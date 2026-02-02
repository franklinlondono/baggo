<?php

namespace Bitrio\Wompi\Helpers;

use Webkul\Checkout\Facades\Cart;

class WompiOrderHelper
{
    /**
     * Prepara los datos del carrito para crear una orden en Bagisto
     *
     * @param \Webkul\Checkout\Models\Cart $cart
     * @param array $paymentAdditional (opcional) datos adicionales del pago
     * @return array
     */
    public static function prepareOrderData(array $cartData): array
    {
        return [
            'customer_id' => $cartData['customer_id'],
            'customer_email' => $cartData['customer_email'],
            'status' => $cartData['status'] ?? 'pending_payment',
            'sub_total' => $cartData['sub_total'],
            'grand_total' => $cartData['grand_total'],
            'base_grand_total' => $cartData['grand_total'],
            'discount_amount' => $cartData['discount_amount'] ?? 0,
            'tax_amount' => $cartData['tax_total'] ?? 0,
            'currency_code' => $cartData['currency_code'],

            'billing_address' => [
                'first_name' => $cartData['billing_address']['first_name'],
                'last_name'  => $cartData['billing_address']['last_name'],
                'address1'   => $cartData['billing_address']['address'],
                'city'       => $cartData['billing_address']['city'],
                'state'      => $cartData['billing_address']['state'],
                'postcode'   => $cartData['billing_address']['postcode'],
                'country'    => $cartData['billing_address']['country'],
                'email'      => $cartData['billing_address']['email'],
                'phone'      => $cartData['billing_address']['phone'],
            ],

            'shipping_address' => [
                'first_name' => $cartData['shipping_address']['first_name'],
                'last_name'  => $cartData['shipping_address']['last_name'],
                'address1'   => $cartData['shipping_address']['address'],
                'city'       => $cartData['shipping_address']['city'],
                'state'      => $cartData['shipping_address']['state'],
                'postcode'   => $cartData['shipping_address']['postcode'],
                'country'    => $cartData['shipping_address']['country'],
                'email'      => $cartData['shipping_address']['email'],
                'phone'      => $cartData['shipping_address']['phone'],
            ],

            'items' => array_map(function ($item) {
                return [
                    'product_id'  => $item['product_id'],
                    'name'        => $item['name'],
                    'price'       => $item['price'],
                    'qty_ordered' => $item['qty'],
                ];
            }, $cartData['items']),

            'payment' => $cartData['payment'] ?? [
                'method'     => 'wompi',
                'additional' => [],
            ],
        ];
    }
}
