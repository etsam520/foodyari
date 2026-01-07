<?php

namespace App\Http\Controllers\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\DeliveryMan;
use App\Models\DMReview;
use App\Models\MessDeliveryMan;
use App\Models\UserInfo;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Zone;
use Carbon\Carbon;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mockery\CountValidator\Exact;

class DeliveryManController extends Controller
{
    public function index()
    {

        return view('mess-views.delivery-man.index');
    }

    public function list()
    {
       
        $delivery_men = self::getMessList();
        return view('mess-views.delivery-man.list', compact('delivery_men'));
    }

    public static function getMessList() {
        $messId = Session::get('mess')->id;
       return DeliveryMan::where('mess_id',$messId)->orderBy('id', 'DESC')->get();
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
            'street' => 'required',
            'city' => 'required',
            'shift_id' => 'required',
            'pincode' => "required|digits:6",
            'password'=>'required|min:6',
            'vehicle_id' => 'required',
            'image' => 'nullable|max:2048',
            'identity_image.*' => 'nullable|max:2048',
            "password" => "required|min:6",
            "c_password" => "required|same:password",

        ], [
            'f_name.required' => __('messages.first_name_is_required'),
            'vehicle_id.required' => __('messages.select_a_vehicle'),
        ]);

        if ($request->has('image')) {
            $image_name = Helpers::uploadFile($request->file('image'),'delivery-man/');
        } else {
            $image_name = 'def.png';
        }

        $id_img_names = [];
        if (!empty($request->file('identity_image'))) {
            foreach ($request->identity_image as $img) {
                $identity_image = Helpers::uploadFile( $img,'delivery-man/');
                array_push($id_img_names, $identity_image);
            }
            $identity_image = json_encode($id_img_names);
        } else {
            $identity_image = json_encode([]);
        }

        $dm = New DeliveryMan();
        $dm->f_name = $request->f_name;
        $dm->l_name = $request->l_name;
        $dm->email = $request->email;
        $dm->phone = $request->phone;
        $dm->shift_id = $request->shift_id;
        $dm->address = json_encode([
            'street' => $request->street,
            'city' => $request->city,
            'pincode' => $request->pincode
        ]);
        $dm->identity_number = $request->identity_number;
        $dm->identity_type = $request->identity_type;
        $dm->vehicle_id = $request->vehicle_id;
        $dm->identity_image = $identity_image;
        $dm->image = $image_name;
        $dm->type = 'mess';
        $dm->active = 1;
        $dm->mess_id = Session::get('mess')->id;
        $dm->password = bcrypt($request->password);
        $dm->save();

        return redirect('mess/delivery-man/list')->with('success',__('messages.deliveryman_added_successfully'));
    }

    public function edit($id)
    {
        $delivery_man = DeliveryMan::find($id);
        return view('admin-views.delivery-man.edit', compact('delivery_man'));
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'f_name' => 'required|max:100',
            'l_name' => 'nullable|max:100',
            'identity_number' => 'required|max:30',
            'email' => 'required|unique:delivery_men,email,'.$id,
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:delivery_men,phone,'.$id,
            'vehicle_id' => 'required',
            // 'earning' => 'required',
            'password' => 'nullable|min:6',
            'image' => 'nullable|max:2048',
            'identity_image.*' => 'nullable|max:2048',
        ], [
            'f_name.required' => __('messages.first_name_is_required'),
            'earning.required' => __('messages.select_dm_type'),
            'vehicle_id.required' => __('messages.select_a_vehicle'),

        ]);

        $delivery_man = DeliveryMan::find($id);

        if ($request->has('image')) {
            $image_name = Helpers::updateFile(  $request->file('image'), $delivery_man->image,'delivery-man/');
        } else {
            $image_name = $delivery_man['image'];
        }

        if ($request->has('identity_image')){
            foreach (json_decode($delivery_man['identity_image'], true) as $img) {
                if (Storage::disk('public')->exists('delivery-man/' . $img)) {
                    Storage::disk('public')->delete('delivery-man/' . $img);
                }
            }
            $img_keeper = [];
            foreach ($request->identity_image as $img) {
                $identity_image = Helpers::uploadFile($img ,'delivery-man/' );
                array_push($img_keeper, $identity_image);
            }
            $identity_image = json_encode($img_keeper);
        } else {
            $identity_image = $delivery_man['identity_image'];
        }

        $delivery_man->vehicle_id = $request->vehicle_id;

        $delivery_man->f_name = $request->f_name;
        $delivery_man->l_name = $request->l_name;
        $delivery_man->email = $request->email;
        $delivery_man->phone = $request->phone;
        $delivery_man->identity_number = $request->identity_number;
        $delivery_man->identity_type = $request->identity_type;
        $delivery_man->zone_id = $request->zone_id;
        $delivery_man->identity_image = $identity_image;
        $delivery_man->image = $image_name;
        // $delivery_man->earning = $request->earning;
        $delivery_man->password = strlen($request->password)>1?bcrypt($request->password):$delivery_man['password'];
        $delivery_man->save();
        return redirect('admin/delivery-man/list')->with('success',__('messages.deliveryman_updated_successfully'));
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
        $key = explode(' ', $request->q);
        $zone_ids = isset($request->zone_ids)?(count($request->zone_ids)>0?$request->zone_ids:[]):0;
        $data=DeliveryMan::when($zone_ids, function($query) use($zone_ids){
            return $query->whereIn('zone_id', $zone_ids);
        })
        ->when($request->earning, function($query){
            return $query->earning();
        })
        ->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%")
                    ->orWhere('l_name', 'like', "%{$value}%")
                    ->orWhere('email', 'like', "%{$value}%")
                    ->orWhere('phone', 'like', "%{$value}%")
                    ->orWhere('identity_number', 'like', "%{$value}%");
            }
        })->active()->limit(8)->get(['id',DB::raw('CONCAT(f_name, " ", l_name) as text')]);
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
    
    
    public function wallet(){
        $dm_list = self::getMessList();
        return view('mess-views.delivery-man.wallet', compact('dm_list'));
    }
    public function getwalletdata(Request $request){
        try {
            $dm_id = $request->get('dm_id');
            if(empty($dm_id)){
                throw new Error('Empty Request');
            }
            $dm_wallet = Wallet::where('mess_deliveryman_id',$dm_id)->first();
            if (!$dm_wallet) {
                $dm_wallet = new Wallet();
                $dm_wallet->balance = 0;
                $dm_wallet->mess_deliveryman_id = $dm_id;
                $dm_wallet->save();
            }
            return response()->json($dm_wallet);
        } catch (\Exception $th) {
            return response()->json('error', $th->getMessage());
        }
        
    }
    public function updateWallet(Request $request){
        try {
            $data = $request->json('data');
            $validator = Validator::make($data, [
                'walletId' => 'required',
                'add_amount' => 'required|numeric|min:0', // Ensure add_amount is numeric and non-negative
            ]);
    
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
            
            DB::beginTransaction();
            
            $amount = $data['add_amount']; // Access add_amount correctly
    
            $mywallet = Wallet::where('mess_id', Session::get('mess')->id)->first();
            $dm_wallet = Wallet::find($data['walletId']);
    
            $mywallet->balance -= $amount;
            $mywallet->save();
    
            $mywallet->WalletTransactions()->create([
                'amount' => $amount,
                'type' => 'Cr',
                'remarks' => 'Amount ' . Helpers::format_currency($amount) . ' Added To Delivery Man'
            ]);
    
            $dm_wallet->balance += $amount; // Adjusted to add amount to delivery man's wallet
            $dm_wallet->save();
    
            $dm_wallet->WalletTransactions()->create([
                'amount' => $amount,
                'type' => 'Dr',
                'remarks' => 'Amount ' . Helpers::format_currency($amount) . ' Added By Mess Owner'
            ]);
    
            DB::commit();
            
            return response()->json(['success' => 'Wallet updated successfully']);
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    public function walletTransactions(Request $request)
    {
        $walletId = $request->get('wallet_id');
        $walletTransactions = WalletTransaction::where('wallet_id', $walletId)->orderBy('created_at', 'DESC')->paginate(15);
        // if ($request->ajax()) {
            return view('mess-views.delivery-man.history', compact('walletTransactions'))->render();  
        // }
        // dd($transactions);
        // Return the paginated data as a JSON response
        // return response()->json($transactions);
    }
}
