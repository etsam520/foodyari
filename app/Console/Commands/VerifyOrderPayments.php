<?php

namespace App\Console\Commands;

use App\CentralLogics\Helpers;
use App\Models\GatewayPayment;
use App\Services\Payments\PaymentGatewayFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\User\Restaurant\CheckoutController;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class VerifyOrderPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:verify-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify payment status for pending orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // dd(34343);
        // $merchant_txn_id = "FY-phonepe-882148-O";
        // $paymentGateway = PaymentGatewayFactory::make('phonepe');

        // $response = $paymentGateway->verifyCallback($merchant_txn_id);
        $order_sessions = DB::table('order_sessions')->where('gateway_data', '!=', null)->latest()->get();
        // dd($order_sessions);

        $order_sessions->each(function ($order_session) {
            try {
                $getWayData = json_decode($order_session->gateway_data, true);
                DB::beginTransaction();
                if ($getWayData['gateway'] == 'phonepe') {
                    $paymentGateway = PaymentGatewayFactory::make('phonepe');
                    $response = $paymentGateway->verifyCallback($getWayData['merchant_txn_id']);
                    // $response = $paymentGateway->verifyCallback('FY-phonepe-228655-O');
                    if ($response->payment_status == 'success') {
                        $user = Customer::find($order_session->customer_id);
                        $getWayTXN = GatewayPayment::where('merchant_txn_id', $getWayData['merchant_txn_id'])->first();
                        $order = CheckoutController::placeOrderProcess($user);
                        $getWayTXN->details = json_encode(['order_id' => $order->id]);
                        $getWayTXN->save();
                        CheckoutController::online_txn_sattlement($response, $order,$user,$order_session);
                        // Log::info('Order Placed SuccessFully', ['userId' => $user->id, 'order' => $order->toArray()]);
                    } elseif ($response->payment_status == 'failed') {
                        $user = Customer::find($order_session->customer_id);
                        DB::table('order_sessions')->where('id', $order_session->id)->update(['gateway_data' => null]);
                        if ($user) {
                            $notification = [
                                'type' => 'Manual',
                                'subject' => "Payment Failed : Order Couldn't be placed",
                                'message' => "",
                            ];
                            Helpers::sendOrderNotification($user, $notification);
                        }
                    }
                }

                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                dd($th);
            }
        });
    }
}
