<?php

namespace Bitrio\Wompi\Listeners;

use Bitrio\Wompi\Payment\WompiRestClient;
use Webkul\Sales\Repositories\OrderTransactionRepository;

class Transaction
{
    protected WompiRestClient $wompiClient;

    protected OrderTransactionRepository $orderTransactionRepository;

    public function __construct(
        WompiRestClient $wompiClient,
        OrderTransactionRepository $orderTransactionRepository
    ) {
        $this->wompiClient = $wompiClient;
        $this->orderTransactionRepository = $orderTransactionRepository;
    }

    /**
     * Guardar la transacción Wompi al generar la factura.
     *
     * @param  \Webkul\Sales\Models\Invoice  $invoice
     * @return void
     */
   public function saveTransaction($invoice)
    {
        if ($invoice->order->payment->method !== 'wompi') {
            return;
        }

        $transactionId = $invoice->order->payment->additional['transaction_id'] ?? null;

        if (!$transactionId) {
            return;
        }

        $transaction = $this->wompiClient->transaction_find_by_id($transactionId);

        if (empty($transaction['data'])) {
            return;
        }

        $transactionData = $transaction['data'];

        $this->orderTransactionRepository->create([
            'transaction_id' => $transactionData['id'],
            'status'         => $transactionData['status'],
            'type'           => $transactionData['payment_method_type'] ?? null,
            'amount'         => $transactionData['amount_in_cents'] / 100,
            'payment_method' => $invoice->order->payment->method,
            'order_id'       => $invoice->order->id,
            'invoice_id'     => $invoice->id,
            'data'           => json_encode($transactionData),
        ]);
    }


}
