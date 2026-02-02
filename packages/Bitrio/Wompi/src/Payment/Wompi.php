<?php

namespace Bitrio\Wompi\Payment;

use Webkul\Payment\Payment\Payment;
use Illuminate\Support\Facades\Storage;

class Wompi extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     * 
     */
    protected $code = 'wompi';
    /**
     * Devuelve el título configurado en admin.
     *
     * @return string
     */
    public function getTitle()
    {
        return core()->getConfigData('sales.payment_methods.wompi.title') ?? 'Wompi';
    }

    /**
     * Devuelve la descripción configurada en admin.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return core()->getConfigData('sales.payment_methods.wompi.description');
    }

    /**
     * Devuelve el orden en el checkout.
     *
     * @return int
     */
    public function getSortOrder()
    {
        return core()->getConfigData('sales.payment_methods.wompi.sort') ?? 1;
    }

    /**
     * Verifica si el método de pago está disponible.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->getConfigData('active');
    }
    /**
     * Returns redirect url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        // Redirige al controlador que inicia la transacción con Wompi
        return route('wompi.redirect');
    }

    /**
     * Get payment method image.
     *
     * @return string
     */
    public function getImage()
    {
        $image = $this->getConfigData('image');

        return $image ? Storage::url($image) : null;
    }

    public function getAdditionalDetails()
    {
        //
    }

} 
