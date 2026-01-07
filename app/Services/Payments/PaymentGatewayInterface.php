<?php 

namespace App\Services\Payments;

interface PaymentGatewayInterface
{
    public function createOrder(array $orderDetails);
    public function handleCallback(array $requestData);
}