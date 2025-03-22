<?php

namespace Macroseso\Enzona;

use Macroseso\Enzona\Payment\CreatePayment;
use Macroseso\Enzona\Payment\ConfirmPayment;
use Macroseso\Enzona\Payment\CancelPayment;
use Macroseso\Enzona\Payment\RefundPayment;
use Macroseso\Enzona\RequestToken;

class Enzona
{
    private $accessToken;
    private $username;
    private $password;
    private $apiKey;
    private $apiSecret;
    private $sandBox;

    public function __construct()
    {
        // Obtener datos del archivo de configuración
        $this->username = config('enzona.username');
        $this->password = config('enzona.password');
        $this->apiKey = config('enzona.api_key');
        $this->apiSecret = config('enzona.api_secret');
        $this->sandBox = config('enzona.sandbox', true);

        try {
            // Obtener el token de acceso
            $this->accessToken = (new RequestToken($username, $password, $apiKey, $apiSecret))->requestToken();
        } catch (Exception $e) {
            throw new Exception("Error al obtener el token de Enzona: " . $e->getMessage());
        }
    }

    /**
     * Crear un pago en Enzona
     */
    public function createPayment(array $data)
    {
        try {
            $createPayment = new CreatePayment($this->accessToken, $this->usedHost);
            return $createPayment->processPayment($data);
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }


public function cancelPayment($paymentId)
{
    try {
        $cancelPayment = new CancelPayment($this->accessToken, $this->usedHost, $this->apiRoute);
        return $cancelPayment->cancel($paymentId);
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

    // Método para reembolsar un pago
    public function refundPayment($paymentId, $amount)
    {
        $refundPayment = new RefundPayment($this->accessToken);
        return $refundPayment->processRefund($paymentId, $amount);
    }

    // Método para obtener el token actual
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
