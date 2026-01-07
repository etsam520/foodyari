<?php

namespace App\Http\Controllers\Admin\refund;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\AdminFund;
use App\Models\Order;
use App\Models\Refund;
use App\Models\RefundReason;
use App\Models\Wallet;
use Google\Rpc\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

class RefundController extends Controller
{
    // List all refund requests
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');
        
        $refunds = Refund::with(['order', 'customer'])
            ->when($status !== 'all', function($query) use ($status) {
                return $query->where('refund_status', $status);
            })
            ->when($search, function($query) use ($search) {
                return $query->whereHas('order', function($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%");
                })->orWhereHas('customer', function($q) use ($search) {
                    $q->where('f_name', 'like', "%{$search}%")
                      ->orWhere('l_name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin-views.refund.index', compact('refunds', 'status'));
    }

    // Show refund details
    public function show($id)
    {
        $refund = Refund::with(['order.details', 'customer'])->findOrFail($id);
        return view('admin-views.refund.show', compact('refund'));
    }

    // Process refund (approve/reject)
    public function process(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_note' => 'nullable|string|max:500',
            'refund_method' => 'required_if:action,approve|in:wallet,original_payment,bank_transfer'
        ]);

        try {
            DB::beginTransaction();

            $refund = Refund::with(['order', 'customer'])->findOrFail($id);
            // dd($refund);
            
            if ($refund->refund_status !== Refund::STATUS_PENDING) {
                throw new \Exception('This refund request has already been processed.');
            }

            if ($request->action === 'approve') {
                $this->approveRefund($refund, $request);
            } else {
                $this->rejectRefund($refund, $request);
            }

            DB::commit();

             

            $message = $request->action === 'approve' 
                ? 'Refund approved successfully' 
                : 'Refund rejected successfully';
                
            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // Approve refund
    private function approveRefund(Refund $refund, Request $request)
    {
        dd($refund);
        $order = $refund->order;
        $customer = $refund->customer;
        $admin = Helpers::getAdmin();

        // Update refund status
        $refund->update([
            'refund_status' => Refund::STATUS_APPROVED,
            'refund_method' => $request->refund_method,
            'processed_by' => $admin->id,
            'processed_at' => now(),
            'admin_note' => $request->admin_note
        ]);

        // Update order status
        $order->update([
            'order_status' => 'refunded',
            'refund_details' => json_encode([
                'refund_id' => $refund->id,
                'refund_amount' => $refund->refund_amount,
                'refund_method' => $request->refund_method,
                'processed_at' => now()
            ])
        ]);

        // Process refund based on method
        if ($request->refund_method === Refund::METHOD_WALLET) {
            $this->processWalletRefund($refund, $customer);
        }

        // Send notification to customer
        $this->sendRefundNotification($customer, $refund, 'approved');
    }

    // Reject refund
    private function rejectRefund(Refund $refund, Request $request)
    {
        $admin = Auth::user();

        $refund->update([
            'refund_status' => Refund::STATUS_REJECTED,
            'processed_by' => $admin->id,
            'processed_at' => now(),
            'admin_note' => $request->admin_note
        ]);

        // Update order status
        $refund->order->update([
            'order_status' => 'refund_request_canceled'
        ]);

        // Send notification to customer
        $this->sendRefundNotification($refund->customer, $refund, 'rejected');
    }

    // Process wallet refund
    private function processWalletRefund(Refund $refund, $customer)
    {
        // Get customer wallet
        $customerWallet = Wallet::firstOrCreate(['customer_id' => $customer->id]);
        
        // Add refund amount to wallet
        $customerWallet->balance += $refund->refund_amount;
        $customerWallet->save();

        // Create wallet transaction
        $customerWallet->walletTransactions()->create([
            'amount' => $refund->refund_amount,
            'type' => 'received',
            'customer_id' => $customer->id,
            'remarks' => "Refund for Order #{$refund->order->id} - {$refund->refund_reason}",
        ]);

        // Deduct from admin fund
        $adminFund = AdminFund::getFund();
        $adminFund->balance -= $refund->refund_amount;
        $adminFund->save();

        $adminFund->txns()->create([
            'amount' => $refund->refund_amount,
            'txn_type' => 'paid',
            'paid_to' => 'customer',
            'customer_id' => $customer->id,
            'remarks' => "Refund processed for Order #{$refund->order->id} to {$customer->f_name} {$customer->l_name}"
        ]);

        // Update refund status to processed
        $refund->update([
            'refund_status' => Refund::STATUS_PROCESSED,
            'transaction_reference' => 'WALLET_' . time()
        ]);
    }

    // Send refund notification
    private function sendRefundNotification($customer, $refund, $status)
    {
        $notification = [
            'type' => 'Manual',
            'subject' => $status === 'approved' ? 'Refund Approved' : 'Refund Rejected',
            'message' => $status === 'approved' 
                ? "Your refund request for Order #{$refund->order->id} has been approved. Amount: " . Helpers::format_currency($refund->refund_amount)
                : "Your refund request for Order #{$refund->order->id} has been rejected."
        ];
        
        Helpers::sendOrderNotification($customer, $notification);
    }

    // Refund reasons management
    public function reasons()
    {
        $reasons = RefundReason::orderBy('created_at', 'desc')->paginate(15);
        return view('admin-views.refund.reasons', compact('reasons'));
    }

    // Store new refund reason
    public function storeReason(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:255|unique:refund_reasons,reason',
            'status' => 'boolean',
            'user_type' => 'required|in:admin,customer,restaurant,delivery_man'
        ]);

        RefundReason::create([
            'reason' => $request->reason,
            'status' => $request->status ?? true,
            'user_type' => $request->user_type,
            'created_by' => Auth::id()
        ]);

        return back()->with('success', 'Refund reason added successfully');
    }

    // Update refund reason status
    public function toggleReasonStatus($id)
    {
        $reason = RefundReason::findOrFail($id);
        $reason->update(['status' => !$reason->status]);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    // Delete refund reason
    public function deleteReason($id)
    {
        RefundReason::findOrFail($id)->delete();
        return back()->with('success', 'Refund reason deleted successfully');
    }

    // Create refund for specific order
    public function createRefund(Request $request, $orderId)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0.01',
            'restaurant_deduction_amount' => 'required|numeric|min:0',
            'restaurant_deduction_reason' => 'nullable|string|max:500',
            'refund_reason' => 'required|string|max:255',
            'refund_type' => 'required|in:full,partial',
            'admin_note' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $order = Order::with('customer')->findOrFail($orderId);

            // Validate refund amount
            if ($request->refund_amount > $order->order_amount) {
                throw new \Exception('Refund amount cannot exceed order amount');
            }

            // Validate restaurant deduction amount
            if ($request->restaurant_deduction_amount > $request->refund_amount) {
                throw new \Exception('Restaurant deduction amount cannot exceed refund amount');
            }

            // Check if order can be refunded
            if (!in_array($order->order_status, ['delivered', 'canceled'])) {
                throw new \Exception('Only delivered or canceled orders can be refunded');
            }

            // Check for existing refund
            $existingRefund = Refund::where('order_id', $orderId)
                ->whereIn('refund_status', ['pending', 'approved', 'processed'])
                ->first();

            if ($existingRefund) {
                throw new \Exception('A refund request already exists for this order');
            }

            // Create refund
            $refund = Refund::create([
                'order_id' => $orderId,
                'customer_id' => $order->customer_id,
                'refund_amount' => $request->refund_amount,
                'restaurant_deduction_amount' => $request->restaurant_deduction_amount,
                'restaurant_deduction_reason' => $request->restaurant_deduction_reason,
                'refund_reason' => $request->refund_reason,
                'refund_status' => Refund::STATUS_PENDING,
                'refund_type' => $request->refund_type,
                'admin_note' => $request->admin_note,
                'refund_details' => [
                    'created_by' => 'admin',
                    'admin_id' => Auth::id()
                ]
            ]);

            // Update order status
            $order->update(['order_status' => 'refund_requested']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Refund request created successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Update restaurant deduction for a refund
    public function updateDeduction(Request $request, $id)
    {
        try {
            $refund = Refund::findOrFail($id);
            
            // Only allow updates for pending refunds
            if ($refund->refund_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Can only modify deduction for pending refunds'
                ], 400);
            }
            $request->validate([
                'refund_amount' => 'required|numeric|min:0.01',
                'restaurant_deduction_amount' => 'required|numeric|min:0',
                'restaurant_deduction_reason' => 'nullable|string|max:500',
                'refund_reason' => 'required|string|max:255',
                'refund_type' => 'required|in:full,partial',
                'admin_note' => 'nullable|string|max:500'
            ]);

            

            DB::beginTransaction();

            $refund->update([
                'refund_amount' => $request->refund_amount,
                'restaurant_deduction_amount' => $request->restaurant_deduction_amount,
                'restaurant_deduction_reason' => $request->restaurant_deduction_reason,
                'refund_reason' => $request->refund_reason,
                'refund_status' => Refund::STATUS_PENDING,
                'refund_type' => $request->refund_type,
                'admin_note' => $request->admin_note,
                'refund_details' => [
                    'created_by' => 'admin',
                    'admin_id' => Auth::id()
                ]
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Deduction updated successfully',
                'data' => [Refund::findOrFail($id)]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
