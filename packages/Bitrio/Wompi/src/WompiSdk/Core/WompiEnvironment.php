<?php

namespace Bitrio\Wompi\WompiSdk\Core;

abstract class WompiEnvironment
{
    /**
     * La llave secreta de la API de Wompi.
     */
    protected string $secretKey;

    /**
     * @param string $secretKey La llave secreta para el entorno.
     */
    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * Obtiene la llave secreta para las peticiones a la API.
     *
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * Obtiene la URL base de la API para el entorno.
     * Esta es la URL que cambia entre el entorno de pruebas y el de producción.
     *
     * @return string
     */
    abstract public function getBaseUrl(): string;
}