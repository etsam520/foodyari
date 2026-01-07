<?php

namespace App\Http\Controllers\Admin;

use Error;
use Carbon\Carbon;
use App\Models\Zone;
use App\Models\Order;
use App\Models\DMReview;
use App\Models\Document;
use App\Models\UserInfo;
use App\Models\AdminFund;
use App\Models\DeliveryMan;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\DeliverymanKyc;
use App\Models\FuelTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

class DeliveryManController extends Controller
{
    public function index()
    {
        return view('admin-views.delivery-man.index');
    }

    public function list(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $zone_id = $request->query('zone_id', 'all');
        $now = now();
        $today = now()->format('Y-m-d');
        // dd($today);
        $delivery_men = DeliveryMan::with([
            'zone:id,name',
            'attendances' => function ($q) {
                $q->whereDate('created_at', now()->format('Y-m-d'))
                  ->select('deliveryman_id', 'is_online');
            }
        ])
        ->addSelect([
            'live_orders' => DB::table('orders')
                ->selectRaw('COUNT(orders.id)')
                ->whereNull('orders.delivered')
                ->whereNull('orders.canceled')
                ->whereColumn('orders.delivery_man_id', 'delivery_men.id')
                ->whereDate('orders.created_at', $now->toDateString())
        ])
        ->when(is_numeric($zone_id), function ($query) use ($zone_id) {
            return $query->where('zone_id', $zone_id);
        })
        ->whereHas('admin', function ($query) use ($admin) {
            $query->where('id', $admin->id);
        })
        ->latest()
        ->get();
        return view('admin-views.delivery-man.list', compact('delivery_men'));
    }

     public function history($id)
    {
        $dm = DeliveryMan::findOrFail($id);
        $orders = Order::where('delivery_man_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

            // return $orders;
        return view('admin-views.delivery-man.deliveryman-history', compact('dm', 'orders'));
    }

    public function show(Request $request, $id){
        $filter = $request->query('filter', 'this_month');
        $now = Carbon::now() ;
        if($filter == 'custom'){
            $dateRange = $request->date_range;
            if($dateRange == null){
                return back()->with('info', "Date range can\'t be null");
            }
            $dates = explode(" to ", $dateRange);

            $from = $dates[0]??null;
            $to = $dates[1]??null;
            $startDate = $from;
            $endDate = $to;
        }else{
            if ($filter) {
                try {
                    $startDate = Carbon::createFromFormat('Y-m', $filter)->startOfMonth();
                    $endDate = Carbon::createFromFormat('Y-m', $filter)->endOfMonth();
                } catch (\Exception $e) {
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                }
            } else {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }
        }

        $dm = DeliveryMan::addSelect([
            'is_online' => DB::table('deliveryman_attendances')
            ->selectRaw('COUNT(deliveryman_attendances.id)')
            ->whereDate('created_at', $now->toDateString())
            ->where('is_online', 1)
        ])
        ->addSelect([
            'live_orders' => DB::table('orders')
                ->selectRaw('COUNT(orders.id)')
                ->whereNull('orders.delivered')
                ->whereNull('orders.canceled')
                ->whereColumn('orders.delivery_man_id', 'delivery_men.id')
                ->whereDate('orders.created_at', $now->toDateString())
        ])
        ->addSelect([
            'delivered_orders' => DB::table('orders')
                ->selectRaw('COUNT(orders.id)')
                ->whereNotNull('orders.delivered')
                ->whereColumn('orders.delivery_man_id', 'delivery_men.id')
        ])

        ->find($id)->toArray();
        // dd($dm);




        $att_data = $this->getdmAttendances($dm, $startDate, $endDate);
        $fuel_data = $this->getFuelData($dm, $startDate, $endDate);
        $working_data = $this->getWorkingDays($dm, $startDate, $endDate);
        // dd($working_data);
        $dm['attendences'] = $att_data['list'];
        $dm['total_distance'] = $att_data['total_distance'];
        $dm['working_days'] = $working_data['total_working_days'];
        $dm['half_days'] = $working_data['half_days'] ;
        $dm['full_days'] = $working_data['full_days'];
        $dm['leave_days'] = $working_data['leave_days'];
        $dm['fuel_transactions'] = $fuel_data['formattedData'];
        $dm['fuel_balance'] = $fuel_data['balance'];
        $dm['cash_histories'] = $this->cash_histories($dm, $startDate, $endDate);
        $dm['wallet_histories'] = $this->wallet_histories($dm, $startDate, $endDate);


            // dd($dm);
        return view('admin-views.delivery-man.details',compact('dm','now'));
    }

    public function wallet_histories($dm, $startDate, $endDate)
    {

        $mywallet = \App\Models\Wallet::where('deliveryman_id', $dm['id'])
            ->with(['WalletTransactions' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }])
            ->first();

        if (!$mywallet) {
            return [
                'balance' => 0,
                'formattedData' => 0
            ];
        }


        $walletTransactions = $mywallet->WalletTransactions()
        ->whereBetween('created_at', [$startDate, $endDate])->latest()->get();

        // Group transactions by date
        $groupedTransactions = $walletTransactions->groupBy(function ($txn) {
            return Carbon::parse($txn->created_at)->format('Y-m-d');
        });

        // Process transactions: add if "received", subtract if "paid"
        $formattedData = $groupedTransactions->map(function ($transactions, $date) {
            $total = 0;

            foreach ($transactions as $txn) {
                if ($txn->type === 'received') {
                    $total += $txn->amount; // Add amount
                } else {
                    $total -= $txn->amount; // Deduct amount
                }
            }

            return [
                'date' => $date,
                'total' => $total,
                'transactions' => $transactions
            ];
        });

        return [
            'balance' => $mywallet->balance,
            'formattedData' => $formattedData
        ];
        // dd($formattedData->toArray());

        return view('deliveryman.mywallet.history', compact('mywallet', 'formattedData', 'filterType'));
    }
    private function cash_histories($dm , $startDate, $endDate)
    {
        $cashInHand = \App\Models\DeliveryManCashInHand::where('deliveryman_id', $dm['id'])->first();

        if (!$cashInHand) {
            return [
                'cashInHand' => 0,
                'formattedDataTxns' => [],
                'fomattedSettlementTxns' => []
            ];
        }

        $cashTxns = $cashInHand->cashTxns()
        ->whereBetween('created_at', [$startDate, $endDate])->latest()->get();

        // Group transactions by date
        $groupedTransactions = $cashTxns->groupBy(function ($txn) {
            return Carbon::parse($txn->created_at)->format('Y-m-d');
        });

        // Process transactions: add if "received", subtract if "paid"
        $formattedDataTxns = $groupedTransactions->map(function ($transactions, $date) {
            $total = 0;

            foreach ($transactions as $txn) {
                if ($txn->txn_type === 'received') {
                    $total += $txn->amount; // Add amount
                } elseif ($txn->txn_type === 'paid') {
                    $total -= $txn->amount; // Deduct amount
                }
            }
            return [
                'date' => $date,
                'total' => $total,
                'transactions' => $transactions
            ];
        });

        $fomattedSettlementTxns = $groupedTransactions->map(function ($transactions, $date) {
            $total = 0;

            $new_arr = array_filter($transactions->toArray(), function ($txn) use (&$total) {
                if($txn['txn_type'] === 'paid') {
                    $total -= $txn['amount']; // Deduct amount
                    return $txn['txn_type'] === 'paid';
                }
            });

            return [
                'date' => $date,
                'total' => $total,
                'transactions' => $new_arr
            ];
        });
        return [
            'cashInHand' => $cashInHand->balance,
            'formattedDataTxns' => $formattedDataTxns,
            'fomattedSettlementTxns' => $fomattedSettlementTxns
        ];
    }
    // private function getFuelData($dm, $startDate, $endDate) {

