<?php

namespace App\Http\Controllers\User\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class ReviewController extends Controller
{
    public function makeReview(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            // Retrieve the order with its details using the order number

            $orderNO = $request->query('order_id');
            $review = $request->input('review');
            $rating = $request->input('rating');

            $order = Order::with('details')->find($orderNO);

            if (!$order) {
                // Handle case where the order does not exist
                return response()->json(['error' => 'Order not found'], 404);
            }

            $result = Auth::guard('customer')->user()->reviews()->create([
                'order_id' => $orderNO,
                'rating' => $rating,
                'review' => $review,
                'restaurant_id' => $order->restaurant_id,
                'review_to' => 'restaurant'
            ]);

            // Collect food item IDs from the current order's details
            $orderFoodIds = [];
            foreach ($order->details as $foodItem) {
                $foodItem->review_id = $result->id;
                $orderFoodIds[] = $foodItem->food_id;
                $foodItem->save();
            }

            $order->review_id = $result->id;
            $order->save();

            // Get the restaurant ID from the order
            $restaurant_id = $order->restaurant_id;

            // Find all orders from the same restaurant that have a review
            $reviewedOrders = Order::with('review')
                ->where('restaurant_id', $restaurant_id)
                ->whereNotNull('review_id')
                ->get();

            if(isset($reviewedOrders[0])){
                $sum_for_restaurnt =0;
                $counter_for_restaurnt = 0;
                $avgRating_for_restaurnt = 0;
                foreach($reviewedOrders as $r_orders){
                    $sum_for_restaurnt += (float) $r_orders->review->rating;
                    $counter_for_restaurnt ++;
                }
                $avgRating_for_restaurnt = (float) ($sum_for_restaurnt / $counter_for_restaurnt);
                // dd($avgRating_for_restaurnt);
            }

            // Retrieve reviews for the food items in the order, grouped by food_id
            $orderDetailsFoodWise = OrderDetail::with('review')->whereIn('food_id', $orderFoodIds)
                ->whereNotNull('review_id')
                ->get()
                ->groupBy('food_id');

            // Return the food reviews or dump them for debugging
            foreach($orderDetailsFoodWise as $key => $orderDetails){
                $ratedFood  = Food::find($key);
                $sum = 0;
                $counter = 0;
                $avgRating = 0;
                foreach($orderDetails as $orderItem){
                    $sum += (float) $orderItem->review->rating;
                    $counter ++;
                }

                $avgRating = (float) ($sum / $counter);
                $ratedFood->rating = $avgRating;
                $ratedFood->save();
                // dd($avgRating);
            }
            DB::commit();
            return response()->json([]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['message'=> $th->getMessage()],500);

        }
    }

    public function makeReviewDm(Request $request){
        try {
            DB::beginTransaction();
            // Retrieve the order with its details using the order number

            $orderNO = $request->query('order_id');
            $review = $request->input('review');
            $rating = $request->input('rating');
            $deliverymanId = $request->input('deliveryman_id');

            $order = Order::with('details')->find($orderNO);

            if (!$order) {
                // Handle case where the order does not exist
                return response()->json(['error' => 'Order not found'], 404);
            }

            Auth::guard('customer')->user()->reviews()->create([
                'order_id' => $orderNO,
                'rating' => $rating,
                'review' => $review,
                'deliveryman_id' => $deliverymanId,
                'review_to' => 'deliveryman'
            ]);

            DB::commit();
            return response()->json([]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['message'=> $th->getMessage()],500);

        }
    }

    public function checkDmReview(Request $request) {
        $orderNO = $request->query('order_id');
        $result = Review::where('order_id',$orderNO)->whereNotNull('deliveryman_id')->get()->first();
        if($result){
            return response()->json(['review_found'=> true]);
        }else{
            return response()->json(['review_found' => false]);
        }
    }

    public function checkResReview(Request $request) {
        $orderNO = $request->query('order_id');
        $result = Review::where('order_id', $orderNO)->whereNull('deliveryman_id')->first();
        if ($result) {
            return response()->json(['review_found' => true]);
        } else {
            return response()->json(['review_found' => false]);
        }
    }

    public function testR($orderNO)
    {
        // Retrieve the order with its details using the order number
        $order = Order::with('details')->find($orderNO);

        if (!$order) {
            // Handle case where the order does not exist
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Get the restaurant ID from the order
        $restaurant_id = $order->restaurant_id;

        // Find all orders from the same restaurant that have a review
        $reviewedOrders = Order::with('review')
            ->where('restaurant_id', $restaurant_id)
            ->whereNotNull('review_id')
            ->get();

        if(isset($reviewedOrders[0])){
            $sum_for_restaurnt =0;
            $counter_for_restaurnt = 0;
            $avgRating_for_restaurnt = 0;
            foreach($reviewedOrders as $r_orders){
                $sum_for_restaurnt += (float) $r_orders->review->rating;
                $counter_for_restaurnt ++;
            }
            $avgRating_for_restaurnt = (float) ($sum_for_restaurnt / $counter_for_restaurnt);
            // dd($avgRating_for_restaurnt);
        }


        // Collect food item IDs from the current order's details
        $orderFoodIds = [];
        foreach ($order->details as $foodItem) {
            $orderFoodIds[] = $foodItem->food_id;
        }

        // Retrieve reviews for the food items in the order, grouped by food_id
        $orderDetailsFoodWise = OrderDetail::with('review')->whereIn('food_id', $orderFoodIds)
            ->whereNotNull('review_id')
            ->get()
            ->groupBy('food_id');

        // Return the food reviews or dump them for debugging
        foreach($orderDetailsFoodWise as $key => $orderDetails){
            $ratedFood  = Food::find($key);
            $sum = 0;
            $counter = 0;
            $avgRating = 0;
            foreach($orderDetails as $orderItem){
                $sum += (float) $orderItem->review->rating;
                $counter ++;
            }

            $avgRating = (float) ($sum / $counter);
            $ratedFood->rating = $avgRating;
            $ratedFood->save();
            dd($avgRating);
        }
    }


}
