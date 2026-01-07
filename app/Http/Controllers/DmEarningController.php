<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Models\AdminFund;
use App\Models\DeliveryMan;
use App\Models\DeliveryManCashInHand;
use App\Models\DeliveryManPayout;
use App\Models\DiscountCoupon;
use App\Models\Wallet;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DmEarningController extends Controller
{
    //
    public function index(Request $request){
        $key = explode(' ', $request['search']);
        $coupons = DiscountCoupon::where('created_by','admin')
        ->when(isset($key), function($query)use($key){
            $query->where( function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%")
                    ->orWhere('code', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));
        return view('admin-views.dmEarning.index', compact('coupons'));
    }

    public function getDmCashInHand(Request $request)
    {
        $dmId = $request->query('dm_id');

        $deliveryMan = DeliveryMan::find($dmId);
        // $cashInHand = DeliveryManCashInHand::with('cashTxns')->where('deliveryman_id', $deliveryMan->id)->first();
        $cashInHand = DeliveryManCashInHand::where('deliveryman_id', $deliveryMan->id)->first();
        if(!isset($cashInHand->balance)){
            $cashInHand = DeliveryManCashInHand::create([
                'deliveryman_id' => $deliveryMan->id,
                'balance' => 0,
            ]);
        }
        return response()->json(['amount'=> $cashInHand->balance]);
        // dd($cashInHand);
    }
    public function getDmWalletBalance(Request $request)
    {
        $dmId = $request->query('dm_id');

        $deliveryMan = DeliveryMan::find($dmId);
        $dmWallet = Wallet::where('deliveryman_id', $deliveryMan->id)->first();
        if(!isset($dmWallet->balance)){
            $dmWallet = Wallet::create([
                'deliveryman_id' => $deliveryMan->id,
                'balance' => 0,
            ]);
        }
        return response()->json(['amount'=> $dmWallet->balance]);
        // dd($cashInHand);
    }

    public function savingCashTransaction(Request $request)
    {
        // Debugging can be done after validation (if necessary)
        $validator = Validator::make($request->all(), [
            "zone_id" => "required",
            "deliveryman_id" => "required|exists:delivery_men,id", // Ensures deliveryman exists
            "dm_cash_in_hand" => "required|numeric|min:1", // Ensures positive amount
            "payment_method" => "required",
            "notes" => "nullable|string", // Notes can be optional but should be a string
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $amount = $request->dm_cash_in_hand;

        $deliveryMan = DeliveryMan::findOrFail($request->deliveryman_id); // Use findOrFail to throw error if not found

        // Fetch the current cash-in-hand record for the deliveryman
        $cashInhand = DeliveryManCashInHand::firstOrCreate([
            'deliveryman_id' => $deliveryMan->id
        ]);
        $adminFund = AdminFund::getFund();

        $ADMIN = Helpers::getAdmin();

        try {
            DB::beginTransaction();

            if($cashInhand->balance < $amount){
                throw new \Exception('Amount Excessing the Limit');
            }

            if ($amount > 0) {
                // Record the cash transaction for the deliveryman
                $cashInhand->cashTxns()->create([
                    'amount' => $amount,
                    'txn_type' => 'paid',
                    'received_from' => 'deliveryman',
                    'paid_to' => 'admin',
                    'payment_method' => $request->payment_method,
                    'admin_fund_id' => $adminFund->id,
                    'remarks' => "Paid To Admin"
                ]);
                $cashInhand->balance -= $amount;
                $cashInhand->save();

                // Update the admin fund's balance and create an admin transaction
                $adminFund->balance += $amount;
                $adminFund->txns()->create([
                    'amount' => $amount,
                    'txn_type' => 'received',
                    'received_from' => 'deliveryman',
                    'paid_to' => null,
                    'payment_method' => $request->payment_method,
                    'deliveryman_id' => $deliveryMan->id,
                    'remarks' => $request->notes
                ]);
                $adminFund->save(); // Save the updated balance

                // Record payout
                DeliveryManPayout::create([
                    'delivery_man_id' => $deliveryMan->id,
                    'amount' => $amount,
                    'method' => $request->payment_method,
                    'payout_type' => 'cash_collection',
                    'notes' => $request->notes,
                    'status' => 'completed',
                    'admin_id' => $ADMIN->id,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Transaction saved successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function savingWalletTransaction(Request $request)
    {
        // Validate the incoming request
        try {
            $request->validate([
                "deliveryman_id" => "required|exists:delivery_men,id", // Ensure deliveryman exists
                "amount" => "required|numeric|min:1", // Ensure positive amount
                "payment_method" => "required",
                "notes" => "nullable|string", // Notes can be optional but should be a string
            ]);

            $amount = $request->amount;

            $deliveryMan = DeliveryMan::findOrFail($request->deliveryman_id); // Use findOrFail to throw error if not found

            // Find wallet by deliveryman_id instead of the deliveryman ID directly
            $dmWallet = Wallet::where('deliveryman_id', $deliveryMan->id)->firstOrFail();

            $ADMIN = Helpers::getAdmin();

            DB::beginTransaction();

            // Check if the wallet balance is sufficient
            if ($dmWallet->balance < $amount) {
                throw new \Exception('Amount exceeds the available balance');
            }

            if ($amount > 0) {
                // Record the cash transaction for the deliveryman
                $adminFund_deduct = AdminFund::getFund(); // Assuming same fund is used

                $adminFund_deduct->cashTxns()->create([
                    'amount' => $amount,
                    'txn_type' => 'paid',
                    'received_from' => 'admin',
                    'paid_to' => 'deliveryman',
                    'payment_method' => $request->payment_method,
                    'remarks' => "Wallet Amount Paid to $deliveryMan->f_name $deliveryMan->l_name ($deliveryMan->phone) in Cash",
                ]);
                $adminFund_deduct->txns()->create([
                    'amount' => $amount,
                    'txn_type' => 'paid',
                    'received_from' => null,
                    'paid_to' => 'deliveryman',
                    'payment_method' => $request->payment_method,
                    'deliveryman_id' => $deliveryMan->id,
                    'remarks' => "Wallet Amount Paid to $deliveryMan->f_name $deliveryMan->l_name ($deliveryMan->phone) in Cash"
                ]);
                $adminFund_deduct->balance -= $amount;
                $adminFund_deduct->save();

                // Deduct the amount from the deliveryman's wallet
                $dmWallet->balance -= $amount;
                $dmWallet->walletTransactions()->create([
                    'amount' => $amount,
                    'admin_id' => $ADMIN->id,
                    'type' => 'paid',
                    'payment_method' => 'wallet',
                    'remarks' => 'Clearing Wallet Amount By Admin',
                ]);
                $dmWallet->save();
                $adminFund_add = AdminFund::getFund();
                // Add the same amount to the admin fund
                $adminFund_add->balance += $amount;
                $adminFund_add->txns()->create([
                    'amount' => $amount,
                    'txn_type' => 'received',
                    'received_from' => 'deliveryman',
                    'paid_to' => null,
                    'payment_method' => 'wallet',
                    'deliveryman_id' => $deliveryMan->id,
                    'remarks' => $request->notes
                ]);
                $adminFund_add->save(); // Save the updated balance

                // Record payout
                DeliveryManPayout::create([
                    'delivery_man_id' => $deliveryMan->id,
                    'amount' => $amount,
                    'method' => $request->payment_method,
                    'payout_type' => 'wallet_payout',
                    'notes' => $request->notes,
                    'status' => 'completed',
                    'admin_id' => $ADMIN->id,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Transaction saved successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the payout list page
     */
    public function payouts(Request $request)
    {
        $key = explode(' ', $request['search']);
        
        $payouts = DeliveryManPayout::with(['deliveryMan.zone', 'admin', 'updatedBy'])
            ->when(isset($key) && !empty($request['search']), function($query) use($key) {
                $query->whereHas('deliveryMan', function($q) use($key) {
                    foreach ($key as $value) {
                        $q->orWhere('f_name', 'like', "%{$value}%")
                          ->orWhere('l_name', 'like', "%{$value}%")
                          ->orWhere('phone', 'like', "%{$value}%");
                    }
                });
            })
            ->when($request->has('status') && $request->status != 'all', function($query) use($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('payout_type') && $request->payout_type != 'all', function($query) use($request) {
                $query->where('payout_type', $request->payout_type);
            })
            ->when($request->has('zone_id') && $request->zone_id != 'all', function($query) use($request) {
                $query->whereHas('deliveryMan', function($q) use($request) {
                    $q->where('zone_id', $request->zone_id);
                });
            })
            ->latest()
            ->paginate(config('default_pagination'));

        $zones = Zone::isActive()->get();
        
        // Calculate summary statistics
        $totalPayouts = DeliveryManPayout::count();
        $totalAmount = DeliveryManPayout::sum('amount');
        $completedPayouts = DeliveryManPayout::where('status', 'completed')->count();
        $pendingPayouts = DeliveryManPayout::where('status', 'pending')->count();
        $failedPayouts = DeliveryManPayout::where('status', 'failed')->count();
        
        // Recent payouts by different admins
        $recentAdmins = DeliveryManPayout::with('admin')
            ->whereNotNull('admin_id')
            ->groupBy('admin_id')
            ->selectRaw('admin_id, COUNT(*) as payout_count, SUM(amount) as total_amount, MAX(created_at) as last_payout')
            ->orderBy('last_payout', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin-views.dmEarning.payouts', compact(
            'payouts', 
            'zones', 
            'totalPayouts', 
            'totalAmount', 
            'completedPayouts', 
            'pendingPayouts', 
            'failedPayouts',
            'recentAdmins'
        ));
    }

    /**
     * Create a new payout record
     */
    public function createPayout(Request $request)
    {
        $request->validate([
            'delivery_man_id' => 'required|exists:delivery_men,id',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:cash,upi,bank_transfer',
            'payout_type' => 'required|in:cash_collection,wallet_payout',
            'notes' => 'nullable|string|max:500',
            'reference_no' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            $admin = Helpers::getAdmin();
            
            // Create payout record
            $payout = DeliveryManPayout::create([
                'delivery_man_id' => $request->delivery_man_id,
                'amount' => $request->amount,
                'method' => $request->method,
                'payout_type' => $request->payout_type,
                'notes' => $request->notes,
                'reference_no' => $request->reference_no,
                'status' => 'completed',
                'admin_id' => $admin->id,
            ]);

            // Handle the financial transaction based on payout type
            if ($request->payout_type === 'cash_collection') {
                $this->handleCashCollectionPayout($request, $payout);
            } else {
                $this->handleWalletPayout($request, $payout);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payout created successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle cash collection payout
     */
    private function handleCashCollectionPayout($request, $payout)
    {
        $deliveryMan = DeliveryMan::findOrFail($request->delivery_man_id);
        $cashInHand = DeliveryManCashInHand::firstOrCreate([
            'deliveryman_id' => $deliveryMan->id
        ]);

        if ($cashInHand->balance < $request->amount) {
            throw new \Exception('Insufficient cash in hand balance');
        }

        // Deduct from delivery man's cash in hand
        $cashInHand->balance -= $request->amount;
        $cashInHand->save();

        // Record cash transaction
        $cashInHand->cashTxns()->create([
            'amount' => $request->amount,
            'txn_type' => 'paid',
            'received_from' => 'deliveryman',
            'paid_to' => 'admin',
            'payment_method' => $request->method,
            'remarks' => "Payout: {$request->notes}"
        ]);
    }

    /**
     * Handle wallet payout
     */
    private function handleWalletPayout($request, $payout)
    {
        $deliveryMan = DeliveryMan::findOrFail($request->delivery_man_id);
        $wallet = Wallet::where('deliveryman_id', $deliveryMan->id)->firstOrFail();

        if ($wallet->balance < $request->amount) {
            throw new \Exception('Insufficient wallet balance');
        }

        $admin = Helpers::getAdmin();

        // Deduct from delivery man's wallet
        $wallet->balance -= $request->amount;
        $wallet->save();

        // Record wallet transaction
        $wallet->walletTransactions()->create([
            'amount' => $request->amount,
            'admin_id' => $admin->id,
            'type' => 'payout',
            'payment_method' => $request->method,
            'remarks' => "Payout: {$request->notes}",
        ]);
    }

    /**
     * Update payout status
     */
    public function updatePayoutStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed'
        ]);

        $payout = DeliveryManPayout::findOrFail($id);
        $admin = Helpers::getAdmin();
        
        $payout->update([
            'status' => $request->status,
            'updated_by' => $admin->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payout status updated successfully'
        ]);
    }

    /**
     * Get payouts created by a specific admin
     */
    public function payoutsByAdmin(Request $request, $adminId)
    {
        $key = explode(' ', $request['search']);
        
        $payouts = DeliveryManPayout::with(['deliveryMan.zone', 'admin', 'updatedBy'])
            ->where('admin_id', $adminId)
            ->when(isset($key) && !empty($request['search']), function($query) use($key) {
                $query->whereHas('deliveryMan', function($q) use($key) {
                    foreach ($key as $value) {
                        $q->orWhere('f_name', 'like', "%{$value}%")
                          ->orWhere('l_name', 'like', "%{$value}%")
                          ->orWhere('phone', 'like', "%{$value}%");
                    }
                });
            })
            ->when($request->has('status') && $request->status != 'all', function($query) use($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('payout_type') && $request->payout_type != 'all', function($query) use($request) {
                $query->where('payout_type', $request->payout_type);
            })
            ->when($request->has('zone_id') && $request->zone_id != 'all', function($query) use($request) {
                $query->whereHas('deliveryMan', function($q) use($request) {
                    $q->where('zone_id', $request->zone_id);
                });
            })
            ->latest()
            ->paginate(config('default_pagination'));

        $zones = Zone::isActive()->get();
        $admin = \App\Models\Admin::findOrFail($adminId);
        
        return view('admin-views.dmEarning.payouts-by-admin', compact('payouts', 'zones', 'admin'));
    }
}
