<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\LoyaltyPointService;
use Google\Rpc\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class LoyaltyPointController extends Controller
{
    /**
     * Display loyalty points dashboard
     */
    public function index()
    {
        $customer = Session::get('userInfo') ?? Auth::guard('customer')->user();
        
        if (!$customer) {
            return redirect()->route('user.auth.login');
        }

        // Get fresh customer data with loyalty points
        $customer = Customer::find($customer->id);
        
        // Get loyalty settings
        $loyaltySettings = LoyaltyPointService::getLoyaltySettings($customer->id);
        
        // Get loyalty point history
        $transactions = LoyaltyPointService::getLoyaltyPointHistory($customer->id);
        
        // Calculate currency value of current points
        $currencyValue = LoyaltyPointService::calculateCurrencyValue($customer->loyalty_points);
        
        return view('user-views.loyalty.index', compact(
            'customer', 
            'loyaltySettings', 
            'transactions', 
            'currencyValue'
        ));
    }

    /**
     * Redeem loyalty points to wallet
     */
    public function redeemPoints(Request $request)
    {
        $request->validate([
            'points' => 'required|numeric|min:1'
        ]);

        $customer = Session::get('userInfo') ?? Auth::guard('customer')->user();
        
        if (!$customer) {
            return redirect()->route('user.auth.login');
        }

        $pointsToRedeem = (float) $request->points;
        
        // Redeem points
        $result = LoyaltyPointService::redeemPointsToWallet($customer->id, $pointsToRedeem);
        
        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }

    /**
     * Get loyalty point history via AJAX
     */
    public function getHistory(Request $request)
    {
        $customer = Session::get('userInfo') ?? Auth::guard('customer')->user();
        
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $transactions = LoyaltyPointService::getLoyaltyPointHistory($customer->id, 10);
        
        return response()->json([
            'transactions' => $transactions->items(),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total()
            ]
        ]);
    }

    /**
     * Calculate points for a given amount (AJAX)
     */
    public function calculatePoints(Request $request)
    {
        $amount = (float) $request->get('amount', 0);
        $customer = auth()->guard('customer')->user();
        $points = LoyaltyPointService::calculatePointsForOrder($amount, $customer->id);


        return response()->json([
            'points' => $points,
            'currency_value' => LoyaltyPointService::calculateCurrencyValue($points)
        ]);
    }
}