    //     $query = DB::table('fuel_transactions')->where('dm_id', $dm['id'])
    //     ->whereBetween('fuel_transactions.created_at', [$startDate, $endDate]); // Correct column name

    //     $transactions = $query->orderBy('created_at', 'desc')->get();
    //     $balance = $query->select(
    //         'dm_id',
    //         DB::raw("SUM(CASE WHEN type = 'add' THEN amount ELSE 0 END) - SUM(CASE WHEN type = 'deduct' THEN amount ELSE 0 END) AS balance")
    //     )->groupBy('dm_id')->first();
    //     $balance = $balance->balance ?? 0;

    //     $grouped = $transactions->groupBy(function($item) {
    //         return Carbon::parse($item->created_at)->startOfWeek()->format('Y-m-d');
    //     });

    //     $formattedData = $grouped->map(function ($transactions, $date) {
    //         $total = 0;
    //         foreach ($transactions as $txn) {
    //             if ($txn->type === 'add') {
    //                 $total += $txn->amount; // Add amount
    //             } else {
    //                 $total -= $txn->amount; // Deduct amount
    //             }
    //         }

    //         return [
    //             'date' => $date,
    //             'total' => $total,
    //             'transactions' => $transactions
    //         ];
    //     });
    //     return ['formattedData' => $formattedData, 'balance' => $balance];;
    // }

      private function getFuelData($dm, $startDate, $endDate) {

        // base query (no orderBy)
        $baseQuery = DB::table('fuel_transactions')
            ->where('dm_id', $dm['id'])
            ->whereBetween('fuel_transactions.created_at', [$startDate, $endDate]);

        // ordered transaction list (clone so it doesn't affect the base query)
        $transactions = (clone $baseQuery)->orderBy('created_at', 'desc')->get();

        // balance aggregate from a separate cloned query (no orderBy)
        $balanceRow = (clone $baseQuery)
            ->select(
                'dm_id',
                DB::raw("SUM(CASE WHEN type = 'add' THEN amount ELSE 0 END) - SUM(CASE WHEN type = 'deduct' THEN amount ELSE 0 END) AS balance")
            )
            ->groupBy('dm_id')
            ->first();

        $balance = $balanceRow->balance ?? 0;

        $grouped = $transactions->groupBy(function($item) {
            return Carbon::parse($item->created_at)->startOfWeek()->format('Y-m-d');
        });

        $formattedData = $grouped->map(function ($transactions, $date) {
            $total = 0;
            foreach ($transactions as $txn) {
                if ($txn->type === 'add') {
                    $total += $txn->amount;
                } else {
                    $total -= $txn->amount;
                }
            }

            return [
                'date' => $date,
                'total' => $total,
                'transactions' => $transactions
            ];
        });

        return ['formattedData' => $formattedData, 'balance' => $balance];
    }



