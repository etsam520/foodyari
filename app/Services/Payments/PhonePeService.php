<?php
namespace App\Services\Payments;

use App\Models\GatewayPayment;
use App\Services\Payments\PaymentGatewayInterface;
use Exception;
use Illuminate\Support\Facades\Http;
use PhonePe\payments\v1\models\request\builders\InstrumentBuilder;
use PhonePe\payments\v1\models\request\builders\PgPayRequestBuilder;
use PhonePe\payments\v1\PhonePePaymentClient;

class PhonePeService implements PaymentGatewayInterface{

    protected $phonePe;
    protected $merchantId;
    protected $merchantUserId;
    protected $merchantTransactionId;


    public function __construct()
    {
        $this->phonePe = new PhonePePaymentClient(
            config('payment.gateways.phonepe.merchantId'),
            config('payment.gateways.phonepe.saltKey'),
            config('payment.gateways.phonepe.saltIndex'),
            config('payment.gateways.phonepe.env'),
            config('payment.gateways.phonepe.SHOULDPUBLISHEVENTS'),
        );

        $this->merchantId = config('payment.gateways.phonepe.merchantId');
        $this->merchantUserId = 'MUID123';
    }

    public function createOrder(array $orderDetails)
    {
        try{
            $this->merchantTransactionId = $orderDetails['merchant_txn_id'];
            $amount = (int) ($orderDetails['amount'] * 100);

            $request = PgPayRequestBuilder::builder()
               ->mobileNumber($orderDetails['phone'])
               ->callbackUrl($orderDetails['returnUrl'])
               ->merchantId($this->merchantId)
               ->merchantUserId($this->merchantUserId)
               ->amount($amount)
               ->merchantTransactionId($this->merchantTransactionId)
               ->redirectUrl($orderDetails['returnUrl'])
               ->redirectMode("REDIRECT")
               //    ->redirectMode("POST")
               ->paymentInstrument(InstrumentBuilder::buildPayPageInstrument())
               ->build();


               $response = $this->phonePe->pay($request);

               $payPageUrl = $response->getInstrumentResponse()->getRedirectInfo()->getUrl();


               $returnData = new \stdClass();
                $returnData->status = 'OK';
                $returnData->paymentLink = $payPageUrl;
                return $returnData;

            // return redirect()->away($payPageUrl);
           }catch(Exception $e){
            return $e;
            //    $message =  $e->getMessage();
               // echo $messsage;die();
            //    return view('user-views.Error.errorhandle-page',compact('message'));
           }
    }

    public function handleCallback(array $requestData)
    {

        $this->merchantTransactionId = $requestData['merchant_txn_id'];
        $checkStatus = $this->phonePe->statusCheck($this->merchantTransactionId);
        $getWayTXN = GatewayPayment::where('merchant_txn_id' , $this->merchantTransactionId)->first();
        $state = strtolower($checkStatus->getState());

        if ($state === 'success' || $state === 'completed') {
            $getWayTXN->payment_status = 'success';
        } elseif ($state === 'failed') {
            $getWayTXN->payment_status = 'failed';
        }

        $getWayTXN->txn_id = $checkStatus->getTransactionId();
        $getWayTXN->responseCode = $checkStatus->getResponseCode();
        $getWayTXN->response = json_encode($checkStatus);
        $getWayTXN->save();

        return $getWayTXN;
    }

    public function  verifyCallback(string $merchant_txn_id)
    {

        $this->merchantTransactionId = $merchant_txn_id;
        $checkStatus = $this->phonePe->statusCheck($this->merchantTransactionId);
        // dd($checkStatus);
        $getWayTXN = GatewayPayment::where('merchant_txn_id' , $this->merchantTransactionId)->first();
        $state = strtolower($checkStatus->getState());

        if ($state === 'success' || $state === 'completed') {
            $getWayTXN->payment_status = 'success';
        } elseif ($state === 'failed') {
            $getWayTXN->payment_status = 'failed';
        }

        $getWayTXN->txn_id = $checkStatus->getTransactionId();
        $getWayTXN->responseCode = $checkStatus->getResponseCode();
        $getWayTXN->response = json_encode($checkStatus);
        $getWayTXN->save();

        return $getWayTXN;
    }
/*

$checkStatus->getResponseCode();
$checkStatus->getState();
$checkStatus->getTransactionId();
*/
}
