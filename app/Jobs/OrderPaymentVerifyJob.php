<?php

namespace App\Jobs;

use App\CentralLogics\Helpers;
use App\Http\Controllers\User\Restaurant\CheckoutController;
use App\Models\Customer;
use App\Models\GatewayPayment;
use App\Services\Payments\PaymentGatewayFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\error;

class OrderPaymentVerifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $remainingRuns;
    /**
     * Create a new job instance.
     */
    public function __construct(int $remainingRuns = 8)
    {
        $this->remainingRuns = $remainingRuns;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order_sessions = DB::table('order_sessions')->
            where('gateway_data','!=',null)->latest()->get();

        $order_sessions->each(function ($order_session) {
            try {
                $getWayData = json_decode($order_session->gateway_data, true);
                DB::beginTransaction();
                if($getWayData['gateway'] == 'phonepe'){
                    $paymentGateway = PaymentGatewayFactory::make('phonepe');
                    $response = $paymentGateway->verifyCallback($getWayData['merchant_txn_id']);
                    // $response = $paymentGateway->verifyCallback('FY-phonepe-228655-O');
                    if($response->payment_status == 'success'){
                        $user = Customer::find($order_session->customer_id);
                        $getWayTXN = GatewayPayment::where('merchant_txn_id' , $getWayData['merchant_txn_id'])->first();
                        $order = CheckoutController::placeOrderProcess($user);
                        $getWayTXN->details = json_encode(['order_id' => $order->id]);
                        $getWayTXN->save();
                        CheckoutController::online_txn_sattlement($response, $order,$user, $order_session); // public static function online_txn_sattlement($paidTxn, $order, $user, $order_session)
                        Log::info('Order Placed SuccessFully', ['userId' => $user->id, 'order' => $order->toArray()]);
                    }elseif($response->payment_status == 'failed'){
                        $user = Customer::find($order_session->customer_id);
                        DB::table('order_sessions')->where('id', $order_session->id)->update(['gateway_data' => null]);
                        if ($user) {
                            $notification = [
                                'type' => 'Manual',
                                'subject' => "Payment Failed : Order Couldn't be placed",
                                'message' => "",
                            ];
                            Helpers::sendOrderNotification($user,$notification);
                        }
                    }

                }
                Log::info('order job done');
                DB::commit();
            }catch(\Throwable $th){
                DB::rollBack();
                Log::error('Error confirming order: ' . $th->getMessage());
            }
        });

        // 🔁 Re-dispatch if there are remaining
        if ($this->remainingRuns > 1) {
            OrderPaymentVerifyJob::dispatch($this->remainingRuns - 1)
                ->delay(now()->addSeconds(15));
        }
    }
}
