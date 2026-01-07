<?php

namespace App\Services\Payments;

use InvalidArgumentException;

class PaymentGatewayFactory
{
    public static function make($gateway = null)
    {
        $gateway = $gateway ?: config('payment.default');

        switch ($gateway) {
            case 'cashfree':
                return new CashfreeService();
            case 'phonepe':
                return new PhonePeService();
            default:
                throw new InvalidArgumentException("Unsupported payment gateway [{$gateway}]");
        }
    }
}
