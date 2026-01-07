<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;


class RazorPayController extends Controller
{
    public $api ;

    public function __construct()
    {
        $this->api = new Api($api_key, $api_secret);
    }
}
