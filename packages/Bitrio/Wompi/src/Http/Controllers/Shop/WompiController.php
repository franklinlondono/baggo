<?php

namespace Bitrio\Wompi\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use App\Http\Controllers\Controller;
use Bitrio\Wompi\Payment\WompiRestClient;
use Bitrio\Wompi\Helpers\WompiOrderHelper;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Checkout\Repositories\CartRepository;

class WompiController extends Controller
{
   /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var WompiRestClient
     */
    protected $wompiRestClient;

    /**
     * @var InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * @param OrderRepository $orderRepository
     * @param WompiRestClient $wompiRestClient
     */
    public function __construct(
        OrderRepository $orderRepository,
        WompiRestClient $wompiRestClient,
        InvoiceRepository $invoiceRepository,
        CartRepository $cartRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->wompiRestClient = $wompiRestClient;
        $this->invoiceRepository = $invoiceRepository;
        $this->cartRepository = $cartRepository;
    }

    /**
     * Redirección inicial al widget de pago.
     */
    public function redirect(Request $request)
    {
        $cart = Cart::getCart();
        // dd($cart);
        if(empty($cart)){
           return redirect()->route('shop.home.index');
        }

        // // Crear la orden a partir del carrito
        // $order = $this->orderRepository->create(Cart::toArray());

        return view('wompi::shop.index', [
            'cart' => $cart,
            'publicKey' => core()->getConfigData('sales.payment_methods.wompi.public_key'),
        ]);
    }

    /**
     * Callback desde Wompi cuando termina el pago.
     */
    public function callback(Request $request)
    {
        $transactionId = $request->get('id');

        if (!$transactionId) {
            return redirect()->route('shop.checkout.cart.index')
                ->with('error', 'No se recibió el ID de la transacción.');
        }

        // Consultar transacción en Wompi
        $response = $this->wompiRestClient->transaction_find_by_id($transactionId);
        // dd($response);
        if (empty($response['data'])) {
            return redirect()->route('shop.checkout.cart.index')
                ->with('error', 'Error al consultar el estado del pago.');
        }

        $transaction = $response['data'];

        $statusMap = [
            'APPROVED' => 'processing',       // Pago recibido
            'DECLINED' => 'canceled',         // Pago rechazado
            'PENDING'  => 'pending_payment',  // Pago pendiente de confirmación
            'ERROR'    => 'pending',          // Fallo en el pago
        ];

        // Buscar la orden por referencia
        $cart = $this->cartRepository->find($transaction['reference']);

        if (!$cart) {
            return redirect()->route('admin.sales.orders.index')
            ->with('error', 'Orden no encontrada.');
        }
        // $order = $this->orderRepository->findOneWhere([
        //     'increment_id' => $transaction['reference']
        // ]);
        // if (!$order) {
        //     return redirect()->route('shop.checkout.cart.index')
        //         ->with('error', 'Orden no encontrada.');
        // }
        $data = (new OrderResource($cart))->jsonSerialize();
        $order = $this->orderRepository->create($data);
        $payment = $order->payment;
        $payment->additional = array_merge($payment->additional ?? [], [
            'transaction_id' => $transaction['id'],
            'status'         => $transaction['status'],
        ]);
        $payment->save();

        $order->update([
            'status' => $statusMap[$transaction['status']] ?? 'pending'
        ]);
        
        // // Registrar historial
        // $order->histories()->create([
        //     'comment' => "Pago Wompi: {$transaction['status']} (ID {$transaction['id']})",
        //     'status'  => $orderStatus,
        //     'customer_notified' => true
        // ]);

        switch ($transaction['status']) {
            case 'APPROVED':
                
                if ($order->canInvoice()) {
                    $this->invoiceRepository->create($this->prepareInvoiceData($order));
                }
    
                // session()->forget('wompi_order_id');
                Cart::deActivateCart();

                $message = 'Pago aprobado. Gracias por su compra.';
                $redirect = 'shop.checkout.onepage.success';
                break;
            case 'PENDING':
                $message = 'Su pago está pendiente de confirmación. La orden se mantiene activa.';
                $redirect = 'shop.checkout.cart.index';
                break;
            case 'DECLINED':
                $message = 'El pago fue rechazado. Intente nuevamente.';
                $redirect = 'shop.checkout.cart.index';
                break;
            case 'ERROR':
                $message = 'Ocurrió un error con la transacción. Intente nuevamente.';
                $redirect = 'shop.checkout.cart.index';
                break;
            default:
                $message = 'Estado de pago desconocido.';
                $redirect = 'shop.checkout.cart.index';
        }

        session()->flash('order_id', $order->id);
        session()->flash($transaction['status'] === 'APPROVED' ? 'success' : 'error', $message);

        return redirect()->route($redirect);
    }

    /**
     * Prepares order's invoice data for creation.
     *
     * @param  \Webkul\Sales\Models\Order  $order
     * @return array
     */
    protected function prepareInvoiceData($order)
    {
        $invoiceData = ['order_id' => $order->id];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

    public function createWompiOrder()
    {

        try {
            Cart::collectTotals();

            $this->validateOrder();

            $cart = Cart::getCart();
            // dd($cart);
            
            // $order_id = session()->get('wompi_order_id') ?? null;
            // if(empty($order_id)){
            //     $data = (new OrderResource($cart))->jsonSerialize();
            //     $order = $this->orderRepository->create($data);
            // }else{
            //     $order = $this->orderRepository->findOrFail($order_id);
            // }
            
            // // $this->orderRepository->update(['status' => 'pending'], $order->id);
            // session()->put('wompi_order_id', $order->id);
            // session()->flash('order_id', $order->id);

            $integrityKey = core()->getConfigData('sales.payment_methods.wompi.integrity_key');

            // **Generar la firma SHA256**
            $signatureString = $cart->id . $cart->grand_total * 100 . $cart->cart_currency_code . $integrityKey;
            $signature = hash('sha256', $signatureString);
            

            return response()->json([
                'cart' => [ 
                    'reference'    => $cart->id,
                    'amount_in_cents' => (int) ($cart->grand_total * 100),
                    'currency'        => $cart->cart_currency_code,
                    'signature'       => $signature,
                ],
            ]);
        } catch (\Exception $e) {
            session()->flash('error', trans('shop::app.common.error'));

            throw $e;
        }
    }

    /**
     * Validate order before creation.
     *
     * @return void|\Exception
     */
    protected function validateOrder()
    {
        $cart = Cart::getCart();

        $minimumOrderAmount = (float) core()->getConfigData('sales.order_settings.minimum_order.minimum_order_amount') ?: 0;

        if (! Cart::haveMinimumOrderAmount()) {
            throw new \Exception(trans('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]));
        }

        if (
            $cart->haveStockableItems()
            && ! $cart->shipping_address
        ) {
            throw new \Exception(trans('shop::app.checkout.cart.check-shipping-address'));
        }

        if (! $cart->billing_address) {
            throw new \Exception(trans('shop::app.checkout.cart.check-billing-address'));
        }

        if (
            $cart->haveStockableItems()
            && ! $cart->selected_shipping_rate
        ) {
            throw new \Exception(trans('shop::app.checkout.cart.specify-shipping-method'));
        }

        if (! $cart->payment) {
            throw new \Exception(trans('shop::app.checkout.cart.specify-payment-method'));
        }
    }

    



}
