<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralRewardConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReferralController extends Controller
{
    public function index()
    {
        $configurations = ReferralRewardConfiguration::orderBy('order_limit')
            ->get();
            
        return view('admin-views.referral.index', compact('configurations'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'configurations' => 'required|array',
            'configurations.*.order_limit' => 'required|integer|min:1',
            'configurations.*.user_reward_type' => 'required|in:cashback,discount',
            'configurations.*.sponsor_reward_type' => 'required|in:cashback,discount',
            'configurations.*.user_discount_type' => 'nullable|required_if:configurations.*.user_reward_type,discount|in:flat,percentage',
            'configurations.*.sponsor_discount_type' => 'nullable|required_if:configurations.*.sponsor_reward_type,discount|in:flat,percentage',
            'configurations.*.user_reward_value' => 'required|numeric|min:0',
            'configurations.*.sponsor_reward_value' => 'required|numeric|min:0',
            'configurations.*.max_amount' => 'nullable|numeric|min:0',
        ], [
            'configurations.*.order_limit.required' => 'Order limit is required for all configurations',
            'configurations.*.user_reward_type.required' => 'User reward type is required',
            'configurations.*.sponsor_reward_type.required' => 'Sponsor reward type is required',
            'configurations.*.user_discount_type.required_if' => 'User discount type is required when user reward type is discount',
            'configurations.*.sponsor_discount_type.required_if' => 'Sponsor discount type is required when sponsor reward type is discount',
            'configurations.*.user_reward_value.required' => 'User reward value is required',
            'configurations.*.sponsor_reward_value.required' => 'Sponsor reward value is required',
            'configurations.*.user_reward_value.min' => 'User reward value must be greater than 0',
            'configurations.*.sponsor_reward_value.min' => 'Sponsor reward value must be greater than 0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            ReferralRewardConfiguration::truncate();
            

                        // Create new configurations
            foreach ($request->configurations as $config) {
                ReferralRewardConfiguration::create([
                    'order_limit' => $config['order_limit'],
                    'user_reward_type' => $config['user_reward_type'],
                    'sponsor_reward_type' => $config['sponsor_reward_type'],
                    'user_discount_type' => $config['user_discount_type'] ?? null,
                    'user_reward_value' => $config['user_reward_value'],
                    'sponsor_reward_value' => $config['sponsor_reward_value'],
                    'max_amount' => $config['max_amount'] ?? null,
                    'is_active' => true
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Referral configurations saved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save configurations: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getConfigurations()
    {
        $configurations = ReferralRewardConfiguration::orderBy('user_type')
            ->orderBy('order_count')
            ->get();

        return response()->json([
            'success' => true,
            'configurations' => $configurations
        ]);
    }

    public function toggleStatus(Request $request, $id)
    {
        try {
            $configuration = ReferralRewardConfiguration::findOrFail($id);
            $configuration->update(['is_active' => !$configuration->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'is_active' => $configuration->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $configuration = ReferralRewardConfiguration::findOrFail($id);
            $configuration->delete();

            return response()->json([
                'success' => true,
                'message' => 'Configuration deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete configuration'
            ], 500);
        }
    }

    public function statistics()
    {
        $stats = [
            'total_configurations' => ReferralRewardConfiguration::count(),
            'active_configurations' => ReferralRewardConfiguration::where('is_active', true)->count(),
            'sponsor_rewards' => ReferralRewardConfiguration::where('user_type', 'sponsor')->count(),
            'beneficiary_rewards' => ReferralRewardConfiguration::where('user_type', 'beneficiary')->count(),
            'cashback_rewards' => ReferralRewardConfiguration::where('reward_type', 'cashback')->count(),
            'discount_rewards' => ReferralRewardConfiguration::where('reward_type', 'discount')->count(),
            'total_referral_codes' => \App\Models\Referral::count(),
            'total_referral_uses' => \App\Models\ReferralUse::count(),
            'active_referral_codes' => \App\Models\Referral::where('is_active', true)->count(),
            'used_referral_codes' => \App\Models\Referral::where('total_uses', '>', 0)->count(),
        ];

        return response()->json([
            'success' => true,
            'statistics' => $stats
        ]);
    }

    public function usageStatistics()
    {
        return view('admin-views.referral.usage-statistics');
    }

    public function usageDetails()
    {
        $referralCodes = \App\Models\Referral::with(['sponsor', 'uses.beneficiary'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'referral_codes' => $referralCodes
        ]);
    }
}
