<?php

namespace App\Services\Payments;

use App\Services\Payments\PaymentGatewayInterface;
use Cashfree\PaymentGateway;

class CashfreeService implements PaymentGatewayInterface
{
    protected $cashfree;

    public function __construct()
    {
        $this->cashfree = new PaymentGateway(config('payment.gateways.cashfree'));
    }

    public function createOrder(array $orderDetails)
    {
        return $this->cashfree->createOrder($orderDetails);
    }

    public function handleCallback(array $requestData)
    {
        return $this->cashfree->verifySignature($requestData);
    }
}
