<?php

namespace App\Http\Controllers\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use function PHPSTORM_META\type;

class SubscriptionController extends Controller
{
    
    public function index()
    {
        return view('mess-views.subscription.add');

    }

    public function submit(Request $request)
    {
        // dd(Session::get('vendor'));
        $request->validate([
            "title" => 'required|string',
            "description" => 'nullable|string',
            "validity" => 'required|numeric',
            'speciality' => 'required|boolean',
            'item_type' => 'required',
            "price" => 'required|numeric',
            "no_d_breakfast" => 'nullable|numeric',
            "no_d_lunch" => 'nullable|numeric',
            "no_d_dinner" => 'nullable|numeric',
            "no_diet_special" => 'nullable|numeric',
            "discount_type" => 'nullable|string',
            "discount" => 'nullable|string',
        ]);
        try {
            if ((int)$request->no_d_breakfast + (int)$request->no_d_lunch + (int)$request->no_d_dinner < 1) {
                throw new \Exception('Normal Diets Cannot be Zero');
            }
            $subscription = new Subscription();
            $subscription->title = $request->title;
            $subscription->description = $request->description??null;
            $subscription->validity = $request->validity;
            $subscription->speciality = $request->speciality;
            $subscription->type = Helpers::getFoodType($request->item_type);
            $subscription->diets =  json_encode([
                                        'breakfast' => $request->no_d_breakfast??0,
                                        'lunch' => $request->no_d_lunch??0,
                                        'dinner' => $request->no_d_dinner??0,
                                        'special' =>  $request->no_diet_special??0
                                    ]);
            $subscription->discount = $request->discount??0;
            $subscription->discount_type = $request->discount_type??0;
            $subscription->price = $request->price; 
            $subscription->mess_id = Session::get('mess')->id;
            $subscription->save(); 
            
            return redirect()->back()->with('success','Subscription Pakage Created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function list()
    {
        $subscriptions = Subscription::where('mess_id',Session::get('mess')->id)->latest()->get();
        // dd($subscriptions);
        return view('mess-views.subscription.list',compact('subscriptions'));
    }
    
    public function pakagelist(Request $request)
    {
        $type = $request->input('type');
        // dd($type);
        try {
            $subscriptions = Subscription::where('mess_id', Session::get('mess')->id)->where('type',Helpers::getFoodType($type) )->latest()->get();
            if (empty($subscriptions)) {
                throw new \Exception('Data Not Found');
            }
            return response()->json($subscriptions);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }

    public function edit($id)
    {

        try {
            $subscription = Subscription::find($id);

            if (!$subscription) {
                throw new \Exception('Data Not Found');
            }

            return view('mess-views.subscription.edit',compact('subscription'));
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    public function update(Request $request)
    {
        // dd($request->file());
        $rule = [
            'id' => 'required',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'validity' => 'required|numeric',
            'speciality' => 'required|boolean',
            'item_type' => 'required|string',
            'price' => 'required|numeric',
            'no_d_breakfast' => 'nullable|numeric',
            'no_d_lunch' => 'nullable|numeric',
            'no_d_dinner' => 'nullable|numeric',
            'no_diet_special' => 'nullable|numeric',
            'discount_type' => 'nullable|string',
            'discount' => 'nullable|numeric',
        ];
        
        $subscription = Subscription::find($request->id);
        
        if ($subscription && $subscription->images) {
            $rule['product_images'] = 'nullable|array';
            $rule['product_images.*'] = 'nullable|mimes:jpg,png,jpeg,svg';
        } else {
            $rule['product_images'] = 'required|array';
            $rule['product_images.*'] = 'required|mimes:jpg,png,jpeg,svg';
        }
        
        $request->validate($rule);
        
        try {

            if ((int)$request->no_d_breakfast + (int)$request->no_d_lunch + (int)$request->no_d_dinner < 1) {
                throw new \Exception('Normal Diets Cannot be Zero');
            }
            if($request->product_images){
                
                $imgData = $subscription->images? json_decode($subscription->images) : [];
                foreach ($request->product_images as $img) {
                    $imgData[] = Helpers::uploadFile($img, 'messSubscriptionToCustomers');
                }
                
                $subscription->images = json_encode($imgData);
            }
            
            $subscription->title = $request->title;
            $subscription->description = $request->description??null;
            $subscription->validity = $request->validity;
            $subscription->speciality = $request->speciality;
            $subscription->type = $request->item_type;
            $subscription->diets =  json_encode([
                                        'breakfast' => $request->no_d_breakfast??0,
                                        'lunch' => $request->no_d_lunch??0,
                                        'dinner' => $request->no_d_dinner??0,
                                        'special' =>  $request->no_diet_special??0
                                    ]);
            $subscription->discount = $request->discount??0;
            $subscription->discount_type = $request->discount_type??0;
            $subscription->price = $request->price; 
            $subscription->mess_id = Session::get('mess')->id;
            $subscription->save(); 
            
            return redirect()->back()->with('success','Subscription Pakage Updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }



}
