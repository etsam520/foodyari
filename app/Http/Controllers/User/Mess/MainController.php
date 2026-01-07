<?php

namespace App\Http\Controllers\User\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Attendace;
use App\Models\Customer;
use App\Models\MessAddonModel;
use App\Models\MessCharges;
use App\Models\MessMenu;
use App\Models\MessService;
use App\Models\MessTiming;
use App\Models\Subscription;
use App\Models\SubscriptionOrderDetails;
use App\Models\UserRole;
use App\Models\VendorMess;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MainController extends Controller
{
    public function home(): View{
        $nearBymess = true;
        if(Session::has('userLocation')){
            $messes =  VendorMess::findNearbyLocations();
            if($messes->count()<1){
                $nearBymess = false;
                $messes = VendorMess::latest()->get();
            }
        }else {
            $nearBymess = false;
            $messes = VendorMess::latest()->get();
        }

        return view('user-views.dashboard', compact('messes','nearBymess'));
    }
    public function createUser(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            "f_name" => 'required|string',
            "l_name" => 'nullable|string',
            "street" => 'required|string',
            "phone" => 'required|numeric|digits:10|unique:customers',
            "email" => 'required|email|unique:customers',
            "pincode" => 'required|digits:6',
            "city" => "required|string",
            "password" => "required|min:6", 
            "c_password" => "required|same:password", 
            'image' => 'required|mimes:jpeg,jpg,png', 
        ],[
            'f_name' => 'First Name is Required',
            'street' => 'Address is Required',
            'phone' => 'Phone is Required',
            'email' => 'Email is Required',
            'email.unique' => 'Email already exists',
            'pincode' => 'Pincode is Required',
            'city' => 'City is Required',
            'password' => 'Password is Required',
            'c_password' => 'Password not matched',
            'image' => 'Image Required',
        ]);

        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
        
            $role = UserRole::where('role', 'customer')->first();
            
            if(!$role) {
                throw new \Exception('Role not found');
            }
        
            $customer = Customer::create([
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'image' => Helpers::uploadFile($request->image, 'customers'),
                'password' => bcrypt($request->password),
                'role_id' => $role->id,
                'status' => 1,
                'address' => json_encode([
                    'street' => $request->street,
                    'city' => $request->city,
                    'pincode' => $request->pincode
                ]),
            ]);
            
            if (!$customer) {
                throw new \Exception('Failed to Add Customer');
            }

             Wallet::create([
                'customer_id' => $customer->id,
            ]);
            DB::commit();
            if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password])) {
                Session::put('userInfo',Auth::guard('customer')->user());
                return response()->json(['success' => 'User Created!!','redirect' =>route('user.dashboard')]);
            }
            
            return response()->json(['success' => 'User Created!!']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['errors' => $ex->getMessage()], 422);
        }
    }

    
    public function index($messId) : View
    {   
        $mess = VendorMess::with('subscription')->where('id',$messId)->first();
        return view('user-views.mess.index2',compact('mess'));
    }

    public function index2() : View
    {  
        return view('user-views.mess.index3');
    }

    public function index3() : View
    {   
       
        return view('user-views.mess.index4');
    }

    public function index4() : View
    {   
        $messList = VendorMess::all();
        $userInfo =  Session::get('userInfo');
        $meesMunu ='';
        if(isset($userInfo->subscription_id)){
           $subscription = Subscription::where('id',$userInfo->subscription_id)->with('mess')->first();
           if($subscription){
               $meesMunu = MessMenu::whereDate('created_at',Carbon::now()->format('Y-m-d'))
                           ->where('mess_id',$subscription->mess_id)
                           ->with('messServices')
                           ->get(); 
           }
        }
        return view('user-views.mess.index4',compact('messList','meesMunu','userInfo'));
    }    

    // gettion list of subscriptons
    public function list($id)
    {
        $subscriptions = Subscription::where('mess_id', $id)->latest()->get();
        // dd($subscriptions);
        if(!$subscriptions){
            return redirect()->back()->with('error', 'Subscription Not Found');
        }
        return view('user-views.mess.subscriptions',compact('subscriptions'));
    }

    public function addons(Request $request)  {
        try{
            $menuId = $request->get('menu_id');
            if(empty($menuId)){
                throw new \Exception('Id Is Empty');
            }

            $menu = MessMenu::find($menuId);
            if(!$menu){
                throw new \Exception('Menu Not Found');  
            }

            $addons = MessAddonModel::whereIn('id',json_decode($menu->addons,true))->get();
            return response()->json($addons);
        }catch(\Exception $ex){
            return response()->json('error',$ex->getMessage());
        }      
    }

    public function storeaddons(Request $request){
        try{
            $addons =$request->json('addons'); 
            $menu_id =$request->json('menu_id'); 
            $menuWithServices =  MessMenu::where('id',$menu_id)->with('messServices')->first();
            $service = $menuWithServices->messServices[0];
            $today = Carbon::now('Asia/Kolkata')->toDateString(); 
            $timenow = Carbon::now('Asia/Kolkata');
            $endTime = Carbon::parse($service->available_time_starts)->addMinutes(30);
            $customer = Session::get('userInfo');
            if ($endTime->isPast()) {
                throw new \Exception('Time For Updation is End Now');
            }elseif($customer->diet_status != 1){
                throw new \Exception('Please First Active You Diet');
            }
            if(!empty($service)){
                $attendance = Attendace::whereDate('created_at', $today)
                ->where('customer_id', $customer->id)
                ->firstOrNew();
                $attendance->customer_id = $customer->id;
                $attendance->save();
                
                // Using relationships to handle checklist
                $checklist = $attendance->checklist()->updateOrCreate(
                    ['attendance_date' => $today, 'service_id' => $service->id],
                    ['attendance_time' => $timenow->toTimeString(), 'checked' => 1,
                    'addons' =>json_encode($addons)]
                );
                if($checklist){
                    return response()->json(['success'=>'Addons Added with Your Diet']);
                }else{
                    throw new \Exception('Nothig To Update');
                }
            }else{
                throw new \Exception('Service Not Available');
            }
            
        }catch(\Exception $ex){
            return response()->json(['error'=>$ex->getMessage()]);
        }
    }

    public function dietCancel(Request $request) 
    {
        try{
            $menuId = $request->get('menu_id');
            $menuWithServices =  MessMenu::where('id',$menuId)->with('messServices')->first();
            $service = $menuWithServices->messServices[0];
            $today = Carbon::now('Asia/Kolkata')->toDateString(); 
            $timenow = Carbon::now('Asia/Kolkata');
            $endTime = Carbon::parse($service->available_time_starts)->addMinutes(30);
            $customer = Session::get('userInfo');
            if ($endTime->isPast()) {
                throw new \Exception('Time For Updation is End Now');
            }elseif($customer->diet_status != 1){
                throw new \Exception('Please First Active You Diet');
            }
            if(!empty($service)){
                $attendance = Attendace::whereDate('created_at', $today)
                ->where('customer_id', $customer->id)
                ->firstOrNew();
                $attendance->customer_id = $customer->id;
                $attendance->save();
                
                // Using relationships to handle checklist
                $checklist = $attendance->checklist()->updateOrCreate(
                    ['attendance_date' => $today, 'service_id' => $service->id],
                    ['attendance_time' => $timenow->toTimeString(), 'checked' => 0]
                );
                if($checklist){
                    return response()->json(['success'=>'Diet Cancelled']);
                }else{
                    throw new \Exception('Nothig To Update');
                }
            }else{
                throw new \Exception('Service Not Available');
            }
        }catch(\Exception $ex){
            return response()->json(['error'=>$ex->getMessage()]);
        }
    }

    public function holdDiet()
    {
        $user = Session::get('userInfo');
        $customer = Customer::with('user')->where('id',$user->id)->first();
        if($customer->diet_status == 1){
            $customer->diet_status = 0 ;
            $data= ['holdDietIndex' => 1,'textContent' => 'Active Your Diet'];
        }else{
            $customer->diet_status = 1 ;
            $data= ['holdDietIndex' => 0,'textContent' =>'Inactive Your Diet'];
        }
        if($customer->save()){
            Session::put('userInfo',$customer);
            $data['success'] = "Status Updated";
            return response()->json($data);
        }else{
            return response()->json(['error', 'Failed To Update']);
        }
    }
    /***
     * ======================// My Wallet //=======================
     */

    public function mywallet() 
    {
       
        $mess = Session::get('mess');
       $mywallet = Wallet::where('vendor_id', $mess->vendor_id)->first();
       if(!$mywallet){
            $newWallet = new Wallet();
            $newWallet->balance = 0;
            $newWallet->vendor_id = $mess->vendor_id;
            if($newWallet->save()){
                Session::flash('success', 'New Wallet Created');
            }
            $mywallet = $newWallet;
            
       }
    //    dd($mywallet);
       return view('mess-views.mywallet.index',compact('mywallet'));
        
    }

    public function mywalletHistories() 
    {
        $mess = Session::get('mess');

        $mywallet = Wallet::where('vendor_id', $mess->vendor_id)
            ->with(['WalletTransactions' => function ($query) use($mess) {
                $query->where('mess_id',$mess->id)->orderBy('created_at', 'DESC');
            }])
            ->first();

        // Paginate the WalletTransactions
        $walletTransactions = $mywallet->WalletTransactions()->paginate(20);

        return view('mess-views.mywallet.history', compact('mywallet', 'walletTransactions'));
    }

    public function addToMywallet(Request $request)
    {
        $request->validate([
            'add_amount' => 'required|numeric|min:0',
        ]);
    
        try {
            $messId = Session::get('mess')->id;
            $mywallet = Wallet::where('mess_id', $messId)->first();
    
            if (!$mywallet) {
                throw new \Error("You don't have any wallet.");
            }
    
            $mywallet->balance += (int) $request->add_amount;
    
            if ($mywallet->save()) {
                $mywallet->WalletTransactions()->create([
                    'amount' => (int) $request->add_amount,
                    'type' => 'Dr',
                    'remarks' => "Dear Mess, you have added " . Helpers::format_currency($request->add_amount) . " to your wallet successfully.",
                ]);
    
                return redirect()->back()->with('success', "Amount added to your wallet successfully!");
            } else {
                throw new \Error("Failed to add to the wallet.");
            }
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }
    
/***
     * ======================// My Invoice //=======================
     */
    public function invoice(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = SubscriptionOrderDetails::with(['customer','paymentDetail','orderItems.package','mess'])->find($orderId);
        // dd($order);
       
        
       return view('user-views.mess.invoices.index',compact('order'));
    }
    /***
     * ======================// business Setup Section //=======================
     */
    public function charges() : View {
        $messId = Session::get('mess')->id;
        $charges = MessCharges::where('mess_id', $messId)->first();
        // dd($charges);
        return view('mess-views.business_setup.chargesTable', compact('charges'));
    }
    
    public function chargesSave(Request $request)
    {
        $request->validate([
            'GST' => 'required',
            'mess_charge' => 'required',
            'mess_charge_type' => 'required',
            'admin_charge' => 'required',
            'admin_charge_type' => 'required',
            'delivery_man_charge' => 'required',
            'delivery_man_charge_type' => 'required'
        ]);
    
        try {
            $messId = Session::get('mess')->id;
            $chargeType = ['F' => 'fixed', 'P' => 'percent'];
            $charges = MessCharges::updateOrCreate(
                ['mess_id' => $messId],
                [
                    'GST' => $request->GST,
                    'mess_charge' => $request->mess_charge,
                    'mess_charge_type' => $chargeType[$request->mess_charge_type],
                    'admin_charge' => $request->admin_charge,
                    'admin_charge_type' => $chargeType[$request->admin_charge_type],
                    'delivery_man_charge' => $request->delivery_man_charge,
                    'delivery_man_charge_type' => $chargeType[$request->delivery_man_charge_type]
                ]
            );
    
            if ($charges) {
                return redirect()->back()->with('success', 'Charges Saved');
            } else {
               throw new \Error( 'Failed to save charges');
            }
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function timing() 
    {
        $timing = MessTiming::where(['mess_id' => Session::get('mess')->id])->first();
        return view('mess-views.info.timing',compact('timing'));
    }
    
    public function timingsave(Request $request) 
    {
        $request->validate([
            "delivery_breakfast" => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
            "delivery_lunch" => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
            "delivery_dinner" => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
            "dinein_breakfast" => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
            "dinein_lunch" => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
            "dinein_dinner" => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
        ]);

        $timing = MessTiming::firstOrNew(['mess_id' => Session::get('mess')->id]);

        $timing->delivery = json_encode([
            'breakfast' => $request->delivery_breakfast,
            'lunch'     => $request->delivery_lunch,
            'dinner'    => $request->delivery_dinner,
        ]);

        $timing->dine_in = json_encode([
            'breakfast' => $request->dinein_breakfast,
            'lunch'     => $request->dinein_lunch,
            'dinner'    => $request->dinein_dinner,
        ]);

        $timing->save();

        return back()->with('success', "Timing Changed");
    }
      
}
