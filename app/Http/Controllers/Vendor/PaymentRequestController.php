<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\BankingDetails;
use App\Models\Wallet;
use App\Traits\BankingDetailsValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class PaymentRequestController extends Controller
{
    use BankingDetailsValidation;

    public function index()
    {
        $restaurant = Session::get('restaurant');
        $paymentRequests = PaymentRequest::where('vendor_id', $restaurant->vendor_id)
            ->with(['bankingDetails'])
            ->latest()
            ->get();
        
        $vendorWallet = Wallet::where("vendor_id", $restaurant->vendor_id)->first();
        $bankingDetails = BankingDetails::where('vendor_id', $restaurant->vendor_id)->first();
        
        // Check banking details completeness
        $bankingCompleteness = null;
        if ($bankingDetails) {
            $bankingCompleteness = $this->checkBankingDetailsCompleteness([
                'account_number' => $bankingDetails->account_number,
                'ifsc_code' => $bankingDetails->ifsc_code,
                'account_holder_name' => $bankingDetails->account_holder_name,
                'bank_name' => $bankingDetails->bank_name,
                'upi_id' => $bankingDetails->upi_id,
            ]);
        }
        
        return view('vendor-views.payment-request.index', compact(
            'paymentRequests', 
            'vendorWallet', 
            'bankingDetails',
            'bankingCompleteness'
        ));
    }

    public function storeRequstedTxns(Request $request)
    {
        try {
            // Enhanced validation rules
            $request->validate([
                'amount' => 'required|numeric|min:1|max:999999.99',
                'payment_method' => 'required|in:bank_transfer,upi',
                'banking_details_id' => 'required|exists:banking_details,id',
                'payments_note' => 'nullable|string|max:500',
            ], [
                'amount.min' => 'Amount must be at least ₹1',
                'amount.max' => 'Amount cannot exceed ₹999,999.99',
                'payment_method.in' => 'Payment method must be bank transfer or UPI',
                'banking_details_id.required' => 'Please select banking details',
                'banking_details_id.exists' => 'Selected banking details not found'
            ]);

            // Get restaurant and admin data
            $restaurant = Session::get('restaurant');
            $admin = Helpers::getAdmin();
            $vendorWallet = Wallet::where('vendor_id', $restaurant->vendor_id)->first();
            
            if (!$vendorWallet || $vendorWallet->balance < $request->amount) {
                throw new \Exception("Insufficient Balance. Available: ₹" . ($vendorWallet ? number_format($vendorWallet->balance, 2) : 0));
            }

            // Validate banking details
            $bankingDetails = BankingDetails::where('id', $request->banking_details_id)
                ->where('vendor_id', $restaurant->vendor_id)
                ->first();
                
            if (!$bankingDetails) {
                throw new \Exception('Banking details not found or not owned by your account');
            }

            // Validate payment method compatibility with banking details
            if ($request->payment_method === 'bank_transfer') {
                if (!$bankingDetails->account_number || !$bankingDetails->ifsc_code || !$bankingDetails->account_holder_name) {
                    throw new \Exception('Bank account details are incomplete. Please update your banking information.');
                }
            }
            
            if ($request->payment_method === 'upi') {
                if (!$bankingDetails->upi_id) {
                    throw new \Exception('UPI ID is required for UPI payments. Please update your banking information.');
                }
            }

            // Start transaction
            DB::beginTransaction();

            // Create compact payment information based on method
            $paymentInfo = [
                'ip' => $request->ip(),
                'user_agent' => substr($request->userAgent(), 0, 100), // Limit user agent length
                'requested_at' => now()->format('Y-m-d H:i:s'),
                'wallet_balance' => (float) $vendorWallet->balance,
            ];

            if ($request->payment_method === 'bank_transfer') {
                $paymentInfo['banking'] = [
                    'holder' => $bankingDetails->account_holder_name,
                    'bank' => $bankingDetails->bank_name,
                    'account' => $bankingDetails->account_number,
                    'ifsc' => $bankingDetails->ifsc_code,
                ];
            } else {
                $paymentInfo['banking'] = [
                    'upi' => $bankingDetails->upi_id,
                ];
            }

            // Create the payment request
            $requestedTXns = PaymentRequest::create([
                'amount' => $request->amount,
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'payments_note' => $request->payments_note,
                'restaurant_id' => $restaurant->id,
                'admin_id' => $admin->id,
                'vendor_id' => $restaurant->vendor_id,
                'banking_details_id' => $bankingDetails->id,
                'pending' => now(),
                'data' => $paymentInfo
            ]);

            // Optionally deduct from wallet (uncomment if you want to reserve the amount)
            // $vendorWallet->decrement('balance', $request->amount);

            DB::commit();

            return response()->json([
                'message' => 'Payment request submitted successfully',
                'request_id' => $requestedTXns->id,
                'amount' => number_format($requestedTXns->amount, 2),
                'status' => $requestedTXns->payment_status,
                'payment_method' => $requestedTXns->payment_method,
                'banking_info' => $request->payment_method === 'bank_transfer' 
                    ? "Bank: {$bankingDetails->bank_name} (**** {$bankingDetails->account_number})"
                    : "UPI: {$bankingDetails->upi_id}"
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function getAllRequests(Request $request)
    {
        try {
            $restaurant = Session::get('restaurant');
            $query = PaymentRequest::where('vendor_id', $restaurant->vendor_id);

            // Filter by status
            if ($request->has('status')) {
                $query->where('payment_status', $request->status);
            }

            // Filter by date range
            if ($request->has('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }
            if ($request->has('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            $paymentRequests = $query->latest()->paginate(20);

            return response()->json($paymentRequests);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getRequest($id)
    {
        try {
            $restaurant = Session::get('restaurant');
            $paymentRequest = PaymentRequest::where('id', $id)
                ->where('vendor_id', $restaurant->vendor_id)
                ->firstOrFail();

            return response()->json($paymentRequest);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Payment request not found'], 404);
        }
    }

    public function cancelRequest(Request $request, $id)
    {
        try {
            $restaurant = Session::get('restaurant');
            $paymentRequest = PaymentRequest::where('id', $id)
                ->where('vendor_id', $restaurant->vendor_id)
                ->where('payment_status', 'pending')
                ->firstOrFail();

            $paymentRequest->update([
                'payment_status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->reason ?? 'Cancelled by vendor'
            ]);

            return response()->json(['message' => 'Payment request cancelled successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unable to cancel payment request'], 400);
        }
    }

}
