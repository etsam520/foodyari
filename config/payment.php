<?php

use Illuminate\Support\Str;

return [
    'default' => env('PAYMENT_GATEWAY', 'phonepe'),
    'merchant_txn_id' => [App\CentralLogics\Helpers::class, 'merchant_txn_id'],
    'merchant_user_id' => 'FOODYARI_PATNS',

    'gateways' => [
        'cashfree' => [
            'appId' => env('CASHFREE_APP_ID'),
            'secretKey' => env('CASHFREE_SECRET_KEY'),
            'env' => env('CASHFREE_ENV', 'TEST'),
        ],

        'phonepe' => [
            'merchantId' => env('PHONEPE_MERCHANT_ID'),
            'saltKey' => env('PHONEPE_SALT_KEY'),
            'saltIndex' => env('PHONEPE_SALT_INDEX'),
            'env' => env('PHONEPE_ENV', 'PRODUCTION'),
            'SHOULDPUBLISHEVENTS' => env('SHOULDPUBLISHEVENTS', true),

        ],

        // Add more gateways as needed
    ],
];
