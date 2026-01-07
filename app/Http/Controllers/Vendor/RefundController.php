<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use App\Models\RefundReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RefundController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:vendor');
    }

    // List refunds for the restaurant
    public function index(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        $status = $request->get('status', 'all');
        $search = $request->get('search');
        $restaurant = Session::get('restaurant');

       

        $refunds = Refund::with(['order'])
            ->whereHas('order', function($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })
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


        $stats = [
            'total' => Refund::whereHas('order', function($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id)
                ->where('refund_status',  'processed');
            })->count(),
        ];

        return view('vendor-views.refund.index', compact('refunds', 'status', 'stats'));
    }

    // Show refund details
    public function show($id)
    {
        $vendor = Auth::guard('vendor')->user();
        $restaurant = Session::get('restaurant');
        
        $refund = Refund::whereHas('order', function($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })->findOrFail($id);

        return view('vendor-views.refund.show', compact('refund'));
    }

    // Add comment to refund (vendor response)
    public function addComment(Request $request, $id)
    {
        $vendor = Auth::guard('vendor')->user();
        
        $refund = Refund::whereHas('order', function($query) use ($vendor) {
            $query->where('restaurant_id', $vendor->restaurant_id);
        })->findOrFail($id);

        $request->validate([
            'vendor_note' => 'required|string|max:1000'
        ]);

        // Add vendor note to refund details
        $refundDetails = $refund->refund_details ?? [];
        $refundDetails['vendor_notes'] = $refundDetails['vendor_notes'] ?? [];
        $refundDetails['vendor_notes'][] = [
            'note' => $request->vendor_note,
            'added_at' => now()->toISOString(),
            'added_by' => $vendor->f_name . ' ' . $vendor->l_name
        ];

        $refund->update([
            'refund_details' => $refundDetails
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully'
        ]);
    }

    // Get refund statistics for dashboard
    public function getStats()
    {
        $vendor = Auth::guard('vendor')->user();
        
        $stats = [
            'total_refunds' => Refund::whereHas('order', function($query) use ($vendor) {
                $query->where('restaurant_id', $vendor->restaurant_id);
            })->count(),
            
            'pending_refunds' => Refund::whereHas('order', function($query) use ($vendor) {
                $query->where('restaurant_id', $vendor->restaurant_id);
            })->where('refund_status', 'pending')->count(),
            
            'total_refund_amount' => Refund::whereHas('order', function($query) use ($vendor) {
                $query->where('restaurant_id', $vendor->restaurant_id);
            })->where('refund_status', 'processed')->sum('refund_amount'),
            
            'total_deductions' => Refund::whereHas('order', function($query) use ($vendor) {
                $query->where('restaurant_id', $vendor->restaurant_id);
            })->where('refund_status', 'processed')->sum('restaurant_deduction_amount'),
            
            'recent_refunds' => Refund::with(['order', 'customer'])
                ->whereHas('order', function($query) use ($vendor) {
                    $query->where('restaurant_id', $vendor->restaurant_id);
                })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        ];

        return response()->json($stats);
    }
}
