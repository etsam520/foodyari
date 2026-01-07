<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\Helpers;
use App\Models\DiscountCoupon;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    public function add_new(Request $request)
    {
        $key = explode(' ', $request['search']);
        $coupons = DiscountCoupon::latest()->where('created_by', 'vendor' )->where('restaurant_id',Helpers::get_restaurant_id())
        ->when( isset($key) , function($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%")
                    ->orWhere('code', 'like', "%{$value}%");
                }
            });
        }
        )
        ->get();
        return view('vendor-views.coupon.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:discount_coupons|max:100',
            'title' => 'required|max:191',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required',
            'coupon_type' => 'required|in:free_delivery,default',
        ]);
        $customer_id  = $request->customer_ids ?? ['all'];
        $data = "";
        DB::table('discount_coupons')->insert([
            'title' => $request->title,
            'code' => $request->code,
            'limit' => $request->coupon_type=='first_order'?1:$request->limit,
            'coupon_type' => $request->coupon_type,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'min_purchase' => $request->min_purchase != null ? $request->min_purchase : 0,
            'max_discount' => $request->max_discount != null ? $request->max_discount : 0,
            'discount' => $request->discount_type == 'amount' ? $request->discount : $request['discount'],
            'discount_type' => $request->discount_type??'',
            'status' => 1,
            'created_by' => 'vendor',
            'data' => json_encode($data),
            'restaurant_id' =>Helpers::get_restaurant_id(),
            'customer_id' => json_encode($customer_id),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Session::flash('success',__('messages.coupon_added_successfully'));
        return back();
    }

    public function edit($id)
    {
        $coupon = DiscountCoupon::where(['id' => $id])->where('created_by', 'vendor' )->first();
        // dd(json_decode($coupon->data));
        return view('vendor-views.coupon.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:100|unique:discount_coupons,code,'.$id,
            'title' => 'required|max:191',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required',
            'coupon_type' => 'required|in:free_delivery,default',

        ]);

        $customer_id  = $request->customer_ids ?? ['all'];

        DB::table('discount_coupons')->where(['id' => $id])->update([
            'title' => $request->title,
            'code' => $request->code,
            'limit' => $request->coupon_type=='first_order'?1:$request->limit,
            'coupon_type' => $request->coupon_type,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'min_purchase' => $request->min_purchase != null ? $request->min_purchase : 0,
            'max_discount' => $request->max_discount != null ? $request->max_discount : 0,
            'discount' => $request->discount_type == 'amount' ? $request->discount : $request['discount'],
            'discount_type' => $request->discount_type??'',
            'customer_id' => json_encode($customer_id),
            'updated_at' => now()
        ]);

        Session::flash('success',__('messages.coupon_updated_successfully'));
        return redirect()->route('vendor.coupon.add-new');
    }

    public function status(Request $request)
    {
        $coupon = DiscountCoupon::find($request->id);
        $coupon->status = $request->status;
        $coupon->save();

        return back()->with('success',__('messages.coupon_status_updated'));
    }

    public function delete(Request $request)
    {
        $coupon = DiscountCoupon::find($request->id);
        $coupon->delete();

        return back()->with('success',__('messages.coupon_deleted_successfully'));
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $coupons=DiscountCoupon::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%")
                ->orWhere('code', 'like', "%{$value}%");
            }
        })->where('restaurant_id',Helpers::get_restaurant_id())->limit(50)->get();
        return response()->json([
            'view'=>view('vendor-views.coupon.partials._table',compact('coupons'))->render(),
            'count'=>$coupons->count()
        ]);
    }
}
