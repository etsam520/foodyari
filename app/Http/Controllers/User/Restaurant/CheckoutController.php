<?php

namespace App\Http\Controllers\User\Restaurant;


use  App\Http\Controllers\User\Restaurant\CartHelper;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\Restaurant\apparatusReferral\ReferralProvider;
use App\Jobs\OrderPaymentVerifyJob;
use App\Jobs\User\ProcessOrderNotifications;
use App\Models\AdminFund;
use App\Models\BusinessSetting;
use App\Models\Customer;
use App\Models\DeliveryMan;
use App\Models\DiscountCoupon;
use App\Models\DiscountCouponUsed;
use App\Models\Food;
use App\Models\GatewayPayment;
use App\Models\GuestSession;
use App\Models\Order;
use App\Models\OrderCalculationStatement;
use App\Models\OrderDetail;
use App\Models\Restaurant;
use App\Models\RestaurantSchedule;
use App\Models\Wallet;
use App\Models\ZoneBusinessSetting;
use App\Services\JsonDataService;
use App\Services\MqttService;
use App\Services\Payments\PaymentGatewayFactory;
use Carbon\Carbon;
use Error;
use Faker\Extension\Helper;
use Google\Rpc\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\Coupons;
use PhpOffice\PhpSpreadsheet\Shared\IntOrFloat;
use PhpParser\Node\Expr\FuncCall;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Console\Helper\HelperSet;

class CheckoutController extends Controller
{

    protected $mqttService;


    public function index()
    {
        $userType = auth('customer')->check() ? 'customer' : 'guest';
        $user = null;
        $guest = null;
        if(auth('customer')->check()) {
            $user = auth('customer')->user();
        } else if(Helpers::guestCheck()) {
            $guest = Helpers::getGuestSession();
        } else {
            return redirect()->route('user.dashboard');  
        }
        if (!CartHelper::cartExist()) {
            return redirect()->route('user.dashboard')->with('warning', 'Empty Cart');
        }
        $carts = CartHelper::getCart();

        if (count($carts) < 1) {
            return redirect()->route('user.dashboard')->with('warning', 'Empty Cart');
        }

        // dd(Helpers::getOrderSessions($user->id));
        // dd(CartHelper::getCart($user?->id ?? null));
        $billing  = new BillingController($user,$guest, $userType);
        $billing->process();
        $billmakerData = $billing->billMaker();
        $billData = $billmakerData->customerBillData();

        // dd($billData);
        $restaurant = $billing->restaurant;
        if ($billData->distance > $billing->restaurant->radius) {
            return view('user-views.restaurant.checkout.out_of_range', compact('restaurant'));
        }
        if (!$restaurant) {
            CartHelper::clearCart();
            return back()->with('sweet_info', 'Food Not Found');
        }

        $user ? event(new \App\Events\User\Restaurant\ClearOrderSessions($user->id, true)) : null;// clearing the orders Sessions if for a loing time
        $user ? event(new \App\Events\User\Restaurant\ClearSavedCoupon($user->id, true)) : null; // clearing the coupons if for a loing time

        return view('user-views.restaurant.checkout.index', compact('restaurant', 'userType'));
    }

    public function billingSummery()
    {
       $userType = auth('customer')->check() ? 'customer' : 'guest';
       $user = null;
        $guest = null;
        if(auth('customer')->check()) {
            $user = auth('customer')->user();
        } else if(Helpers::guestCheck()) {
            $guest = Helpers::getGuestSession();
            // dd($guest);
        }
        // if(Session::has(''))

        if (isset(CartHelper::getCart()[0])) {
            try {
                $billing = new BillingController($user,$guest, $userType);
                $billing->process();

                $billmakerData = $billing->billMaker();
                $billData = $billmakerData->customerBillData();


                // dd($billmakerData->restaurantBillData()) ;

            } catch (\Throwable $th) {
                // dd($th);
                Log::error('Billing Summery Error: ' . $th->getMessage());
                return response()->json([
                    'view' => '',
                    'data' => null,
                ]);
            }


            return response()->json([
                'view' => view('user-views.restaurant.checkout._billing-summery', compact('billData'))->render(),
                'order_amount' => ceil($billData->billingTotal)
            ]);
        } else {
            return response()->json([
                'view' => '',
                'data' => null,
            ]);
        }
    }

    public function dmTips(Request $request)
    {
        $dm_tips = (float) $request->query('dm_tips');
        $userId = auth('customer')->user()->id;

        if (Helpers::isOrderSessionLock($userId)) {
            return response()->json([
                'message' => 'Order Session is Locked, Please try again later',
            ]);
        }


        if ($dm_tips > -1) {
            $_saved_dm_tips =  Helpers::getOrderSessions($userId, "dm_tips");

            if ($dm_tips != null) {
                if ($dm_tips == $_saved_dm_tips) {
                    DB::table('order_sessions')->updateOrInsert(
                        ['customer_id' => $userId],
                        ['dm_tips' => 0]
                    );
                    return response()->json([
                        'message' => " Removed Delivery Man Tips",
                        'checked' => false
                    ]);
                }
            }
            DB::table('order_sessions')->updateOrInsert(
                ['customer_id' => $userId],
                ['dm_tips' => $dm_tips]
            );
            return response()->json([
                'message' => Helpers::format_currency($dm_tips) . " Added For Delivery Man Tips",
                'checked' => true
            ]);
        } else {
            return response()->json([
                'message' => "Delivery Man Tips Not Set",
            ]);
        }
    }

