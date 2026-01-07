<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\Mess\CartHelper;
use App\Models\Subscription;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function packageAddToCart(Request $request)
    {
        $request->validate([
            'package' => 'required|array',
            'package.*' => 'nullable|array'
        ], [
            'package.required' => 'At least one package is required.',
            'package.*.array' => 'Any Package does\'t selected'
        ]);

        $packages = $request->package;
        foreach($packages as $package)
        {
            if($package['quantity'] < 1){
                continue;
            }
            $subscriptionPackage = Subscription::find($package['id']);
            $data = [
                'product_id' => $subscriptionPackage->id,
                'quantity' => $package['quantity'],
                'package_data' => $subscriptionPackage->toArray(),

            ];

            $cartItemIfExists = CartHelper::hasItem($data['product_id']);
            if($cartItemIfExists){
                $data['uuid'] = CartHelper::getItem($data['product_id']);
                CartHelper::updateItem($data);
            }else{
                CartHelper::addItem($data);
            }
        }
        Session::flash('success', 'Cart Saved');
        return redirect()->route('user.mess.checkout');
    }
    public function checkout()
    {
        if(count(CartHelper::getCart()) > 0){
            $billing =new \App\CentralLogics\MessSubscriptionBilling();
            return view('user-views.mess.checkout', compact('billing'));
        }else{
            Session::flash('warning', 'Cart is empty');
            return redirect()->route('user.dashboard');
        }
    }

    // public static function FunctionName() : Returntype {

    // }
}
