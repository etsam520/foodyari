<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\Payments\PaymentGatewayInterface;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use PhonePe\payments\v1\models\request\builders\InstrumentBuilder;
use PhonePe\payments\v1\models\request\builders\PgPayRequestBuilder;
use PhonePe\payments\v1\PhonePePaymentClient;

class PhonePayController extends Controller
{

    protected $phonePePaymentsClient;
    protected $indexKey;
    protected $merchantId;
    protected $merchantUserId;
    protected $saltKey;
    protected $merchantTransactionId;




    public function __construct()
    {
        $this->merchantTransactionId = "MT7850590068188107";
        $this->indexKey = 1;
        $this->merchantId = "PGTESTPAYUAT";
        $this->merchantUserId = "MUID123";
        $this->saltKey = '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399';

        $this->phonePePaymentsClient = new PhonePePaymentClient($this->merchantId, $this->saltKey, $this->indexKey, 'UAT', true);
    }

    public function pay()
    {
        try{

         $amount =(int)(100 * 100);
         $request = PgPayRequestBuilder::builder()
            ->mobileNumber("8986265780")
            ->callbackUrl(route('payments.response', ['txn' => $this->merchantTransactionId]))
            ->merchantId($this->merchantId)
            ->merchantUserId($this->merchantUserId)
            ->amount($amount)
            ->merchantTransactionId($this->merchantTransactionId)
            ->redirectUrl(route('payments.response',['txn' => $this->merchantTransactionId]))
            ->redirectMode("REDIRECT")
            ->paymentInstrument(InstrumentBuilder::buildPayPageInstrument())
            ->build();

            $response = $this->phonePePaymentsClient->pay($request);
            $payPageUrl = $response->getInstrumentResponse()->getRedirectInfo()->getUrl();

            return redirect()->away($payPageUrl);
        }catch(Exception $e){
            $message =  $e->getMessage();
            // echo $messsage;die();
            return view('user-views.Error.errorhandle-page',compact('message'));
        }
    }

    public function response(Request $request){
        // dd($request->json());
        $txn = $request->input('txn');
       return $checkStatus = $this->phonePePaymentsClient->statusCheck($txn);
    }
}
