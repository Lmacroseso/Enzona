<?php

namespace Lmacroseso\Enzona;

use Lmacroseso\Enzona\Payment\CreatePayment;
use Lmacroseso\Enzona\Payment\ConfirmPayment;
use Lmacroseso\Enzona\Payment\CancelPayment;
use Lmacroseso\Enzona\Payment\RefundPayment;

class Enzona
{
    private $accessToken;
    private $username;
    private $password;
    private $apiKey;
    private $apiSecret;
    private $sandBox;
    private $usedHost;

    // Constructor que toma las configuraciones desde el archivo config/enzona.php
    public function __construct()
    {
        $this->username = config('enzona.username');
        $this->password = config('enzona.password');
        $this->apiKey = config('enzona.api_key');
        $this->apiSecret = config('enzona.api_secret');
        $this->sandBox = config('enzona.sandbox', true);

        // Determina si usar el host de producción o sandbox según la configuración
        $this->usedHost = ($this->sandBox) ? 'https://sandbox.enzona.com/api/v1/' : 'https://api.enzona.com/api/v1/';

        // Se obtiene el token al inicializar la clase
        $this->accessToken = (new RequestToken($this->username, $this->password, $this->apiKey, $this->apiSecret, $this->sandBox))->requestToken();
    }

    // Método para crear un pago
    public function createPayment($amount, $currency, $paymentMethod)
    {
        $createPayment = new CreatePayment($this->accessToken, $this->usedHost);
        return $createPayment->processPayment($amount, $currency, $paymentMethod);
    }

    // Método para confirmar un pago
    public function confirmPayment($paymentId)
    {
        $confirmPayment = new ConfirmPayment($this->accessToken, $this->usedHost);
        return $confirmPayment->processConfirmation($paymentId);
    }

    // Método para cancelar un pago
    public function cancelPayment($paymentId)
    {
        $cancelPayment = new CancelPayment($this->accessToken, $this->usedHost);
        return $cancelPayment->processCancellation($paymentId);
    }

    // Método para reembolsar un pago
    public function refundPayment($paymentId, $amount)
    {
        $refundPayment = new RefundPayment($this->accessToken, $this->usedHost);
        return $refundPayment->processRefund($paymentId, $amount);
    }
}
