<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use App\Models\DiscountCouponUsed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function add_new(Request $request)
    {
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
        return view('admin-views.coupon.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:discount_coupons|max:100',
            'title' => 'required|max:191',
            'description' => 'nullable|string',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required',
            'coupon_type' => 'required|in:zone_wise,restaurant_wise,free_delivery,first_order,default',
            'zone_ids' => 'required_if:coupon_type,zone_wise',
            'restaurant_ids' => 'required_if:coupon_type,restaurant_wise'
        ]);
        $data  = '';
        $customer_id  = $request->customer_ids ?? ['all'];
        if($request->coupon_type == 'zone_wise')
        {
            $data = $request->zone_ids;
        }
        else if($request->coupon_type == 'restaurant_wise')
        {
            $data = $request->restaurant_ids;
        }

        DB::table('discount_coupons')->insert([
            'title' => $request->title,
            'code' => $request->code,
            'description' => $request->description,
            'limit' => $request->coupon_type=='first_order'?1:$request->limit,
            'coupon_type' => $request->coupon_type,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'min_purchase' => $request->min_purchase != null ? $request->min_purchase : 0,
            'max_discount' => $request->max_discount != null ? $request->max_discount : 0,
            'discount' => $request->discount_type == 'amount' ? $request->discount : $request['discount'],
            'discount_type' => $request->discount_type??'',
            'status' => 1,
            'created_by' => 'admin',
            'data' => json_encode($data),
            'customer_id' => json_encode($customer_id),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return back()->with('success',__('messages.coupon_added_successfully'));
    }

    public function edit($id)
    {
        $coupon = DiscountCoupon::where(['id' => $id])->first();
        // dd(json_decode($coupon->data));
        return view('admin-views.coupon.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:100|unique:discount_coupons,code,'.$id,
            'title' => 'required|max:191',
            'description' =>'nullable|string',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required',
            'zone_ids' => 'required_if:coupon_type,zone_wise',
            'restaurant_ids' => 'required_if:coupon_type,restaurant_wise',
            'delivery_range' => 'required_if:coupon_type,free_delivery',
            // "enble_ext_distance" => ''
        ]);
        // dd($request->post());
        $data  = '';
        if($request->coupon_type == 'zone_wise')
        {
            $data = $request->zone_ids;
        }
        else if($request->coupon_type == 'restaurant_wise')
        {
            $data = $request->restaurant_ids; 
        }
        $customer_id  = $request->customer_ids ?? ['all'];
        DB::table('discount_coupons')->where(['id' => $id])->update([
            'title' => $request->title,
            'code' => $request->code,
            'description' => $request->description,
            'limit' => $request->coupon_type=='first_order'?1:$request->limit,
            'coupon_type' => $request->coupon_type,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'min_purchase' => $request->min_purchase != null ? $request->min_purchase : 0,
            'max_discount' => $request->max_discount != null ? $request->max_discount : 0,
            'discount' => $request->discount_type == 'amount' ? $request->discount : $request['discount'],
            'discount_type' => $request->discount_type??'',
            'data' => json_encode($data),
            'customer_id' => json_encode($customer_id),
            'delivery_range' => $request->delivery_range??0,
            'enble_ext_distance' =>(bool) $request->enble_ext_distance ?? false ,
            'updated_at' => now()
        ]);

        return redirect()->route('admin.coupon.add-new')->with('success',__('messages.coupon_updated_successfully'));
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

    public function usesDetails($id)
    {
        $coupon = DiscountCoupon::findOrFail($id);
        $usesDetails = DiscountCouponUsed::leftJoin('orders', 'discount_coupon_useds.order_id', '=', 'orders.id')
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->select(
                'discount_coupon_useds.*',
                'orders.order_amount',
                'orders.created_at',
                DB::raw('CONCAT(customers.f_name, " ", customers.l_name) as user_name'),
                'customers.phone',
                'customers.email'
            )
            ->where('discount_coupon_id', $id)
            ->orderBy('used_at', 'desc')
            ->paginate(config('default_pagination', 25));

        return view('admin-views.coupon.uses-details', compact('coupon', 'usesDetails'));
    }
}

