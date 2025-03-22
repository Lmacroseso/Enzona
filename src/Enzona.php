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

    public function __construct($username, $password, $apiKey, $apiSecret, $sandBox)
    {
        $this->username = env('ENZONA_USERNAME');
        $this->password = env('ENZONA_PASSWORD');
        $this->apiKey = env('ENZONA_API_KEY');
        $this->apiSecret = env('ENZONA_API_SECRET');
        $this->sandBox = env('ENZONA_SANDBOX', true);

        // Se obtiene el token al inicializar la clase
        $this->accessToken = (new RequestToken($this->username, $this->password, $this->apiKey, $this->apiSecret, $this->sandBox))->requestToken();
    }

    // Método para crear un pago
    public function createPayment($amount, $currency, $paymentMethod)
    {
        $createPayment = new CreatePayment($this->accessToken);
        return $createPayment->processPayment($amount, $currency, $paymentMethod);
    }

    // Método para confirmar un pago
    public function confirmPayment($paymentId)
    {
        $confirmPayment = new ConfirmPayment($this->accessToken);
        return $confirmPayment->processConfirmation($paymentId);
    }

    // Método para cancelar un pago
    public function cancelPayment($paymentId)
    {
        $cancelPayment = new CancelPayment($this->accessToken);
        return $cancelPayment->processCancellation($paymentId);
    }

    // Método para reembolsar un pago
    public function refundPayment($paymentId, $amount)
    {
        $refundPayment = new RefundPayment($this->accessToken);
        return $refundPayment->processRefund($paymentId, $amount);
    }
}
