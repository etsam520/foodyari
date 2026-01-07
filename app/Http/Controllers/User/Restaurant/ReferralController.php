<?php

namespace App\Http\Controllers\User\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReferralController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    /**
     * Get user's referral dashboard
     */
    public function index()
    {
        $user = Auth::guard('customer')->user();
        
        if (!$user->referral_code) {
            $this->referralService->createReferralCode($user->id);
            $user = Customer::find($user->id);
        }

        $stats = $this->referralService->getReferralStats($user->id);
        
        return view('user-views.referral.index', compact('stats'));
    }

    /**
     * Generate referral code for user
     */
    public function generateCode()
    {
        $user = Auth::guard('customer')->user();
        
        $result = $this->referralService->createReferralCode($user->id);
        
        return response()->json($result);
    }

    /**
     * Get referral statistics
     */
    public function getStats()
    {
        $user = Auth::guard('customer')->user();
        $stats = $this->referralService->getReferralStats($user->id);
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Validate referral code
     */
    public function validateCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'referral_code' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid referral code format'
            ]);
        }

        $user = Auth::guard('customer')->user();
        $result = $this->referralService->validateReferralCode(
            $request->referral_code, 
            $user ? $user->id : null
        );

        return response()->json($result);
    }

    /**
     * Apply referral code during registration
     */
    public function applyReferralCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'referral_code' => 'required|string|max:20',
            'user_id' => 'required|exists:customers,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Customer::find($request->user_id);
        
        // Check if user already used a referral code
        if ($user->referred_by) {
            return response()->json([
                'success' => false,
                'message' => 'You have already used a referral code'
            ]);
        }

        $result = $this->referralService->processReferralRegistration(
            $user, 
            $request->referral_code
        );

        return response()->json($result);
    }

    /**
     * Get user's rewards (both as beneficiary and sponsor)
     */
    public function getRewards()
    {
        $user = Auth::guard('customer')->user();
        
        // Get rewards as beneficiary (user rewards)
        $userRewards = $user->referralRewards()
            ->with(['referral.sponsor', 'sponsor'])
            ->orderBy('order_limit')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($reward) {
                return [
                    'id' => $reward->id,
                    'type' => 'user',
                    'order_limit' => $reward->order_limit,
                    'reward_type' => $reward->user_reward_type,
                    'discount_type' => $reward->user_discount_type,
                    'reward_value' => $reward->user_reward_value,
                    'max_amount' => $reward->max_amount,
                    'current_orders' => $reward->user_current_orders,
                    'is_unlocked' => $reward->is_unlocked,
                    'is_claimed' => $reward->is_user_claimed,
                    'unlocked_at' => $reward->unlocked_at,
                    'claimed_at' => $reward->user_claimed_at,
                    'sponsor' => $reward->sponsor,
                    'created_at' => $reward->created_at
                ];
            });

        // Get rewards as sponsor (sponsor rewards)
        $sponsorRewards = $user->sponsorRewards()
            ->with(['referral', 'user'])
            ->orderBy('order_limit')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($reward) {
                return [
                    'id' => $reward->id,
                    'type' => 'sponsor',
                    'order_limit' => $reward->order_limit,
                    'reward_type' => $reward->sponsor_reward_type,
                    'discount_type' => $reward->sponsor_discount_type,
                    'reward_value' => $reward->sponsor_reward_value,
                    'max_amount' => $reward->max_amount,
                    'current_orders' => $reward->user_current_orders,
                    'is_unlocked' => $reward->is_unlocked,
                    'is_claimed' => $reward->is_sponsor_claimed,
                    'unlocked_at' => $reward->unlocked_at,
                    'claimed_at' => $reward->sponsor_claimed_at,
                    'beneficiary' => $reward->user,
                    'created_at' => $reward->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'user_rewards' => $userRewards,
            'sponsor_rewards' => $sponsorRewards
        ]);
    }

    public function getClaimedRewards()
    {
        $user = Auth::guard('customer')->user();
        $referralProvider = new \App\Http\Controllers\User\Restaurant\apparatusReferral\ReferralProvider($user->id);
        $claimedRewards = $referralProvider->getRewards();

        return response()->json([
            'success' => true,
            'claimed_rewards' => $claimedRewards
        ]);
    }

    /**
     * Claim a user reward (as beneficiary)
     */
    public function claimUserReward(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reward_id' => 'required|exists:referral_user_rewards,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reward ID'
            ], 422);
        }

        $user = Auth::guard('customer')->user();
        
        $result = $this->referralService->claimUserReward(
            $user->id, 
            $request->reward_id
        );

        return response()->json($result);
    }

    /**
     * Claim a sponsor reward (as sponsor)
     */
    public function claimSponsorReward(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reward_id' => 'required|exists:referral_user_rewards,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reward ID'
            ], 422);
        }

        $user = Auth::guard('customer')->user();
        
        $result = $this->referralService->claimSponsorReward(
            $user->id, 
            $request->reward_id
        );

        return response()->json($result);
    }

    /**
     * Get referral code share link/info
     */
    public function getShareInfo()
    {
        $user = Auth::guard('customer')->user();
        
        if (!$user->referral_code) {
            $this->referralService->createReferralCode($user->id);
            $user = Customer::find($user->id);
        }

        $shareData = [
            'referral_code' => $user->referral_code,
            'share_url' => url('/') . '?ref=' . $user->referral_code,
            'share_text' => "Join " . env('APP_NAME') . " using my referral code {$user->referral_code} and get exclusive rewards!",
        ];

        return response()->json([
            'success' => true,
            'share_data' => $shareData
        ]);
    }

    /**
     * Get referral history
     */
    public function getHistory()
    {
        $user = Auth::guard('customer')->user();
        
        // Get all referral codes created by this user
        $referralCodes = $user->sponsoredReferrals()
            ->with(['uses.beneficiary'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Format the data to show all uses
        $referralHistory = [];
        
        foreach ($referralCodes as $referral) {
            foreach ($referral->uses as $use) {
                $referralHistory[] = [
                    'referral_code' => $referral->referral_code,
                    'beneficiary' => $use->beneficiary,
                    'used_at' => $use->used_at,
                    'total_code_uses' => $referral->total_uses,
                    'created_at' => $referral->created_at
                ];
            }
        }

        // Sort by usage date
        usort($referralHistory, function($a, $b) {
            return $b['used_at'] <=> $a['used_at'];
        });

        return response()->json([
            'success' => true,
            'referral_history' => $referralHistory,
            'referral_codes' => $referralCodes
        ]);
    }

    /**
     * Referral landing page for shared referral links
     */
    public function landing(Request $request, $referralCode = null)
    {
        // Get referral code from parameter or query string
        $referralCode = $referralCode ?? $request->get('ref');
        
        if (!$referralCode) {
            return redirect()->route('userHome');
        }
        
        // Validate referral code and get sponsor info
        $validationResult = $this->referralService->validateReferralCode($referralCode);
        if (!$validationResult['valid']) {
            return redirect()->route('userHome')->with('error', 'Invalid referral code');
        }

        $sponsor = $validationResult['sponsor'] ?? null;
        
        // Get available benefits for beneficiaries
        $benefits = \App\Models\ReferralRewardConfiguration::active()
            ->orderBy('order_limit')
            ->get();
        
        return view('user-views.referral.landing', compact('referralCode', 'sponsor', 'benefits'));
    }
}
