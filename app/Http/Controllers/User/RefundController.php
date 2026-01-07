<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use App\Models\RefundReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RefundController extends Controller
{
    // Show customer refund requests
    public function index()
    {
        $customer = Session::get('userInfo');
        if (!$customer) {
            return redirect()->route('user.auth.login');
        }

        $refunds = Refund::with(['order'])
            ->where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user-views.refund.index', compact('refunds'));
    }

    // Show refund request form
    public function create($orderId)
    {
        $customer = Session::get('userInfo');
        if (!$customer) {
            return redirect()->route('user.auth.login');
        }

        $order = Order::with(['details.food', 'restaurant'])
            ->where('id', $orderId)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found');
        }

        // Check if order can be refunded
        if (!$this->canOrderBeRefunded($order)) {
            return back()->with('error', 'This order cannot be refunded');
        }

        // Check for existing refund request
        $existingRefund = Refund::where('order_id', $orderId)
            ->whereIn('refund_status', ['pending', 'approved', 'processed'])
            ->first();

        if ($existingRefund) {
            return back()->with('error', 'A refund request already exists for this order');
        }

        $refundReasons = RefundReason::active()->forCustomer()->get();

        return view('user-views.refund.create', compact('order', 'refundReasons'));
    }

    // Store refund request
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'refund_reason' => 'required|string|max:255',
            'refund_type' => 'required|in:full,partial',
            'refund_amount' => 'required_if:refund_type,partial|numeric|min:0.01',
            'customer_note' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $customer = Session::get('userInfo');
            $order = Order::where('id', $request->order_id)
                ->where('customer_id', $customer->id)
                ->first();

            if (!$order) {
                throw new \Exception('Order not found');
            }

            // Check if order can be refunded
            if (!$this->canOrderBeRefunded($order)) {
                throw new \Exception('This order cannot be refunded');
            }

            // Check for existing refund request
            $existingRefund = Refund::where('order_id', $request->order_id)
                ->whereIn('refund_status', ['pending', 'approved', 'processed'])
                ->first();

            if ($existingRefund) {
                throw new \Exception('A refund request already exists for this order');
            }

            // Calculate refund amount
            $refundAmount = $request->refund_type === 'full' 
                ? $order->order_amount 
                : $request->refund_amount;

            // Validate refund amount
            if ($refundAmount > $order->order_amount) {
                throw new \Exception('Refund amount cannot exceed order amount');
            }

            // Create refund request
            $refund = Refund::create([
                'order_id' => $request->order_id,
                'customer_id' => $customer->id,
                'refund_amount' => $refundAmount,
                'restaurant_deduction_amount' => 0, // Default to 0, admin can adjust later
                'refund_reason' => $request->refund_reason,
                'refund_status' => Refund::STATUS_PENDING,
                'refund_type' => $request->refund_type,
                'customer_note' => $request->customer_note,
                'refund_details' => [
                    'created_by' => 'customer',
                    'requested_at' => now()
                ]
            ]);

            // Update order status
            $order->update([
                'order_status' => 'refund_requested',
                'refund_requested' => now()
            ]);

            DB::commit();

            // Send notification to admin (if needed)
            $this->notifyAdminOfRefundRequest($refund);

            return redirect()->route('user.refund.index')
                ->with('success', 'Refund request submitted successfully. We will process it within 24-48 hours.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // Show refund details
    public function show($id)
    {
        $customer = Session::get('userInfo');
        if (!$customer) {
            return redirect()->route('user.auth.login');
        }

        $refund = Refund::where('id', $id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        return view('user-views.refund.show', compact('refund'));
    }

    // Cancel refund request (only if pending)
    public function cancel($id)
    {
        try {
            $customer = Session::get('userInfo');
            
            $refund = Refund::where('id', $id)
                ->where('customer_id', $customer->id)
                ->where('refund_status', Refund::STATUS_PENDING)
                ->firstOrFail();

            DB::beginTransaction();

            // Update refund status
            $refund->update([
                'refund_status' => 'canceled_by_customer',
                'customer_note' => 'Canceled by customer'
            ]);

            // Update order status back to delivered or original status
            $originalStatus = 'delivered'; // or get from order history
            $refund->order->update([
                'order_status' => $originalStatus
            ]);

            DB::commit();

            return back()->with('success', 'Refund request canceled successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // Check if order can be refunded
    private function canOrderBeRefunded($order)
    {
        // Check order status
        $refundableStatuses = ['delivered'];
        
        // Check if order is within refund time limit (e.g., 7 days)
        $refundTimeLimit = 7; // days
        $orderDeliveredAt = $order->delivered ?? $order->updated_at;
        $daysSinceDelivery = now()->diffInDays($orderDeliveredAt);

        return in_array($order->order_status, $refundableStatuses) 
            && $daysSinceDelivery <= $refundTimeLimit
            && $order->payment_status === 'paid';
    }

    // Notify admin of new refund request
    private function notifyAdminOfRefundRequest($refund)
    {
        // Implementation for admin notification
        // This could be email, push notification, etc.
    }

    // Get refund reasons for AJAX
    public function getReasons()
    {
        $reasons = RefundReason::active()->forCustomer()->get();
        return response()->json($reasons);
    }
}