    public function getCoupons(Request $request)
    {
        $today = Carbon::now()->toDateString();
        $customer = Session::get('userInfo');
        $cart = CartHelper::getCart();
        $cartFirstItem = $cart[0];
        $restaurant = Restaurant::with('zone')->find($cartFirstItem['restaurant_id']);
        $zone = $restaurant->zone;
        $coupons = DiscountCoupon::whereDate('expire_date', '>', $today)->isActive()
            ->when(isset($cart) && !empty($cart), function ($query) use ($restaurant, $zone, $customer) {
                $query->where(function ($query) use ($restaurant, $zone, $customer) {
                    // Check for restaurant-wise coupons
                    $query->where(function ($query) use ($restaurant, $customer) {
                        $query->where('coupon_type', 'restaurant_wise')
                            ->where('data', 'LIKE', "%{$restaurant->id}%")
                            ->orWhere('data', 'LIKE', '%all%')
                            ->where(function ($query) use ($customer) {
                                $query->whereJsonContains('customer_id', (string) $customer->id)
                                    ->orWhere('customer_id', 'LIKE', '%all%');
                            });
                    })
                        // Check for zone-wise coupons
                        ->orWhere(function ($query) use ($zone, $customer) {
                            $query->where('coupon_type', 'zone_wise')
                                ->where('data', 'LIKE', "%{$zone->id}%")
                                ->orWhere('data', 'LIKE', '%all%')
                                ->where(function ($query) use ($customer) {
                                    $query->whereJsonContains('customer_id', (string) $customer->id)
                                        ->orWhere('customer_id', 'LIKE', '%all%');
                                });
                        })
                        // Check for first delivery coupons
                        ->orWhere(function ($query) use ($customer) {
                            $query->where('coupon_type', 'first_order')
                                ->where(function ($query) use ($customer) {
                                    $query->whereJsonContains('customer_id', (string) $customer->id)
                                        ->orWhere('customer_id', 'LIKE', '%all%');
                                });
                        })
                        // Check for free delivery coupons
                        ->orWhere(function ($query) use ($customer) {
                            $query->where('coupon_type', 'free_delivery')
                                ->where(function ($query) use ($customer) {
                                    $query->whereJsonContains('customer_id', (string) $customer->id)
                                        ->orWhere('customer_id', 'LIKE', '%all%');
                                });
                        })
                        ->orWhere(function ($query) use ($customer) {
                            $query->where('coupon_type', 'Default')
                                ->where(function ($query) use ($customer) {
                                    $query->whereJsonContains('customer_id', (string) $customer->id)
                                        ->orWhere('customer_id', 'LIKE', '%all%');
                                });
                        });
                });
            })
            ->with(['used' => function ($query) use ($customer) {
                $query->whereHas('orders', function ($order) use ($customer) {
                    $order->where('customer_id', $customer->id);
                });
            }])->get();



        $couponsTOShow = [];
        // dd($coupons);

        foreach ($coupons as $coupon) {
            $limit = $coupon->used->count() ?? 0;
            if ($coupon->limit > $limit) {
                $couponsTOShow[] = $coupon;
            }
        }

        return response()->json([
            'view' => view('user-views.restaurant.checkout.coupon.choose', ['coupons' => $couponsTOShow])->render(),
        ]);
    }

