<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\AdminFund;
use App\Models\PaymentRequest;
use App\Models\BankingDetails;
use App\Models\Vendor;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function list(Request $request)
    {
        $status = $request->get('status', 'all');
        $searchTerm = $request->get('search');
        $dateRange = $request->get('date_range');
        
        $query = PaymentRequest::with(['vendor', 'bankingDetails'])
            ->when($status !== 'all', function($q) use ($status) {
                return $q->where('payment_status', $status);
            })
            ->when($searchTerm, function($q) use ($searchTerm) {
                return $q->whereHas('vendor', function($vendor) use ($searchTerm) {
                    $vendor->where('f_name', 'like', "%{$searchTerm}%")
                           ->orWhere('l_name', 'like', "%{$searchTerm}%")
                           ->orWhere('phone', 'like', "%{$searchTerm}%");
                })->orWhere('txn_id', 'like', "%{$searchTerm}%")
                  ->orWhere('amount', 'like', "%{$searchTerm}%");
            })
            ->when($dateRange, function($q) use ($dateRange) {
                if ($dateRange === 'today') {
                    return $q->whereDate('created_at', today());
                } elseif ($dateRange === 'week') {
                    return $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($dateRange === 'month') {
                    return $q->whereMonth('created_at', now()->month)
                             ->whereYear('created_at', now()->year);
                }
                return $q;
            });
        
        $paymentRequests = $query->latest()->paginate(15);
        
        // Statistics
        $stats = [
            'total' => PaymentRequest::count(),
            'pending' => PaymentRequest::where('payment_status', 'pending')->count(),
            'approved' => PaymentRequest::where('payment_status', 'approved')->count(),
            'processing' => PaymentRequest::where('payment_status', 'processing')->count(),
            'completed' => PaymentRequest::where('payment_status', 'complete')->count(),
            'rejected' => PaymentRequest::where('payment_status', 'rejected')->count(),
            'total_amount' => PaymentRequest::sum('amount'),
            'paid_amount' => PaymentRequest::where('payment_status', 'complete')->sum('amount_paid'),
        ];
        
        return view('admin-views.payments.index', compact('paymentRequests', 'stats', 'status', 'searchTerm', 'dateRange'));
    }

    public function payform(Request $request)
    {
        $pay_key = $request->query('pay_key');
        $paymentRequest = PaymentRequest::with(['vendor', 'bankingDetails'])->findOrFail($pay_key);
        $vendorWallet = Wallet::where('vendor_id', $paymentRequest->vendor_id)->first();
        
        // Get admin fund balance
        $adminFund = AdminFund::getFund();
        
        return response()->json([
            'view' => view('admin-views.payments._pay-form', compact('paymentRequest', 'vendorWallet', 'adminFund'))->render()
        ]);
    }

    public function approveRequest(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $paymentRequest = PaymentRequest::with(['vendor', 'bankingDetails'])->findOrFail($id);
            $admin = Auth::guard('admin')->user();
            
            // Update request status to approved
            $paymentRequest->update([
                'payment_status' => 'approved',
                'approved' => now(),
                'remarks' => $request->get('remarks', 'Payment request approved by admin'),
                'data' => array_merge($paymentRequest->data ?? [], [
                    'approval' => [
                        'approved_by' => $admin->id,
                        'approved_at' => now()->toISOString(),
                        'approval_notes' => $request->get('remarks'),
                    ]
                ])
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Payment request approved successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Error approving payment request: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rejectRequest(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $paymentRequest = PaymentRequest::findOrFail($id);
            $admin = Auth::guard('admin')->user();
            
            $request->validate([
                'rejection_reason' => 'required|string|max:500'
            ]);
            
            $paymentRequest->update([
                'payment_status' => 'rejected',
                'reject' => now(),
                'remarks' => $request->rejection_reason,
                'data' => array_merge($paymentRequest->data ?? [], [
                    'rejection' => [
                        'rejected_by' => $admin->id,
                        'rejected_at' => now()->toISOString(),
                        'rejection_reason' => $request->rejection_reason,
                    ]
                ])
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Payment request rejected successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Error rejecting payment request: ' . $e->getMessage()
            ], 500);
        }
    }

    public function savePaymentRequest(Request $request)
    {
        try {
            // Enhanced validation with custom messages
            $validator = Validator::make($request->all(), [
                'pay_id' => 'required|exists:payment_requests,id',
                'amount' => 'required|numeric|min:0.01|max:999999999.99',
                'payment_method' => 'required|in:bank_transfer,upi,cash,cheque,digital_wallet,online_banking',
                'payment_status' => 'required|in:pending,approved,processing,completed,rejected',
                'remarks' => 'nullable|string|max:500',
                'transaction_id' => 'nullable|string|max:100',
                'reference_number' => 'nullable|string|max:100',
                'attachement' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // 5MB max
            ], [
                'pay_id.required' => 'Payment request ID is required',
                'pay_id.exists' => 'Payment request not found',
                'amount.required' => 'Payment amount is required',
                'amount.numeric' => 'Payment amount must be a valid number',
                'amount.min' => 'Payment amount must be greater than 0',
                'amount.max' => 'Payment amount exceeds maximum limit',
                'payment_method.required' => 'Payment method is required',
                'payment_method.in' => 'The selected payment method is invalid',
                'payment_status.required' => 'Payment status is required',
                'payment_status.in' => 'The selected payment status is invalid',
                'remarks.max' => 'Remarks cannot exceed 500 characters',
                'transaction_id.max' => 'Transaction ID cannot exceed 100 characters',
                'reference_number.max' => 'Reference number cannot exceed 100 characters',
                'attachement.file' => 'Attachment must be a valid file',
                'attachement.mimes' => 'Attachment must be a JPG, PNG, PDF, DOC, or DOCX file',
                'attachement.max' => 'Attachment size cannot exceed 5MB',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            DB::beginTransaction();

            $pay_id = $request->pay_id;
            $paymentRequest = PaymentRequest::with(['vendor', 'bankingDetails'])->findOrFail($pay_id);
            $vendor = Vendor::findOrFail($paymentRequest->vendor_id);
            $vendorWallet = Wallet::where('vendor_id', $vendor->id)->firstOrFail();
            $admin = Auth::guard('admin')->user();

            // Additional business logic validation
            $businessValidationErrors = [];
            
            // Validate amount doesn't exceed requested amount
            if ($request->amount > $paymentRequest->amount) {
                $businessValidationErrors['amount'] = ['Payment amount cannot exceed requested amount of ₹' . number_format($paymentRequest->amount, 2)];
            }

            // Validate wallet balance for completed payments
            if ($request->payment_status == 'completed' && $vendorWallet->balance < $request->amount) {
                $businessValidationErrors['amount'] = ['Payment amount exceeds vendor wallet balance of ₹' . number_format($vendorWallet->balance, 2)];
            }

            // Validate payment method compatibility with banking details
            if ($request->payment_method == 'bank_transfer' && (!$paymentRequest->vendor->banking_details || !$paymentRequest->vendor->banking_details->account_number)) {
                $businessValidationErrors['payment_method'] = ['Bank transfer not available - vendor banking details incomplete'];
            }

            if ($request->payment_method == 'upi' && (!$paymentRequest->vendor->banking_details || !$paymentRequest->vendor->banking_details->upi_id)) {
                $businessValidationErrors['payment_method'] = ['UPI payment not available - vendor UPI details not found'];
            }

            // Validate transaction ID for certain methods
            if (in_array($request->payment_method, ['bank_transfer', 'upi', 'online_banking']) && 
                $request->payment_status == 'completed' && 
                empty($request->transaction_id) && empty($request->reference_number)) {
                $businessValidationErrors['transaction_id'] = ['Transaction ID or reference number is required for ' . str_replace('_', ' ', $request->payment_method)];
            }

            if (!empty($businessValidationErrors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business validation failed',
                    'errors' => $businessValidationErrors
                ], 422);
            }

            // Store previous status for audit
            $previousStatus = $paymentRequest->payment_status;
            
            // Update payment request fields
            $paymentRequest->amount_paid = $request->amount;
            $paymentRequest->payment_method = $request->payment_method;
            $paymentRequest->payment_status = $request->payment_status;
            $paymentRequest->transaction_reference = $request->transaction_id ?? $request->reference_number;
            $paymentRequest->remarks = $request->remarks;

            // Generate transaction ID for completed payments
            if ($request->payment_status == "completed") {
                $paymentRequest->txn_id = "RSA-" . date('dMY-His') . '-' . $paymentRequest->id;
            }
            
            // Set status timestamp
            $paymentRequest[$request->payment_status] = now();
            
            // Handle file attachment
            if ($request->hasFile('attachement')) {
                $paymentRequest->attachment = Helpers::updateFile(
                    $request->file('attachement'),
                    'payment_attachment/',
                    $paymentRequest->attachment ?? null
                );
            }

            // Update data field with processing information
            $paymentData = $paymentRequest->data ?? [];
            $paymentData['processing'] = [
                'processed_by' => $admin->id,
                'processed_at' => now()->toISOString(),
                'previous_status' => $previousStatus,
                'new_status' => $request->payment_status,
                'payment_method_used' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'reference_number' => $request->reference_number,
                'processing_notes' => $request->remarks,
            ];
            
            // Add banking method details if available
            if ($paymentRequest->bankingDetails) {
                if ($request->payment_method === 'bank_transfer') {
                    $paymentData['banking_used'] = [
                        'type' => 'bank_transfer',
                        'bank_name' => $paymentRequest->bankingDetails->bank_name,
                        'account_holder' => $paymentRequest->bankingDetails->account_holder_name,
                        'account_number' => substr($paymentRequest->bankingDetails->account_number, -4),
                        'ifsc_code' => $paymentRequest->bankingDetails->ifsc_code,
                    ];
                } elseif ($request->payment_method === 'upi') {
                    $paymentData['banking_used'] = [
                        'type' => 'upi',
                        'upi_id' => $paymentRequest->bankingDetails->upi_id,
                    ];
                }
            }
            
            $paymentRequest->data = $paymentData;

            // Handle Transactions
            if($request->payment_status == "completed"){
                if ($vendorWallet->balance < $paymentRequest->amount_paid) {
                    throw new \Exception('Amount exceeds the available balance');
                }

                $ADMIN = Helpers::getAdmin();
                if ($paymentRequest->amount_paid > 0) {
                    // Record the cash transaction for the deliveryman
                    $adminFund_deduct = AdminFund::getFund();
                    $adminFund_deduct = AdminFund::getFund();

                    $adminFund_deduct->bankTxns()->create([
                        'amount' => $paymentRequest->amount_paid,
                        'txn_type' => 'paid',
                        'payment_method' =>$request->payment_method,
                        'received_from' => null,
                        'paid_to' => 'vendor',
                        'payment_method' => $request->payment_method,
                        'remarks' => "Wallet Amount Paid to Vendor : $vendor->f_name $vendor->l_name ($vendor->phone) in Cash",
                    ]);
                    $adminFund_deduct->txns()->create([
                        'amount' => $paymentRequest->amount_paid,
                        'txn_type' => 'paid',
                        'received_from' => null,
                        'paid_to' => 'vendor',
                        'payment_method' => $request->payment_method,
                        'vendor_id' => $vendor->id,
                        'remarks' => "Wallet Amount Paid to Vendor : $vendor->f_name $vendor->l_name ($vendor->phone) in Cash",
                    ]);
                    $adminFund_deduct->balance -= $paymentRequest->amount_paid ;
                    $adminFund_deduct->save();

                    // Deduct the amount from the deliveryman's wallet
                    $vendorWallet->balance -= $paymentRequest->amount_paid;
                    $vendorWallet->walletTransactions()->create([
                        'amount' => $paymentRequest->amount_paid,
                        'admin_id' => $ADMIN->id,
                        'type' => 'paid',
                        'payment_method' => 'wallet',
                        'remarks' => 'Clearing Wallet Amount By Admin',
                    ]);
                    $vendorWallet->save();
                    $adminFund_add = AdminFund::getFund();
                    // Add the same amount to the admin fund
                    $adminFund_add->balance += $paymentRequest->amount_paid;
                    $adminFund_add->txns()->create([
                        'amount' => $paymentRequest->amount_paid,
                        'txn_type' => 'received',
                        'received_from' => 'deliveryman',
                        'paid_to' => null,
                        'payment_method' => 'wallet',
                        'vendor_id' => $vendor->id,
                        'remarks' => $request->remarks
                    ]);
                    $adminFund_add->save();
                }

            }
            // Save the updated PaymentRequest object
            $paymentRequest->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Payment request updated successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error for debugging
            Log::error('Payment processing error: ' . $e->getMessage(), [
                'payment_id' => $request->pay_id ?? null,
                'amount' => $request->amount ?? null,
                'method' => $request->payment_method ?? null,
                'status' => $request->payment_status ?? null,
                'admin_id' => Auth::guard('admin')->id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return user-friendly error message
            $errorMessage = 'An error occurred while processing the payment.';
            
            // Check for specific error types
            if (strpos($e->getMessage(), 'balance') !== false) {
                $errorMessage = 'Insufficient wallet balance to process this payment.';
            } elseif (strpos($e->getMessage(), 'exceed') !== false) {
                $errorMessage = 'Payment amount exceeds the requested amount.';
            } elseif (strpos($e->getMessage(), 'banking') !== false) {
                $errorMessage = 'Vendor banking details are incomplete for this payment method.';
            }
            
            return response()->json([
                'success' => false, 
                'message' => $errorMessage,
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show($id)
    {
        $paymentRequest = PaymentRequest::with(['vendor', 'bankingDetails'])
            ->findOrFail($id);
        
        $vendorWallet = Wallet::where('vendor_id', $paymentRequest->vendor_id)->first();
        
        return view('admin-views.payments.show', compact('paymentRequest', 'vendorWallet'));
    }

    public function bulkAction(Request $request)
    {
        try {
            $request->validate([
                'action' => 'required|in:approve,reject,export',
                'payment_ids' => 'required|array',
                'payment_ids.*' => 'exists:payment_requests,id',
                'bulk_remarks' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();
            $admin = Auth::guard('admin')->user();
            $results = [];

            foreach ($request->payment_ids as $paymentId) {
                $paymentRequest = PaymentRequest::findOrFail($paymentId);
                
                if ($request->action === 'approve' && $paymentRequest->payment_status === 'pending') {
                    $paymentRequest->update([
                        'payment_status' => 'approved',
                        'approved' => now(),
                        'remarks' => $request->bulk_remarks ?? 'Bulk approved by admin',
                        'data' => array_merge($paymentRequest->data ?? [], [
                            'bulk_approval' => [
                                'approved_by' => $admin->id,
                                'approved_at' => now()->toISOString(),
                                'bulk_action' => true,
                            ]
                        ])
                    ]);
                    $results[] = "Payment #{$paymentId} approved";
                    
                } elseif ($request->action === 'reject' && in_array($paymentRequest->payment_status, ['pending', 'approved'])) {
                    $paymentRequest->update([
                        'payment_status' => 'rejected',
                        'reject' => now(),
                        'remarks' => $request->bulk_remarks ?? 'Bulk rejected by admin',
                        'data' => array_merge($paymentRequest->data ?? [], [
                            'bulk_rejection' => [
                                'rejected_by' => $admin->id,
                                'rejected_at' => now()->toISOString(),
                                'bulk_action' => true,
                            ]
                        ])
                    ]);
                    $results[] = "Payment #{$paymentId} rejected";
                }
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Bulk action completed successfully',
                'results' => $results
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error performing bulk action: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportPayments(Request $request)
    {
        $status = $request->get('status', 'all');
        $dateRange = $request->get('date_range');
        
        $query = PaymentRequest::with(['vendor', 'bankingDetails'])
            ->when($status !== 'all', function($q) use ($status) {
                return $q->where('payment_status', $status);
            })
            ->when($dateRange, function($q) use ($dateRange) {
                if ($dateRange === 'today') {
                    return $q->whereDate('created_at', today());
                } elseif ($dateRange === 'week') {
                    return $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($dateRange === 'month') {
                    return $q->whereMonth('created_at', now()->month);
                }
                return $q;
            });
        
        $payments = $query->latest()->get();
        
        return view('admin-views.payments.export', compact('payments'));
    }
}

