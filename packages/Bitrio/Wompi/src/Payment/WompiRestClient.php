<?php

namespace Bitrio\Wompi\Payment;

use Webkul\Payment\Payment\Payment;
use Bitrio\Wompi\WompiSdk\Core\WompiSandboxEnvironment;
use Bitrio\Wompi\WompiSdk\Core\WompiProductionEnvironment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WompiRestClient extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'wompi';

    /**
     * @var Client
     */
    protected $client;

    public function __construct()
    {
        $environment = $this->environment();

        $this->client = new Client([
            'base_uri' => $environment->getBaseUrl(),
        ]);
    }


    /**
     * Implementa el método abstracto requerido.
     *
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return route('wompi.callback');
    }
    /**
     * Retorna el objeto de entorno de Wompi (Sandbox o Producción).
     */
    public function environment()
    {
        $isSandbox = $this->getConfigData('sandbox');

        return $isSandbox
            ? new WompiSandboxEnvironment()
            : new WompiProductionEnvironment();
    }

    /**
     * Buscar transacción por ID en Wompi.
     *
     * @param string $id
     * @return array|null
     */
    public function transaction_find_by_id(string $id)
    {
        try {
            return $this->handlerError(function () use ($id) {

            $request = $this->client->get("v1/transactions/{$id}", $this->getHeader());
            return $request;
        });
            // dd($id);
            // $response = $this->client->get("/transactions/{$id}", $this->getHeader());

            // return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            \Log::error("Error consultando transacción Wompi: " . $e->getMessage());
            return null;
        }
    }

     /**
     * Enganchado de errores
     */
    public function handlerError($callback)
    {
        try {
            return json_decode($callback()->getBody()->getContents(),true);
        } catch (ClientException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    /**
     * Genera headers con autorización.
     */
    public function getHeader($token = null)
    {
        $token = $token ?? $this->getPrivateKey();
        // dd($token);

        return [
            'headers' => [
                'Authorization' => "Bearer {$token}",
                'Accept'        => 'application/json',
            ]
        ];
    }

    /**
     * Obtiene la llave privada según el entorno.
     */
    protected function getPrivateKey()
    {
        $isSandbox = $this->getConfigData('sandbox');

        return $this->getConfigData('private_key');
        // $isSandbox
            // ? $this->getConfigData('private_key_sandbox')
            // : $this->getConfigData('private_key');
    }


}