    public function applyCoupons(Request $request)
    {
        try {
            // $customer = Session::get('userInfo');
           $customer = auth('customer')->user();


            $billing = new BillingController($customer, null, 'customer');
            $billing->process();

            $billmakerData = $billing->billMaker();
            // dd($billmakerData);
            $billData = $billmakerData->customerBillData();

            $today = Carbon::now()->toDateString();
            $cart = CartHelper::getCart();
            $cartFirstItem = $cart[0];
            $restaurant = Restaurant::with('zone')->find($cartFirstItem['restaurant_id']);
            $zone = $restaurant->zone;
            $appliedCoupons = Helpers::getOrderSessions($customer->id, "applied_coupons");

            if (is_array($appliedCoupons) && count($appliedCoupons) > 1) {
                throw new \Exception('Multiple coupons are not allowed. Only one coupon can be applied.');
            }

            $requestedCouponId = $request->query('coupon_id');
            $coupon = DiscountCoupon::whereDate('expire_date', '>', $today)
                ->when(isset($cart) && !empty($cart), function ($query) use ($restaurant, $zone, $customer) {
                    $query->where(function ($query) use ($restaurant, $zone, $customer) {
                        // Check for restaurant-wise coupons
                        $query->where(function ($query) use ($restaurant, $customer) {
                            $query->where('coupon_type', 'restaurant_wise')
                                ->where('data', 'LIKE', "%{$restaurant->id}%")
                                ->orWhere('data', 'LIKE', '%all%')
                                ->where(function ($query) use ($customer) {
                                    $query->whereJsonContains('customer_id', (string) $customer->id)
                                        ->orWhere('customer_id', 'LIKE', '%all%');
                                });
                        })
                            // Check for zone-wise coupons
                            ->orWhere(function ($query) use ($zone, $customer) {
                                $query->where('coupon_type', 'zone_wise')
                                    ->where('data', 'LIKE', "%{$zone->id}%")
                                    ->orWhere('data', 'LIKE', '%all%')
                                    ->where(function ($query) use ($customer) {
                                        $query->whereJsonContains('customer_id', (string) $customer->id)
                                            ->orWhere('customer_id', 'LIKE', '%all%');
                                    });
                            })
                            // Check for first delivery coupons
                            ->orWhere(function ($query) use ($customer) {
                                $query->where('coupon_type', 'first_order')
                                    ->where(function ($query) use ($customer) {
                                        $query->whereJsonContains('customer_id', (string) $customer->id)
                                            ->orWhere('customer_id', 'LIKE', '%all%');
                                    });
                            })
                            // Check for free delivery coupons
                            ->orWhere(function ($query) use ($customer) {
                                $query->where('coupon_type', 'free_delivery')
                                    ->where(function ($query) use ($customer) {
                                        $query->whereJsonContains('customer_id', (string) $customer->id)
                                            ->orWhere('customer_id', 'LIKE', '%all%');
                                    });
                            })
                            ->orWhere(function ($query) use ($customer) {
                                $query->where('coupon_type', 'Default')
                                    ->where(function ($query) use ($customer) {
                                        $query->whereJsonContains('customer_id', (string) $customer->id)
                                            ->orWhere('customer_id', 'LIKE', '%all%');
                                    });
                            });
                    });
                })
                ->with(['used' => function ($query) use ($customer) {
                    $query->whereHas('orders', function ($order) use ($customer) {
                        $order->where('customer_id', $customer->id);
                    });
                }])->find($requestedCouponId);

            if (!$coupon) {
                throw new \Exception('Coupon not found');
            }

            // $startDate = Carbon::parse($coupon->start_date);
            $expireDate = Carbon::parse($coupon->expire_date);
            $minPurchase = $coupon->min_purchase;

            if ($expireDate->isPast(Carbon::now())) {
                throw new \Exception('Coupon Expired');
            }

            if ($billData->sumOfFoodPrice <= $minPurchase) {
                throw new \Exception('Minimum Purchase Amount Should Be more than ' . Helpers::format_currency($minPurchase));
            }


            

            if (!in_array($requestedCouponId, array_column($appliedCoupons??[], 'id'))) {
                $usedSameCoupon = DiscountCouponUsed::where('discount_coupon_id', $coupon->id)
                    ->whereHas('orders', function ($query) use ($customer) {
                        $query->where('customer_id', $customer->id);
                    })->count();
                if ($usedSameCoupon > $coupon->limit) {
                    throw new \Exception('You have reached the coupon limit');
                }
                $appliedCoupons[] = $coupon;

                if (!Helpers::isOrderSessionLock($customer->id)) {

                    DB::table('order_sessions')->updateOrInsert(
                        ['customer_id' => auth('customer')->user()->id],
                        ['applied_coupons' => json_encode($appliedCoupons)]
                    );
                } else {
                    throw new \Exception('Order Session is Locked, Please try again later');
                }
            } else {
                throw new \Exception('Coupon already applied');
            }
            $billing = new BillingController($customer, null, 'customer');
            $billing->process();
            $billmakerData = $billing->billMaker();
            $billData = $billmakerData->customerBillData();
            return response()->json([
                'message' => "{$coupon->code} applied!",
                'saved' => Helpers::format_currency($billData->couponDiscountAmount) . " Saving With This Coupon",
                'applied' => view('user-views.restaurant.checkout.coupon.applied', compact('appliedCoupons'))->render(),
            ]);
        } catch (\Throwable $th) {
            // dd($th);
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    public function removeAppliedCoupon(Request $request, $id)
    {
        try {
            if (Helpers::isOrderSessionLock(auth('customer')->user()->id)) {
                throw new \Exception('Order Session is Locked, Please try again later');
            }
            self::removerAppliedCoupon($id);

            return back()->with('success', 'Coupon removed successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    private static function removerAppliedCoupon($id)
    {
        try {
            if (empty($id)) {
                throw new \Error('Coupon ID is required.');
            }
            $userId = auth('customer')->user()->id;

            $appliedCoupons = Helpers::getOrderSessions($userId, "applied_coupons");

            $key = array_search($id, array_column($appliedCoupons??[], 'id'));
            if ($key !== false) {
                unset($appliedCoupons[$key]);
                $appliedCoupons = array_values($appliedCoupons);

                DB::table('order_sessions')->updateOrInsert(
                    ['customer_id' => auth('customer')->user()->id],
                    ['applied_coupons' => json_encode($appliedCoupons)]
                );
            }

            return true;
        } catch (\Throwable $th) {
            return $th;
        }
    }



    public function cookingInstruction(Request $request)
    {
        $cooking_instruction = $request->json('instruction');
        if ($cooking_instruction) {
            DB::table('order_sessions')->updateOrInsert(
                ['customer_id' => auth('customer')->user()->id],
                ['cooking_instruction' => $cooking_instruction]
            );
            return response()->json([
                'message' => "Instruction Saved",
            ]);
        } else {
            return response()->json([
                'message' => "Instruction Not Set",
            ]);
        }
    }

    public function deliveryInstruction(Request $request)
    {

        $d_instruction = $request->input('d_instruction');
        if ($d_instruction != null) {

            DB::table('order_sessions')->updateOrInsert(
                ['customer_id' => auth('customer')->user()->id], // Condition
                ['delivery_instruction' => json_encode($d_instruction)] // Data to update/insert
            );
            return response()->json([
                'message' => "Instruction Saved",
            ]);
        } else {
            return response()->json([
                'message' => "Instruction Not Set",
            ]);
        }
    }

    public function scheduleOrder(Request $request)
    {
        try {
            $request->validate([
                'scheduled_date' => 'required|date|after_or_equal:today',
                'scheduled_time' => 'required|date_format:H:i'
            ]);

            $scheduledDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->scheduled_date . ' ' . $request->scheduled_time);
            
            // Check if scheduled time is at least 30 minutes from now
            if ($scheduledDateTime->lt(Carbon::now()->addMinutes(30))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order must be scheduled at least 30 minutes in advance'
                ], 422);
            }

            // Check if scheduled time is not more than 7 days from now
            if ($scheduledDateTime->gt(Carbon::now()->addDays(7))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order can only be scheduled up to 7 days in advance'
                ], 422);
            }

            DB::table('order_sessions')->updateOrInsert(
                ['customer_id' => auth('customer')->user()->id],
                ['order_scheduled_time' => $scheduledDateTime]
            );
                

            return response()->json([
                'status' => 'success',
                'message' => 'Order scheduled for ' . $scheduledDateTime->format('d M Y, h:i A'),
                'scheduled_time' => $scheduledDateTime->format('Y-m-d H:i:s')
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function removeSchedule(Request $request)
    {
        try {
            DB::table('order_sessions')
                ->where('customer_id', auth('customer')->user()->id)
                ->update(['order_scheduled_time' => null]);

            return response()->json([
                'status' => 'success',
                'message' => 'Order schedule removed'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function lovedOneDataStore(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'nullable|string',
                'phone' => 'nullable|numeric|digits:10'
            ]);

            // Extract the inputs
            $name = $request->name;
            $phone = $request->phone;
            $sendBill = $request->sendBill ?? "no";
            $sendBill_boolean = $sendBill === "yes";

            // If both name and phone are provided, cache the data
            if ($name !== null && $phone !== null) {
                $data = [
                    'name' => $name,
                    'phone' => $phone,
                    'sendBill' => $sendBill_boolean
                ];
                // Create a new order session if it doesn't exist
                DB::table('order_sessions')->updateOrInsert(
                    ['customer_id' => auth('customer')->user()->id],
                    ['loved_one_data' => json_encode($data)]
                );
            } else {
                // If data is not complete, remove the cache if it exists
                DB::table('order_sessions')->where('customer_id', auth('customer')->user()->id)->update(['loved_one_data' => null]);
            }

            // Return an empty success response
            return response()->json([], 200);
        } catch (\Throwable $th) {
            // Return a JSON response with the error message
            return response()->json(['message' => $th->getMessage()], 503);
        }
    }

    public function getLoveOneStoredData()
    {
        try {
            $loved_one_data = Helpers::getOrderSessions(auth('customer')->user()->id, "loved_one_data");

            return response()->json($loved_one_data, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 503);
        }
    }


    public function paymentOptions()
    {
        try {

            $now = Carbon::now();
            $user = auth('customer')->user();
            $billing  = new BillingController($user, null, 'customer');
            $billing->process();
            $billmakerData = $billing->billMaker();
            $billData = $billmakerData->customerBillData();
            
            // dd($billing->producIds);
            if(gettype($billing->producIds)== 'array' ? $billing->producIds : []):
                foreach($billing->producIds as $prodId):
                    $foodItem = Food::find($prodId);
                    if(!$foodItem->isAvailableNow()):
                        return back()->with('sweet_info',  $foodItem->name.' are not available now');
                    endif;
                endforeach;
            endif ;

            if ($billData->billingTotal == 0) {
                return redirect(route('user.dashboard'));
            }
            if ($billData->sumOfFoodPrice < $billData->restaurant->minimum_order) {
                return back()->with('info', "Minimum Food amount should be at least {$billing->restaurant->minimum_order}");
            }
            // DB::raw('TIMEDIFF(restaurant_schedule.opening_time,  "' . $now->format('H:i:s') . '") as time_remaining_to_open'),
            $restaurant_schedule = RestaurantSchedule::where('day', $now->format('l'))
                ->where('restaurant_schedule.restaurant_id', $billing->restaurant->id)
                ->leftJoin('restaurants', 'restaurant_schedule.restaurant_id', '=', 'restaurants.id')
                ->select(
                    'restaurant_schedule.opening_time',
                    'restaurant_schedule.closing_time',
                    'restaurant_schedule.id',
                    'restaurants.temp_close',
                    DB::raw('DATE_FORMAT(restaurant_schedule.opening_time, "%h:%i %p") as formatted_opening_time'),
                    DB::raw('DATE_FORMAT(restaurant_schedule.closing_time, "%h:%i %p") as formatted_closing_time'),
                    DB::raw('TIMEDIFF(restaurant_schedule.opening_time,  "' . $now->format('H:i:s') . '") as time_remaining_to_open'),
                    DB::raw('TIMEDIFF(restaurant_schedule.closing_time,  "' . $now->format('H:i:s') . '") as time_remaining_to_close'),
                    DB::raw('
                            CASE
                                WHEN TIME(restaurant_schedule.opening_time) < TIME(restaurant_schedule.closing_time)
                                    AND  "' . $now->format('H:i:s') . '" BETWEEN TIME(restaurant_schedule.opening_time) AND TIME(restaurant_schedule.closing_time)
                                THEN 1
                                WHEN TIME(restaurant_schedule.opening_time) > TIME(restaurant_schedule.closing_time)
                                    AND ( "' . $now->format('H:i:s') . '" >= TIME(restaurant_schedule.opening_time) OR  "' . $now->format('H:i:s') . '" <= TIME(restaurant_schedule.closing_time))
                                THEN 1
                                ELSE 0
                            END AS is_open_now
                        ')
                )
                ->first();
            // dd($restaurant_schedule);
            if ($restaurant_schedule->temp_close == true || $restaurant_schedule->is_open_now == false) {
                return back()->with('sweet_info', 'Restaurant Closed  Now');
            }

            // dd(($user->id));


            $wallet = Wallet::firstOrCreate(['customer_id' => $user->id]);
            // dd($wallet);
            return view('user-views.restaurant.checkout.partials.payment-options', compact('billData', 'wallet'));
        } catch (\Throwable $th) {
            return view('user-views.Error.errorhandle-page', ['message' => $th->getMessage()]);
        }
    }

    public function placeOrder_via_cashOrWallet(Request $request)
    {
        try {

            if (count(CartHelper::getCart()) < 1) {
                throw new \Exception(__('messages.cart_empty_warning'));
            }

            if (Session::has('address') && !$request->customer_id) {
                throw new \Exception(__('messages.no_customer_selected'));
            }

            $wallet = filter_var($request->wallet ?? 0, FILTER_VALIDATE_FLOAT);
            $cash = $request->cash ?? 0;
            $onlinePayment = $request->online ?? 0;
            // $user = Session::get('userInfo');
            $user = auth('customer')->user();


            DB::beginTransaction();
            $user = auth('customer')->user();
            $billing = new BillingController($user, null, 'customer');
            $billing->process();
            $billmakerData = $billing->billMaker();
            $billData = $billmakerData->customerBillData();


            $cash_to_collect = $billData->billingTotal ?? 0;
            if (ceil($cash + $wallet) !=  ceil($billData->billingTotal)) {
                throw new \Exception('Amount Mismatched');
            }

            if ($onlinePayment > 0) {
                return redirect()->route('onlinepay'); // Set online URL
            } elseif ($wallet > 0) {
                $customerWallet = Wallet::where('customer_id', $user->id)->first();
                //dd($customerWallet);
                $adminFund = AdminFund::getFund();
                if ($wallet > $customerWallet->balance) {  // Checking wallet available balance
                    throw new \Exception('Insufficient Wallet Amount');
                } elseif (ceil($wallet) > ceil($billData->billingTotal) ) {
                    throw new \Exception('Selected Amount Can\'t be more of Order Amount');
                }
                $customerWallet->balance -= $wallet;
                $customerWallet->save(); // Deducting wallet balance
                $adminFund->balance += $wallet;
                $adminFund->save(); // Adding it to admin fund

                $cash_to_collect -= $wallet; // Deducting cash to collect by wallet amount
                $p_method =  $cash > 0 ? 'cash&wallet' : 'wallet';
            } else {
                $p_method = 'cash';
            }

            

            DB::table('order_sessions')->where('customer_id', $user->id)->update([
                'cash_to_collect' => $cash_to_collect,
                'payment_method' => $p_method
            ]);

            $order = self::placeOrderProcess($user);

            if ($wallet > 0) {
                $customerWallet->walletTransactions()->create([
                    'amount' => $wallet,
                    'type' => 'paid',
                    'customer_id' => $user->id,
                    'remarks' => "Amount deducted For the Order No. #{$order->id}",
                ]);

                $adminFund->txns()->create([
                    'amount' => $wallet,
                    'txn_type' => 'received',
                    'received_from' => 'customer',
                    'customer_id' => $user->id,
                    'remarks' => "Amount received from Mr/Mrs. {$user->f_name} wallet for the order no: #{$order->id}",
                ]);
                $customerWallet->save();
                $adminFund->save();
            }

            DB::commit();
            $message = "Order Placed Successfully";
            
            // Determine redirect URL based on order type
            if($order->order_status == 'scheduled'){
                $url = route('user.restaurant.scheduled-order-details',['orderId' => $order->id]);
            } else {
                $url = route('user.restaurant.order-trace', ['order_id' => $order->id, 'order_type' => 'current']);
            }
            
            // Show success page with auto-redirect and back history blocking
            return view('user-views.success.order-success', compact('message', "url"));
 
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return back()->with('error', $e->getMessage());
        }
    }

    public function placeOrder_via_onlineOrWallet(Request $request)
    {
        try {
            if (count(CartHelper::getCart()) < 1) {
                throw new \Exception(__('messages.cart_empty_warning'));
            }

            if (Session::has('address') && !$request->customer_id) {
                throw new \Exception(__('messages.no_customer_selected'));
            }

            $wallet = filter_var($request->wallet ?? 0, FILTER_VALIDATE_FLOAT);

            $onlinePayment = $request->online ?? 0;
            DB::beginTransaction();
            $user = auth('customer')->user();
            $billing = new BillingController($user, null, 'customer');
            $billing->process();
            $billmakerData = $billing->billMaker();
            $billData = $billmakerData->customerBillData();


            if (round($onlinePayment + $wallet) !=  round($billData->billingTotal)) {
                throw new \Exception('Amount Mismatched');
            }

            $cash_to_collect = 0;
            $payment_method = 'online';
            $pay_from_wallet = 0;


            if ($wallet > 0) {
                $customerWallet = Wallet::where('customer_id', $user->id)->first();
                if ($wallet > $customerWallet->balance) {  // Checking wallet available balance
                    throw new \Exception('Insufficient Wallet Amount');
                } elseif ($wallet > $billData->billingTotal) {
                    throw new \Exception('Selected Amount Can\'t be more of Order Amount');
                }
                $pay_from_wallet = $wallet;
                $payment_method = 'online&wallet';
            } else {
                $pay_from_wallet = 0;
            }

            DB::table('order_sessions')->where('customer_id', $user->id)->update([
                'pay_from_wallet' => $pay_from_wallet,
                'cash_to_collect' => 0,
                'payment_method' => $payment_method ?? 'online',
            ]);

            Helpers::lockCart($user->id, true); // Locking the cart to prevent multiple orders
            Helpers::lockOrderSession($user->id, true); // Locking the order session to prevent multiple orders

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function placeOrderProcess(Customer $user)
    {
        try {
            // $user = auth('customer')->user();
            $billing  = new BillingController($user, null, 'customer');
            $billing->process();
            $billmakerData = $billing->billMaker();
            $billData = $billmakerData->customerBillData();


            $order_sessions = Helpers::getOrderSessions($user->id);

            $cash_to_collect = $order_sessions['cash_to_collect'] ?? $billData->billingTotal;
            $payment_method = $order_sessions['payment_method'] ?? 'cash';

            DB::beginTransaction();
            

            $order = new Order();
            $order->id = 100000 + Order::all()->count() + 1;
            if (Order::find($order->id)) {
                $order->id = Order::latest()->first()->id + 1;
            }
            $order->payment_status = $cash_to_collect == 0 ? 'paid' : 'unpaid';

            if (strpos($payment_method, 'online') !== false) {
                $order->payment_status = 'unpaid';
            }

            $order->order_type = 'delivery';
            $order->referral_user_reward_id = $billmakerData->referralReward->reward?->id;

            $order->distance = number_format($billData->distance, 2, '.', '');
            $order->restaurant_id = $billData->restaurant->id;

            $order->customer_id =   $billData->userId;
            $order->delivery_address = $billData->deliveryAddress;
            $order->checked = 1;
            $order->created_at = now();

            
            // Set scheduled time if available, otherwise use current time
            $scheduledTime = $order_sessions['order_scheduled_time'] ?? null;
            if ($scheduledTime) {
                $order->pending = null ;
                $order->order_status = 'scheduled';
                $order->schedule_at = Carbon::parse($scheduledTime);
                $order->scheduled = 1; // Mark as scheduled

            } else {
                $order->pending = now();
                $order->schedule_at = null;
                $order->scheduled = 0; // Mark as immediate
            }
            
            $order->updated_at = now();
            $order->otp = rand(100000, 999999);
            $order->order_to = $billData->order_to;
            $order->share_bill = $billData->sendBill;

            $order->share_token = self::generateShareOfferToken();

            $order->order_amount = $billData->billingTotal;
            $order->payment_method = $payment_method;


            $order_details = $billing->order_details;

            $order->cash_to_collect = $cash_to_collect;

            $order->cooking_instruction =  $order_sessions['cooking_instruction']?? null;

            $order->delivery_instruction = json_encode($order_sessions['delivery_instruction']?? null);

            if ($order->save()) {

                foreach ($order_details as $key => $item) {
                    $order_details[$key]['order_id'] = $order->id;
                }
                /*=========//save order details //==========*/
                OrderDetail::insert($order_details);
                /*=========//save order Calculation Statement //==========*/

                OrderCalculationStatement::insert([
                    'order_id' => $order->id,
                    'customerData' => json_encode($billData),
                    'restaurantData' => json_encode($billmakerData->restaurantBillData()),
                    'adminData' => json_encode($billmakerData->adminBillData()),
                ]);
                /*=========//save coupon used //==========*/
                $usedCoupons = [];
                foreach ($billData->couponDetails as $coupon) {
                    $updateCoupon = DiscountCoupon::find($coupon['id']);
                    $updateCoupon->total_uses += 1;
                    $updateCoupon->save();
                    $usedCoupons[] = [
                        'discount_coupon_id' => $coupon['id'],
                        'order_id' => $order->id,
                        'used_at' => now()
                    ];
                }
                DiscountCouponUsed::insert($usedCoupons);
                

                // $billing->clearCouponCache();

                /*=========// sending Notification //==========*/

                try {
                    // Process order notifications (handles both scheduled and immediate orders)
                    ProcessOrderNotifications::dispatch($order, $billData);
                    // For immediate orders, trigger the OrderPlaced event immediately
                    // For scheduled orders, the event will be triggered by the scheduled notification job
                    event(new \App\Events\OurLovedOneSessionStore($order, $order_sessions));
                    if ($order->scheduled == 0) event(new \App\Events\OrderPlaced($order));

                } catch (\Throwable $th) {
                    Log::error('Error sending order notification: ' . json_encode($th->getMessage()) . json_encode($th->getTrace()) . json_encode($th->getFile()) . json_encode($th->getLine()));
                }
                
                
                CartHelper::clearCart($billData->userId);
                DB::table('order_sessions')->where('customer_id', $user->id)->delete();
                DB::commit();

                if($scheduledTime != null){
                    $order = Order::find($order->id);
                    $order->update(['pending' => null, 'order_status' => 'scheduled']);
                }
                
                return $order;
            } else {
                throw new \Error(__('messages.failed_to_place_order'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            throw $e;
        }
    }



    public static function sendOrderNotification(Order $order, $billData)
    {
        try {
            // $order = $order->find($orderID);
            // $mqttService = new MqttService();
            $user = Customer::find($billData->userId);;

            if ($order->order_type = 'delivery') {

                $deliverymen = DeliveryMan::where('zone_id', $billData->restaurant->zone_id)
                    ->where('type', 'admin')->get();
            
                $deliveryAddress = json_decode($order->delivery_address, true);
                $bgImage = asset('images/restaurant/staticmap.png');
                if (isset($deliveryAddress['position']) && isset($deliveryAddress['position']['lat']) && isset($deliveryAddress['position']['lon']) && $billData->restaurant->latitude && $billData->restaurant->longitude) {
                    // $bgImage = "https://maps.googleapis.com/maps/api/staticmap?size=300x600&&markers=color:red|label:P|{$deliveryAddress['position']['lat']},{$deliveryAddress['position']['lon']}&key=" . env('GOOGLE_MAPS_API_KEY');
                    $bgImage = "https://maps.googleapis.com/maps/api/staticmap?size=300x600&&markers=color:red|label:P|{$deliveryAddress['position']['lat']},{$deliveryAddress['position']['lon']}&markers=color:blue|label:R|{$billData->restaurant->latitude},{$billData->restaurant->longitude}&key=" . env('GOOGLE_MAPS_API_KEY');
                }
                $notification = [
                    'type' => 'Manual',
                    'image' => '',
                    'subject' => ZoneBusinessSetting::getSettingValue('customer_order_place_message', $order->getZoneId()) ?? "Order Placed Successfully",
                    'message' => "Order no  #$order->id",
                    'order_id' => "{$order->id}",
                    'order_status' => 'pending',
                    'audio_link' => asset('sound/order-received.mp3'),
                    'delivery_address' => $deliveryAddress['stringAddress'] ?? null,
                    'delivery_lat' =>  $deliveryAddress['position']['lat'] ?? null,
                    'delivery_lng' => $deliveryAddress['position']['lon'] ?? null,
                    'restaurant_address' => '',
                    'restaurant_lat' => $billData->restaurant->latitude ?? null,
                    'restaurant_lng' => $billData->restaurant->longitude ?? null,
                    'restaurant_name' => $billData->restaurant->name ?? null,
                    'customer_name' => $user->f_name . ' ' . $user->l_name ?? 'foodyari user',
                    'order_accept_link' => route('deliveryman.admin.order-confirmation', ['order_id' => $order->id, 'status' => 'accept']),
                    'order_reject_link' => route('deliveryman.admin.order-confirmation', ['order_id' => $order->id, 'status' => 'reject']),
                    'background_image' => $bgImage,
                ];
                if ($deliverymen) {
                    // dd($deliverymen);

                    $message = json_encode($notification);
                    foreach ($deliverymen as $deliveryman) {
                        Helpers::sendOrderNotification($deliveryman, $notification);
                        $topic = 'foodyari_givni_order_data_' . $deliveryman['id'];
                        // $mqttService->publish($topic, $message);
                        // $dmData = new JsonDataService($deliveryman->id);

                        // if($dmData->active){

                        // }
                    }
                    // Helpers::sendOrderNotification($deliverymen, $notification);

                }
                // $adminNotificationPermission = DB::table('business_settings')->where('key', 'admin_order_notification')->first();
                $restaurant  = Restaurant::find($order->restaurant_id);
                // if($adminNotificationPermission->value != null){

                // }

                Helpers::sendOrderNotification(Helpers::getAdmin(), $notification);
                Helpers::sendOrderNotification($restaurant, $notification);
                $message = json_encode($notification);
                $topic = 'foodyari_givni_order_data_' . $restaurant->id;
                // $v_check->publish($topic, $message);
                $topic = 'foodyari_givni_order_data_' . Helpers::getAdmin()->id;
                // $mqttService->publish($topic, $message);

                // Publish message to the MQTT broker
            }

            if ($user) {
                $notification = [
                    'type' => 'Manual',
                    'subject' => ZoneBusinessSetting::getSettingValue('customer_order_place_message', $restaurant->zone_id) ?? "Order Placed Successfully",
                    'message' => "Order no  #$order->id",
                ];
                Helpers::sendOrderNotification($user, $notification);
            }
        } catch (\Throwable $th) {
            log::error($th);
            //Log::error('Error sending order from func notification: ' .json_encode( $th->getMessage()) . json_encode($th->getTrace()). json_encode($th->getFile()). json_encode($th->getLine()));
        }
    }


    public function orderPaymentOnline(Request $request)
    {
        //last update 17:36 14:07/2025
        try {
            $request->validate([
                'orderAmount' => 'required|numeric|min:1',
                'gateway' => 'required_if:amount,notnull|in:phonepe,gpay,paytm'
            ]);
            $user = Session::get('userInfo');

            $orderDetails = [
                'merchant_txn_id' =>  call_user_func_array(
                    config('payment.merchant_txn_id'),
                    [$request->input('gateway'), "O"]
                ),
                'amount' => $request->input('online'),
                'email' => $user->email,
                'phone' => $user->phone,
                'gateway' => $request->input('gateway'),
            ];
            DB::beginTransaction();
            // dd($request->all());
            // Create the order using the payment gateway
            $this->placeOrder_via_onlineOrWallet($request);


            GatewayPayment::create([
                'amount' => $request->input('online'),
                'merchant_txn_id' => $orderDetails['merchant_txn_id'],
                'gateway' => $orderDetails['gateway'],
                'assosiate' => 'customer',
                'assosiate_id' => $user->id,
                'payload' => json_encode($orderDetails),
                'details' => json_encode([]),
            ]);


            DB::table('order_sessions')->where('customer_id', $user->id)->update([
                'gateway_data' => json_encode($orderDetails)
            ]);


            $paymentGateway = PaymentGatewayFactory::make($request->input('gateway')); // 'cashfree', 'phonepe', etc.
            $queryString = array_filter($orderDetails, function ($key) {
                return in_array($key, ['gateway', 'merchant_txn_id']);
            }, ARRAY_FILTER_USE_KEY);
            $queryString = http_build_query($queryString);
            $orderDetails['returnUrl'] = route('user.restaurant.handle-order-payment-online-callback', $queryString);




            $response = $paymentGateway->createOrder($orderDetails);
            DB::commit();
            // return json_encode($response);

            // Handle the response
            if ($response->status === "OK") {
                // OrderPaymentVerifyJob::dispatch()->delay(now()->addMinute());
                // OrderPaymentVerifyJob::dispatch();
                return redirect($response->paymentLink);
            } else {
                return back()->withErrors(['error' => $response->message]);
            }
        } catch (\Throwable $th) {
            Session::remove('success');
            DB::rollBack();
            $message = $th->getMessage();
            return view('user-views.Error.errorhandle-page', compact('message'));
        }
    }

    public function handleOrderPaymentOnlineCallback(Request $request)
    {
        try {

            // $user = Session::get('userInfo');
            $user = auth('customer')->user();
            $paymentGateway = PaymentGatewayFactory::make($request->input('gateway')); // 'cashfree', 'phonepe', etc.

            $response = $paymentGateway->handleCallback($request->all());
            if ($response->payment_status == 'success') {
                $order_session = DB::table('order_sessions')->where('customer_id', $user->id)->first();
                if (!empty($order_session) && !empty($order_session->gateway_data)) {
                    $getWayTXN = GatewayPayment::where('merchant_txn_id', $request['merchant_txn_id'])->first();
                    $order = self::placeOrderProcess($user);
                    $getWayTXN->details = json_encode(['order_id' => $order->id]);
                    $getWayTXN->save();
                    self::online_txn_sattlement($response, $order, $user, $order_session);
                } else {
                    $order = Order::where('customer_id', $user->id)->latest()->get('id')->first();
                }
                Session::flash('success', __('messages.order_placed_successfully'));
                
                // Determine redirect URL based on order type
                if($order->order_status == 'scheduled'){
                    $redirectUrl = route('user.restaurant.scheduled-order-details',['orderId' => $order->id]);
                    $isScheduled = true;
                } else {
                    $redirectUrl = route('user.restaurant.order-trace', ['order_id' => $order->id, 'order_type' => 'current']);
                    $isScheduled = false;
                }
                
                return view('user-views.restaurant.order-success', compact('redirectUrl', 'isScheduled'))->with('orderId', $order->id);
            } elseif ($response->payment_status == 'failed') {
                Helpers::unlockCart($user->id); // Unlock the cart
                Helpers::unlockOrderSession($user->id); // Unlock the order session
                throw new \Error($response->responseCode);
            } elseif ($response->payment_status == 'pending') {
                return response()->route('user.dashboard')->with('error', 'Process Pending Please Contact Our Support Team');
            }
        } catch (\Throwable $th) {

            Session::remove('success');
            // $message = $th->getMessage();
            $message = "Relax, We'll ensure you don't get hungry";
            $url = route('user.restaurant.payment-options');
            return view('user-views.Error.errorhandle-page', compact('message', "url"));
        }
    }

    public static function online_txn_sattlement($paidTxn, $order, $user, $order_session)
    {
        try {
            $order = Order::find($order->id);
            $amount = $paidTxn->amount;
            $wallet = (float) $order_session->pay_from_wallet ?? 0.0;

            $adminFundRemarks =  Helpers::format_currency($amount) . " Received From {$user->f_name}, For the Order no: #{$order->id} , Transaction No : {$paidTxn->txn_id} , using " . strtoupper($paidTxn->gateway);

            DB::beginTransaction();

            if ($wallet > 0) {
                $customerWallet = Wallet::where('customer_id', $user->id)->first();
                $customerWallet->balance -= $wallet; // Adding wallet balance
                $customerWallet->save();
                $customerWallet->walletTransactions()->create([
                    'amount' => $wallet,
                    'type' => 'paid',
                    'customer_id' => $user->id,
                    'remarks' => "Order no: #{$order->id} , " . Helpers::format_currency($wallet) . " Deducted and remaing amount " . Helpers::format_currency($amount) . " Paid by Transaction No : {$paidTxn->txn_id} , using " . strtoupper($paidTxn->gateway),
                ]);


                $adminFundRemarks = Helpers::format_currency($amount + $wallet) . " Received for the Order no: #{$order->id} From {$user->f_name},
                    where in " . Helpers::format_currency($wallet) . " from wallet and remaing amount " . Helpers::format_currency($amount) . ", Transaction No : {$paidTxn->txn_id} , using " . strtoupper($paidTxn->gateway);
            }
            $adminFund = AdminFund::getFund();
            $adminFund->balance += ((float) $wallet + (float) $amount); // Adding it to admin fund
            $adminFund->save();

            $adminFund->txns()->create([
                'amount' => ((float) $wallet + (float) $amount),
                'txn_type' => 'received',
                'received_from' => 'customer',
                'customer_id' => $user->id,
                'remarks' => $adminFundRemarks,
            ]);


            $order->payment_status = 'paid';
            $order->save();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private static function generateShareOfferToken($length = 32)
    {
        // Generate a cryptographically secure random string
        $token = bin2hex(random_bytes($length / 2)); // Convert binary to hexadecimal
        return $token;
    }

    /**
     * Apply referral discount during checkout
     */
    public function applyReferralDiscount(Request $request)
    {
        try {
            $customer = auth('customer')->user();
            $rewardId = $request->query('reward_id');
            
            DB::table('order_sessions')->where('customer_id', $customer->id)->update([
                'referral_user_reward_id' => $rewardId
            ]);

            $billing  = new BillingController($customer, null, 'customer');
            $billing->process();
            $billmakerData = $billing->billMaker();
            $billData = $billmakerData->customerBillData();
            

            $applied_reward = array_filter((new ReferralProvider($customer->id))->getRewards(), function ($reward) use ($rewardId) {
                return $reward['id'] == $rewardId;
            });


            return response()->json([
                'success' => true,
                'message' => 'Referral discount applied successfully',
                'discount_amount' => $billData->referralDiscountAmount ?? 0,
                'formatted_amount' => Helpers::format_currency(200),
                'reward' => count($applied_reward) > 0 ? array_values($applied_reward)[0]: null
            ]);

        } catch (\Exception $e) {
            Log::error('Referral discount application failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply referral discount'
            ], 500);
        }
    }

    /**
     * Remove applied referral discount
     */
    public function removeReferralDiscount(Request $request)
    {
        try {
            $customer = auth('customer')->user();
            
            // Remove referral discount from order session
            DB::table('order_sessions')->where('customer_id', $customer->id)->update([
                'referral_user_reward_id' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Referral discount removed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Referral discount removal failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove referral discount'
            ], 500);
        }
    }

    /**
     * Check if referral discount is currently applied
     */
    public function checkReferralDiscountStatus(Request $request)
    {
        try {
            $user = auth('customer')->user();
            if (!$user) {
                return response()->json(['status' => false, 'applied' => false]);
            }

            $rewardId = Helpers::getOrderSessions($user->id, 'referral_user_reward_id');
            $fetched_reward = (new ReferralProvider($user->id))->getRewards();
            $applied_reward = array_filter($fetched_reward, function ($reward) use ($rewardId) {
                return $reward['id'] == $rewardId;
            });
            // dd($fetched_reward);

            Log::info('Referral discount status check: ' . ($rewardId ? "Applied" : "Not Applied"));

            if (!empty($rewardId)) {
                return response()->json([
                    'status' => true,
                    'applied' => true,
                    'reward_id' => $rewardId,
                    'reward' => count($applied_reward) > 0 ? $applied_reward[0]: null,
                    'is_reward' => true
                ]);
            }

            return response()->json([
                'status' => true,
                'applied' => false,
                'is_reward' => count(array_filter(
                    $fetched_reward,
                    fn($reward) => !preg_match('/cashback/i', $reward['reward_type'] ?? '')
                )) > 0
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'applied' => false]);
        }
    }
}