    private function getdmAttendances($dm, $startDate, $endDate){

        $halfDayTime = Carbon::createFromTimeString(env('HALF_DAY_TIME', '12:00:00'))->toTimeString(); // e.g. '12:00:00'
        $checkOutHalfDay = Carbon::createFromTimeString(env('CHECK_OUT_HALF_DAY', '21:45:00'))->toTimeString(); // e.g. '21:45:00'

        $attendance = DB::table('deliveryman_attendances as dm_attendances')
            ->where('dm_attendances.deliveryman_id', $dm['id'])
            ->whereBetween('dm_attendances.created_at', [$startDate, $endDate])
            ->select([
                DB::raw('DATE(dm_attendances.created_at) as date'),

                // is_half_day: either check_in is after 12:00 or check_out is after 21:45
                // DB::raw("IF(TIME(dm_attendances.check_in) >= '$halfDayTime' OR TIME(dm_attendances.check_out) >= '$checkOutHalfDay', 1, 0) as is_half_day"),

                DB::raw("CASE
                            WHEN TIME(dm_attendances.check_in) >= '$halfDayTime'
                            OR TIME(dm_attendances.check_out) >= '$checkOutHalfDay'
                            OR TIMESTAMPDIFF(MINUTE, dm_attendances.check_in, dm_attendances.check_out) < 240
                            THEN 1
                            ELSE 0
                        END as is_half_day"),

                // is_full_day: both check_in before 12:00 and check_out before 21:45
                // DB::raw("IF(TIME(dm_attendances.check_in) < '$halfDayTime' AND TIME(dm_attendances.check_out) < '$checkOutHalfDay', 1, 0) as is_full_day"),
                DB::raw("CASE
                            WHEN TIME(dm_attendances.check_in) < '$halfDayTime'
                            OR TIME(dm_attendances.check_out) < '$checkOutHalfDay'
                            OR TIMESTAMPDIFF(MINUTE, dm_attendances.check_in, dm_attendances.check_out) >= 240
                            THEN 1
                            ELSE 0
                        END as is_full_day"),

                DB::raw('(dm_attendances.check_out_meter - dm_attendances.check_in_meter) as distance'),
                'check_in_meter',
                'check_out_meter',
                'check_in',
                'check_out',
                'created_at',
                'check_in_location',
                'check_out_location',
                'check_in_address',
                'check_out_address',
                'check_in_image',
                'check_out_image',
            ])
            ->orderBy('dm_attendances.created_at', 'asc')
            ->get();

        $total_distance = 0 ;
        $dm_attendances_formatted = array_map(function ($dm_attendance) use (&$total_distance) {
            $total_distance += $dm_attendance->distance;
            return [
                'date' => Carbon::parse($dm_attendance->created_at)->format('d F Y'),
                'check_in' =>  Carbon::parse($dm_attendance->check_in)->format('g:i A'),
                'check_out' =>$dm_attendance->check_out !=null? Carbon::parse($dm_attendance->check_out)->format('g:i A') : null,
                'check_in_meter' => $dm_attendance->check_in_meter,
                'check_out_meter' => $dm_attendance->check_out_meter,
                'check_in_location' =>  json_decode($dm_attendance->check_in_location, true),
                'check_out_location' => json_decode($dm_attendance->check_out_location, true),
                'check_in_address' => $dm_attendance->check_in_address,
                'check_out_address' => $dm_attendance->check_out_address,
                'check_in_image' => $dm_attendance->check_in_image != null ? asset('uploads/meters/'.$dm_attendance->check_in_image) :asset('assets/dm/images/shapes/istockphoto-1226328537-612x612.jpg'),
                'check_out_image' => $dm_attendance->check_out_image != null ? asset('uploads/meters/'.$dm_attendance->check_out_image) :asset('assets/dm/images/shapes/istockphoto-1226328537-612x612.jpg'),
                'distance' => $dm_attendance->distance,
                'status'=> $dm_attendance->is_half_day == 1 ? 'Half Day' : 'Present',

            ];
        }, $attendance->toArray());

        return ['list' => $dm_attendances_formatted,'total_distance' => $total_distance];
    }

    private function getWorkingDays($dm, $startDate, $endDate) {
        $now = Carbon::now();
        $halfDayTime = Carbon::createFromTimeString(env('HALF_DAY_TIME', '12:00:00'))->toTimeString(); // e.g. '12:00:00'
        $checkOutHalfDay = Carbon::createFromTimeString(env('CHECK_OUT_HALF_DAY', '21:45:00'))->toTimeString(); // e.g. '21:45:00'
        $workings = DB::table('deliveryman_attendances as dm_attendances')
                ->where('dm_attendances.deliveryman_id', $dm['id'])
                ->whereBetween('dm_attendances.created_at', [$startDate, $endDate])
                ->select([
                    DB::raw("SUM(
                        CASE
                            WHEN TIME(dm_attendances.check_in) >= '$halfDayTime'
                                OR TIME(dm_attendances.check_out) >= '$checkOutHalfDay'
                                OR TIMESTAMPDIFF(MINUTE, dm_attendances.check_in, dm_attendances.check_out) < 240
                            THEN 0.5
                            ELSE 1
                        END
                    ) as total_working_days"),

                    DB::raw("SUM(
                        CASE
                            WHEN TIME(dm_attendances.check_in) >= '$halfDayTime'
                                OR TIME(dm_attendances.check_out) >= '$checkOutHalfDay'
                                OR TIMESTAMPDIFF(MINUTE, dm_attendances.check_in, dm_attendances.check_out) < 240
                            THEN 1
                            ELSE 0
                        END
                    ) as half_days"),

                    DB::raw("SUM(
                        CASE
                            WHEN TIME(dm_attendances.check_in) < '$halfDayTime'
                                AND TIME(dm_attendances.check_out) < '$checkOutHalfDay'
                                AND TIMESTAMPDIFF(MINUTE, dm_attendances.check_in, dm_attendances.check_out) >= 240
                            THEN 1
                            ELSE 0
                        END
                    ) as full_days"),
                ])
                ->first();

        $leave_days =$now->greaterThanOrEqualTo($endDate)? ($endDate->diffInDays($startDate) +1) - $workings->total_working_days : 0;

        return [
            'total_working_days' => $workings->total_working_days,
            'half_days' => $workings->half_days,
            'full_days' => $workings->full_days,
            'leave_days' => $leave_days
        ];
    }
    public function update_fuel_rate(Request $request){

        $dm = DeliveryMan::find($request->dm_id);
        $dm->fuel_rate = $request->fuel_rate;
        $dm->save();
        return back();

    }

    public function add_fuel_balance(Request $request){

        $request->validate([
            'dm_id' => 'required',
            'amount' => 'required',
            'note' => 'nullable|string',
        ]);
        $dm = DeliveryMan::find($request->dm_id);
        try {
            DB::beginTransaction();
            $adminFund = AdminFund::getFund();
            $amount = $request->amount;
            $adminFund->balance -= $amount;

            $adminFund->txns()->create([
                'amount' => $amount,
                'txn_type' => 'paid',
                'paid_to' => 'deliveryman',
                'received_from' => null,
                'deliveryman_id' => $dm->id,
                'remarks' => "Fuel Top Up : ".Helpers::format_currency($amount)." to : ".ucfirst($dm->f_name). " ".ucfirst($dm->l_name)."(".$dm->phone." )",
            ]);
            $adminFund->save();
            FuelTransaction::create([
                'dm_id' => $dm->id,
                'amount' => $amount,
                'type' => 'add',
                'note' => $request->note??"Fuel Top Up",
            ]);
            DB::commit();
            return back()->with('success', 'Fuel Amount added successfully');
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

    }

    public function change_password(Request $request)
    {
        try {
            $password = $request->new_password??'';
            if(!$password){
                return back()->with('warning', 'Password is required!');
            }elseif(strlen($password) < 6){
                return back()->with('warning', 'Password must be at least 6 characters!');
            }elseif($password !== $request->confirm_password){
                return back()->with('warning', 'Password confirmation does not match!');
            }

            $deliveryMan = DeliveryMan::find($request->dm_id);
            
            if (!$deliveryMan) {
                return back()->with('error', 'Delivery man not found!');
            }

            // Update the password
            $deliveryMan->password = Hash::make(trim($password));
            $deliveryMan->save();

            // Log the password change activity
            Log::info('Password changed for delivery man', [
                'delivery_man_id' => $deliveryMan->id,
                'delivery_man_name' => $deliveryMan->f_name . ' ' . $deliveryMan->l_name,
                'changed_by_admin' => Auth::guard('admin')->user()->id,
                'changed_at' => now()
            ]);

            return back()->with('success', 'Password changed successfully!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Password change error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong! Please try again.');
        }
    }


    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $delivery_men=DeliveryMan::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%")
                    ->orWhere('l_name', 'like', "%{$value}%")
                    ->orWhere('email', 'like', "%{$value}%")
                    ->orWhere('phone', 'like', "%{$value}%")
                    ->orWhere('identity_number', 'like', "%{$value}%");
            }
        })->where('type','zone_wise')->where('application_status','approved')->get();
        return response()->json([
            'view'=>view('admin-views.delivery-man.partials._table',compact('delivery_men'))->render(),
            'count'=>$delivery_men->count()
        ]);
    }

    public function reviews_list(){
        $reviews=DMReview::with(['delivery_man','customer'])->whereHas('delivery_man',function($query){
            $query->where('type','zone_wise');
        })->latest()->paginate(config('default_pagination'));
        return view('admin-views.delivery-man.reviews-list',compact('reviews'));
    }

    public function preview(Request $request, $id, $tab='info')
    {
        $dm = DeliveryMan::with(['reviews'])->where('type','zone_wise')->where(['id' => $id])->first();
        if($tab == 'info')
        {
            $reviews=DMReview::where(['delivery_man_id'=>$id])->latest()->paginate(config('default_pagination'));
            return view('admin-views.delivery-man.view.info', compact('dm', 'reviews'));
        }
        else if($tab == 'transaction')
        {
            $date = $request->query('date');
            return view('admin-views.delivery-man.view.transaction', compact('dm', 'date'));
        }
        else if($tab == 'timelog')
        {
            $from = $request->query('from', null);
            $to = $request->query('to', null);
            $timelogs = $dm->time_logs()->when($from && $to, function($query)use($from, $to){
                $query->whereBetween('date', [$from, $to]);
            })->paginate(config('default_pagination'));
            return view('admin-views.delivery-man.view.timelog', compact('dm', 'timelogs'));
        }
        else if($tab == 'conversation')
        {
            $user = UserInfo::where(['deliveryman_id' => $id])->first();
            if($user){
                $conversations = Conversation::with(['sender', 'receiver', 'last_message'])->WhereUser($user->id)->paginate(8);
            }else{
                $conversations = [];
            }

            return view('admin-views.delivery-man.view.conversations', compact('conversations','dm'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'f_name' => 'required|max:100',
            'l_name' => 'nullable|max:100',
            'identity_number' => 'required|max:30',
            'email' => 'required|unique:delivery_men',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:delivery_men',
            'zone_id' => 'required',
            'earning' => 'required',
            'password'=>'required|min:6',
            'vehicle_id' => 'required',
            'image' => 'nullable|max:2048',
            'identity_image.*' => 'nullable|max:2048',

        ], [
            'f_name.required' => __('messages.first_name_is_required'),
            'zone_id.required' => __('messages.select_a_zone'),
            'vehicle_id.required' => __('messages.select_a_vehicle'),
            'earning.required' => __('messages.select_dm_type')
        ]);

        // dd($request->post());
        // dd(auth()->guard('admin')->user());
        $admin = Auth::guard('admin')->user();

        if ($request->has('image')) {
            $image_name = Helpers::uploadFile($request->file('image'),'delivery-man/');
        } else {
            $image_name = 'def.png';
        }

        $dm = New DeliveryMan();

        if ($request->hasFile('identity_image')) {
            $id_img_names =[];
            foreach ($request->file('identity_image') as $img) {
                $identity_image = Helpers::updateFile($img, 'delivery-man/');
                array_push($id_img_names, $identity_image);
            }
            $dm->identity_image = json_encode($id_img_names);
        }
        $dm->f_name = $request->f_name;
        $dm->l_name = $request->l_name;
        $dm->email = $request->email;
        $dm->phone = $request->phone;
        $dm->identity_number = $request->identity_number;
        $dm->identity_type = $request->identity_type;
        $dm->zone_id = $request->zone_id;
        $dm->admin_id = $admin->id;
        $dm->vehicle_id = $request->vehicle_id;
        $dm->type = 'admin';
        $dm->earning =  $request->earning;;

        $dm->image = $image_name;
        $dm->active = 0;
        $dm->earning = $request->earning;
        $dm->password = bcrypt($request->password);
        $dm->save();

        return redirect('admin/delivery-man/list')->with('success',__('messages.deliveryman_added_successfully'));
    }

    public function edit($id)
    {
        $dm = DeliveryMan::find($id);

        $documentDetails  = null;
        if($dm->kyc != null){
            $documentDetails = $dm->kyc->documentDetails()->get()->toArray() ;
        }
        $documents = Document::where('type', 'deliveryman_kyc')->where('status', 'active')->get();

        return view('admin-views.delivery-man.edit', compact('dm','documents','documentDetails'));
    }

    public function viewKyc($dm_id){
        $delivery_man = DeliveryMan::findOrFail($dm_id);
        if(!$delivery_man->kyc){
            return back()->with('error','Kyc Not Available');
        }
        $documentDetails = $delivery_man->kyc->documentDetails()->with('document')->get();
        return view('admin-views.delivery-man.kyc-view', compact('delivery_man', 'documentDetails'));
    }

    public function status(Request $request)
    {
        $delivery_man = DeliveryMan::find($request->id);
        $delivery_man->status = $request->status;

        try
        {
            if($request->status == 0)
            {   $delivery_man->auth_token = null;
                if(isset($delivery_man->fcm_token))
                {
                    $data = [
                        'title' => __('messages.suspended'),
                        'description' => __('messages.your_account_has_been_suspended'),
                        'order_id' => '',
                        'image' => '',
                        'type'=> 'block'
                    ];
                    // Helpers::send_push_notif_to_device($delivery_man->fcm_token, $data);

                    // DB::table('user_notifications')->insert([
                    //     'data'=> json_encode($data),
                    //     'delivery_man_id'=>$delivery_man->id,
                    //     'created_at'=>now(),
                    //     'updated_at'=>now()
                    // ]);
                }

            }

        }
        catch (\Exception $e) {
            return back()->with('error',__('messages.push_notification_faild') ) ;
        }

        $delivery_man->save();

        return back()->with('success',__('messages.deliveryman_status_updated'));
    }

    public function reviews_status(Request $request)
    {
        $review = DMReview::find($request->id);
        $review->status = $request->status;
        $review->save();
        return back()->with('error',__('messages.review_visibility_updated') ) ;
    }

    public function earning(Request $request)
    {
        $delivery_man = DeliveryMan::find($request->id);
        $delivery_man->earning = $request->status;

        $delivery_man->save();

        return back()->with('success',__('messages.deliveryman_type_updated') ) ;

    }

    public function update(Request $request, $id)
    {
        try {
            // Define validation rules
            $rules = [
                'f_name' => 'required|max:100',
                'l_name' => 'nullable|max:100',
                'identity_number' => 'required|max:30',
                'email' => 'required|email',
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'vehicle_id' => 'required',
                'password' => 'nullable|min:6',
                'image' => 'nullable|image|max:2048',
                'identity_image.*' => 'nullable|image|max:2048',
            ];

            $messages = [
                'f_name.required' => __('messages.first_name_is_required'),
                'vehicle_id.required' => __('messages.select_a_vehicle'),
            ];

            // Add dynamic rules for KYC documents
            $kycDocuments = Document::where('type', 'deliveryman_kyc')->where('status', 'active')->get();
            foreach ($kycDocuments as $document) {
                if ($document->is_text && $document->is_text_required) {
                    $rules[$document->text_input_name] = 'required|string|max:255';
                }
                if ($document->is_media && $document->is_media_required) {
                    $rules[$document->media_input_name] = 'required|mimes:jpg,jpeg,png,pdf|max:10240';
                }
                if ($document->has_expiry_date) {
                    $rules[$document->expire_date_input_name] = 'nullable|date';
                }
            }

            // Validate the request
            $request->validate($rules, $messages);

            DB::beginTransaction();

            // Retrieve delivery man
            $delivery_man = DeliveryMan::findOrFail($id);

            // Update image if provided
            if ($request->has('image')) {
                $image_name = Helpers::updateFile($request->file('image'), 'delivery-man/', $delivery_man->image);
                $delivery_man->image = $image_name;
            }

            // Update identity images if provided
            if ($request->hasFile('identity_image')) {
                $id_img_names = $delivery_man->identity_image ? json_decode($delivery_man->identity_image, true) : [];
                foreach ($request->file('identity_image') as $img) {
                    $identity_image = Helpers::updateFile($img, 'delivery-man/');
                    $id_img_names[] = $identity_image;
                }
                $delivery_man->identity_image = json_encode($id_img_names);
            }

            // Update delivery man data
            $delivery_man->fill([
                'vehicle_id' => $request->vehicle_id,
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'identity_number' => $request->identity_number,
                'identity_type' => $request->identity_type,
                'type' => 'admin',
                'zone_id' => $request->zone_id,
                'earning' => $request->earning,
            ]);
            $delivery_man->save();

            $kyc = $delivery_man->kyc()->first(); // Get the existing KYC record
            if (!$kyc) {
                // If no KYC record exists, create a new one
                $kyc = $delivery_man->kyc()->create([
                    'status' => 'pending',
                ]);
            } else {
                // Update the existing KYC record
                $kyc->update([
                    'status' => 'pending',
                ]);
            }

            if(!$kyc){
                throw new Error('kyc cannot be empty');
            }

            // Handle KYC documents
            $documentData = [];
            foreach ($kycDocuments as $document) {
                $mediaValue = $document->is_media && $request->hasFile($document->media_input_name)
                    ? Helpers::uploadFile($request->file($document->media_input_name), 'uploads/kyc')
                    : null;

                $documentData[] = [
                    'document_id' => $document->id,
                    'text_value' => $request->input($document->text_input_name),
                    'media_value' => $mediaValue,
                    'expire_date' => $request->input($document->expire_date_input_name) ?? null,
                    'status' => 'pending',
                    'associate' => 'deliveryman',
                ];
            }

            // Update or create KYC document details
            foreach ($documentData as $data) {
                $kyc->documentDetails()->updateOrCreate(
                    [
                        'document_id' => $data['document_id'],
                        'associate' => $data['associate'],
                    ],
                    [
                        'text_value' => $data['text_value'],
                        'media_value' => $data['media_value'],
                        'expire_date' => $data['expire_date'],
                        'status' => $data['status'],
                    ]
                );
            }

            DB::commit();

            return redirect()->back()->with('success', __('messages.deliveryman_updated_successfully'));
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            // \Log::error('DeliveryMan Update Error: ' . $th->getMessage());
            return redirect()->back()->withErrors(['error' => __('messages.update_failed')]);
        }
    }



    public function update_application(Request $request)
    {
        $delivery_man = DeliveryMan::findOrFail($request->id);
        $delivery_man->application_status = $request->status;
        if($request->status == 'approved') $delivery_man->status = 1;
        $delivery_man->save();

        try{
            if( config('mail.status')) {
                // Mail::to($request['email'])->send(new \App\Mail\SelfRegistration($request->status, $delivery_man->f_name.' '.$delivery_man->l_name));
            }

        }catch(\Exception $ex){
            info($ex);
        }

        return back()->with('success',__('messages.application_status_updated_successfully') ) ;

    }



    public function delete(Request $request)
    {
        $delivery_man = DeliveryMan::find($request->id);
        if (Storage::disk('public')->exists('delivery-man/' . $delivery_man['image'])) {
            Storage::disk('public')->delete('delivery-man/' . $delivery_man['image']);
        }

        foreach (json_decode($delivery_man['identity_image'], true) as $img) {
            if (Storage::disk('public')->exists('delivery-man/' . $img)) {
                Storage::disk('public')->delete('delivery-man/' . $img);
            }
        }
        if($delivery_man->userinfo){

            $delivery_man->userinfo->delete();
        }
        $delivery_man->delete();
        return back()->with('success',__('messages.deliveryman_deleted_successfully'));
    }

    public function get_deliverymen(Request $request){
        $zone_id = $request->query('zone_id')??'all';

        $data=DeliveryMan::when($zone_id != 'all', function($query) use($zone_id){
            return $query->where('zone_id', $zone_id);
        })->deliverymanType('admin')->isActive()->get(['id',DB::raw('CONCAT(f_name, " ", l_name) as name'),'phone']);
        return response()->json($data);
    }

    public function get_account_data(DeliveryMan $deliveryman)
    {
        $wallet = $deliveryman->wallet;
        $cash_in_hand = 0;
        $balance = 0;

        if($wallet)
        {
            $cash_in_hand = $wallet->collected_cash;
            $balance = round($wallet->total_earning - $wallet->total_withdrawn - $wallet->pending_withdraw, config('round_up_to_digit'));
        }
        return response()->json(['cash_in_hand'=>$cash_in_hand, 'earning_balance'=>$balance], 200);

    }

    public function get_conversation_list(Request $request)
    {
        $user = UserInfo::where(['deliveryman_id' => $request->user_id])->first();
        $dm = DeliveryMan::find($request->user_id);
        if($user){
            $conversations = Conversation::with(['sender', 'receiver', 'last_message'])->WhereUser($user->id);
            if($request->query('key')) {
                $key = explode(' ', $request->get('key'));
                $conversations = $conversations->where(function($qu)use($key){
                    $qu->where(function($q)use($key){
                        $q->where('sender_type','!=', 'delivery_man')->whereHas('sender',function($query)use($key){
                            foreach ($key as $value) {
                                $query->where('f_name', 'like', "%{$value}%")->orWhere('l_name', 'like', "%{$value}%")->orWhere('phone', 'like', "%{$value}%");
                            }
                        });
                    })->orWhere(function($q)use($key){
                        $q->where('receiver_type','!=', 'delivery_man')->whereHas('receiver',function($query)use($key){
                            foreach ($key as $value) {
                                $query->where('f_name', 'like', "%{$value}%")->orWhere('l_name', 'like', "%{$value}%")->orWhere('phone', 'like', "%{$value}%");
                            }
                        });
                    });
                });
            }
            $conversations = $conversations->WhereUserType('delivery_man')->paginate(8);
        }else{
            $conversations = [];
        }

        $view = view('admin-views.delivery-man.partials._conversation_list',compact('conversations','dm'))->render();
        return response()->json(['html'=>$view]);

    }

    public function conversation_view($conversation_id,$user_id)
    {
        // $convs = Message::where(['conversation_id' => $conversation_id])->get();
        // $conversation = Conversation::find($conversation_id);
        // $receiver = UserInfo::find($conversation->receiver_id);
        // $sender = UserInfo::find($conversation->sender_id);
        // $user = UserInfo::find($user_id);
        // return response()->json([
        //     'view' => view('admin-views.delivery-man.partials._conversations', compact('convs', 'user', 'receiver'))->render()
        // ]);
    }
    public function dm_list_export(Request $request){

        // $withdraw_request = DeliveryMan::where('type','zone_wise')->orderBy('id','desc')->get();
        // if ($request->type == 'excel') {
        //     return (new FastExcel(Helpers::export_d_man($withdraw_request)))->download('deliveryman_list.xlsx');
        // } elseif ($request->type == 'csv') {
        //     return (new FastExcel(Helpers::export_d_man($withdraw_request)))->download('deliveryman_list.csv');
        // }
    }


    public function pending(Request $request)
    {
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', 'all');
        $delivery_men = DeliveryMan::when(is_numeric($zone_id), function($query) use($zone_id){
            return $query->where('zone_id', $zone_id);
        })
        ->when(isset($key),function($query)use($key){
            $query->where(function($q)use($key){
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                ->orWhere('l_name', 'like', "%{$value}%")
                ->orWhere('email', 'like', "%{$value}%")
                ->orWhere('phone', 'like', "%{$value}%")
                ->orWhere('identity_number', 'like', "%{$value}%");
                }
            });
        })
        ->with('zone')->where('type','zone_wise')->where('application_status', 'pending')->latest()->paginate(config('default_pagination'));
        $zone = is_numeric($zone_id)?Zone::findOrFail($zone_id):null;
        return view('admin-views.delivery-man.pending_list', compact('delivery_men', 'zone'));


    }
    public function denied(Request $request)
    {
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', 'all');
        $delivery_men = DeliveryMan::when(is_numeric($zone_id), function($query) use($zone_id){
            return $query->where('zone_id', $zone_id);
        })
        ->when(isset($key),function($query)use($key){
            $query->where(function($q)use($key){
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                ->orWhere('l_name', 'like', "%{$value}%")
                ->orWhere('email', 'like', "%{$value}%")
                ->orWhere('phone', 'like', "%{$value}%")
                ->orWhere('identity_number', 'like', "%{$value}%");
                }
            });
        })
        ->with('zone')->where('type','zone_wise')->where('application_status', 'denied')->latest()->paginate(config('default_pagination'));
        $zone = is_numeric($zone_id)?Zone::findOrFail($zone_id):null;
        return view('admin-views.delivery-man.denied', compact('delivery_men', 'zone'));
    }

    // public function get_incentives(Request $request)
    // {
    //     $incentives = IncentiveLog::when($request->search, function ($query) use ($request) {
    //         $key = explode(' ', $request->search);
    //         $query->whereHas('deliveryman', function ($query) use ($key) {
    //             $query->where(function ($q) use ($key) {
    //                 foreach ($key as $value) {
    //                     $q->orWhere('f_name', 'like', "%{$value}%");
    //                     $q->orWhere('l_name', 'like', "%{$value}%");
    //                     $q->orWhere('phone', 'like', "%{$value}%");
    //                     $q->orWhere('email', 'like', "%{$value}%");
    //                 }
    //             });
    //         });
    //     })
    //         ->where('status', '!=', 'pending')
    //         ->latest()->paginate(config('default_pagination'));
    //     return view('admin-views.delivery-man.incentive', compact('incentives'));
    // }
    // public function pending_incentives(Request $request)
    // {
    //     $incentives = IncentiveLog::
    //     when($request->search, function ($query) use ($request) {
    //         $key = explode(' ', $request->search);
    //         $query->whereHas('deliveryman', function ($query) use ($key) {
    //             $query->where(function ($q) use ($key) {
    //                 foreach ($key as $value) {
    //                     $q->orWhere('f_name', 'like', "%{$value}%");
    //                     $q->orWhere('l_name', 'like', "%{$value}%");
    //                     $q->orWhere('phone', 'like', "%{$value}%");
    //                     $q->orWhere('email', 'like', "%{$value}%");
    //                 }
    //             });
    //         });
    //     })
    //         ->whereStatus('pending')
    //         ->latest()->paginate(config('default_pagination'));
    //     return view('admin-views.delivery-man.incentive', compact('incentives'));
    // }

    // public function update_incentive_status(Request $request)
    // {
    //     $request->validate([
    //         'status' => 'required|in:denied',
    //         'id' => 'required'
    //     ]);

    //     $incentive = IncentiveLog::findOrFail($request->id);

    //     if ($incentive->status == "pending") {
    //         $incentive->status = $request->status;
    //         $incentive->save();
    //         Toastr::success(__('messages.incentive_denied'));
    //         return back();
    //     }

    // }

    // public function update_all_incentive_status(Request $request)
    // {
    //     $request->validate([
    //         'incentive_id' => 'required'
    //     ]);
    //     $incentives = IncentiveLog::whereIn('id', $request->incentive_id)->get();
    //     foreach ($incentives as $incentive) {
    //         Helpers::dm_wallet_transaction($incentive->delivery_man_id, $incentive->incentive, null, 'incentive');
    //         $incentive->status = "approved";
    //         // $incentive->subsidy = $request->incentive[$incentive->id];
    //         $incentive->save();
    //     }
    //     Toastr::success(__('messages.succesfully_approved_incentive'));
    //     return back();
    // }

    // public function get_bonus(Request $request)
    // {
    //     $data = WalletTransaction::where('transaction_type', 'dm_admin_bonus')
    //     ->when($request->search, function ($query) use ($request) {
    //             $query->where(function($query) use ($request) {
    //                 $key = explode(' ', $request->search);
    //                 $query->where(function ($q) use ($key) {
    //                     foreach ($key as $value) {
    //                         $q->Where('transaction_id', 'like', "%{$value}%");
    //                     }
    //                 })
    //                 ->orWhereHas('delivery_man', function ($query) use ($key) {
    //                     $query->where(function ($q) use ($key) {
    //                         foreach ($key as $value) {
    //                             $q->orWhere('f_name', 'like', "%{$value}%");
    //                             $q->orWhere('l_name', 'like', "%{$value}%");
    //                             $q->orWhere('phone', 'like', "%{$value}%");
    //                             $q->orWhere('email', 'like', "%{$value}%");
    //                         }
    //                     });
    //                 });
    //             });
    //     })

    //     ->paginate(config('default_pagination'));
    //     return view('admin-views.delivery-man.bonus', compact('data'));
    // }

    // public function add_bonus(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'delivery_man_id'=>'exists:delivery_men,id',
    //         'amount'=>'numeric|min:.01',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => Helpers::error_processor($validator)]);
    //     }

    //     if(Helpers::dm_wallet_transaction($request->delivery_man_id, $request->amount, $request->referance)){
    //         return response()->json(['message'=>trans('messages.bonus_added_successfully')], 200);
    //     }
    //     return response()->json(['errors' => [['code'=>'transaction-failed', 'message'=>__('messages.faield_to_create_transaction')]]]);
    // }
}
