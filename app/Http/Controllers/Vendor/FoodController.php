<?php

namespace App\Http\Controllers\Vendor;

// use App\Http\Controllers\Admin\Food;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use App\Models\RestaurantMenu;
use App\Models\RestaurantSubMenu;
use App\Models\Subcategory;
use App\Models\Tag;
use Carbon\Carbon;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Str;



class FoodController extends Controller

{
    public function index()
    {
        return view('vendor-views.food.index');
    }

    public function edit($id)
    {
        $food = Food::with(['category', 'tags', 'menu'])->find($id);
        $restaurantId = Session::get('restaurant')->id;
        $categories = Category::isActive(true)->latest()->get();

        if (!$food) {
            return back()->with('error', 'Food Not Found');
        }
        return view('vendor-views.food.edit', compact('food', 'categories'));
    }

    public function delete($id)
    {
        $food = Food::find($id);
        if (!$food) {
            return back()->with('error', 'Food Not Found');
        }
        Helpers::deleteFile('product/', $food->image);
        $food->delete();
        return back()->with('success', "food Deleted");
    }

    public function list()
    {
        $restaurantId = Session::get('restaurant')->id;
        $foods = Food::with(
            ['category:id,name', 'menu:id,name', 'submenu:id,name']
        )->where('restaurant_id', $restaurantId)
            ->latest()->paginate(200);
        $food_count = Food::where('restaurant_id', $restaurantId)
            ->select([
                DB::raw('count(*) as food_count'),
                DB::raw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_count'),
                DB::raw('SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive_count'),
            ])
            ->first();
        $data = [
            'foods' => $foods,
            'counts' => $food_count->toArray(),
        ];
        // return $foods;
        return view('vendor-views.food.list', compact('data'));
    }


    public function store(Request $request)
    {
        // dd($request->post());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:191',

            'category_id' => 'nullable',
            'restaurant_menu_id' => 'required',
            'image' => 'nullable|max:2048',
            'restaurant_price' => 'nullable|numeric',
            'admin_margin' => 'nullable|numeric',
            'packing_charge' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_by' => 'nullable',
            'discount_type' => 'nullable',
            'description.*' => 'max:1000',
            'food_type' => 'required'
        ], [
            'description.*.max' => __('messages.description_length_warning'),
            'category_id.required' => __('messages.category_required'),
            'food_type.required' => __('messages.item_type_is_required')
        ]);
        $foodPrice = $request->isCustomize != 1 ? ((int) $request->restaurant_price + (int) $request->admin_margin) : 0;
        $restaurant_price = $request->isCustomize != 1 ? $request->restaurant_price  : 0;
        if ($request->isCustomize != 1) {
            if ($request['discount_type'] == 'percent') {
                $dis = ($foodPrice / 100) * $request['discount'];
            } else {
                $dis = $request['discount'];
            }
            if ($foodPrice <= $dis && $foodPrice > 0) {
                return redirect()->back()->withErrors(__('messages.discount_can_not_be_more_than_or_equal'))->withInput();
            }

            if (($foodPrice <= $dis && $foodPrice > 0) || $validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        $tag_ids = [];
        if ($request->tags != null) {
            $tags = explode(",", $request->tags);
            foreach ($tags as $value) {
                $value = trim($value);
                $tag = Tag::firstOrCreate(['tag' => $value]);
                $tag_ids[] = $tag->id;
            }
        }

        $food = new Food();
        $food->name = $request->name;

        $category = [];

        if ($request->filled('category_id')) {
            $category[] = [
                'id' => $request->category_id,
                'position' => 1,
            ];
        }




        $food->category_ids = json_encode($category);
        $food->category_id = $request->category_id ?? null;
        $food->description =  $request->description;
        $food->isRecommended = $request->isRecommended ?? 0;
        $food->restaurant_menu_id = $request->restaurant_menu_id;
        $food->restaurant_submenu_id = $request->restaurant_submenu_id ?? null;


        $variations = [];
        if ($request->has('options')) {
            foreach ($request->options as $option) {
                $temp_variation = [
                    'name' => $option['name'],
                    'type' => $option['type'],
                    'min' => $option['min'] ?? 0,
                    'max' => $option['max'] ?? 0,
                    'required' => $option['required'] ?? 'off',
                ];

                if ($temp_variation['min'] > 0 && ($temp_variation['min'] > $temp_variation['max'])) {
                    return redirect()->back()->withErrors(__('messages.minimum_value_can_not_be_greater_then_maximum_value'))->withInput();
                }

                if (!isset($option['values'])) {
                    return redirect()->back()->withErrors(__('messages.please_add_options_for') . $option['name'])->withInput();
                }
                if ($option['type'] == "multi") {
                    if ($temp_variation['min'] > 0 && ($temp_variation['max'] < count($option['values']))) {
                        return redirect()->back()->withErrors('Options Can\'t be Greater Than Max')->withInput();
                    }
                }

                $temp_value = [];

                foreach ($option['values'] as $value) {
                    $temp_option = [
                        'optionPrice' => $value['optionPrice']
                    ];
                    if (isset($value['label'])) {
                        $temp_option['label'] = $value['label'];
                    }
                    if (isset($value['optionMargin'])) {
                        $temp_option['optionMargin'] = $value['optionMargin'];
                    }
                    $temp_value[] = $temp_option;
                }

                $temp_variation['values'] = $temp_value;
                $variations[] = $temp_variation;
            }
        }
        // dd($request->isCustomize);

        //combinations end

        $food->variations =  json_encode($variations);
        $food->price = $request->isCustomize == 1 ? 0 : $foodPrice;
        $food->admin_margin = $request->isCustomize == 1 ?  $request->admin_margin : 0;
        $food->restaurant_price = $restaurant_price;
        $food->isCustomize = !empty($variations) ? true : false;
        $food->image = $request->hasFile('image') ? Helpers::uploadFile($request->file('image'), 'product/') : null     ;
        $food->available_time_starts = $request->available_time_starts;
        $food->available_time_ends = $request->available_time_ends;
        $food->discount = $request->discount_type == 'amount' ? $request->discount : $request->discount;
        $food->discount_type = $request->discount_type;
        $food->discount_by = $request->discount_by;
        $food->packing_charge =  $request->packing_charge ?? 0;

        $food->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
        $food->add_ons = $request->has('addon_ids') ? json_encode($request->addon_ids) : json_encode([]);
        $food->restaurant_id = Session::get('restaurant')->id;
        $food->type = Helpers::getFoodType($request->food_type);
        $food->save();
        // dd($food);
        $food->tags()->sync($tag_ids);

        return redirect()->back()->with('success', 'Food Added');
    }




    public function getFoodsZoneWise(Request $request)
    {
        try {
            $zone_id = $request->get('zone_id');

            if (empty($zone_id)) {
                return response()->json(['error' => 'Zone ID is required.'], 400);
            }
            $products = Food::whereHas('restaurant', function ($query) use ($zone_id) {
                $query->where('zone_id', $zone_id);
            })
                ->with('restaurant')
                ->orderBy('id', 'DESC')->get();

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:3|max:191',

                'food_id' => 'required',
                'category_id' => 'nullable',
                'restaurant_menu_id' => 'required',
                'image' => 'nullable|max:2048',
                'restaurant_price' => 'nullable|numeric',
                'admin_margin' => 'nullable|numeric',
                'packing_charge' => 'nullable|numeric',
                'discount' => 'required|numeric|min:0',
                'discount_by' => 'nullable',
                'discount_type' => 'nullable',
                'description.*' => 'max:1000',
                'food_type' => 'required'
            ], [
                'description.*.max' => __('messages.description_length_warning'),
                'category_id.required' => __('messages.category_required'),
                'food_type.required' => __('messages.item_type_is_required')
            ]);

            // dd($validator);
            $foodPrice = $request->isCustomize != 1 ? ((int) $request->restaurant_price + (int) $request->admin_margin) : 0;
            $restaurant_price = $request->isCustomize != 1 ? $request->restaurant_price  : 0;


            if ($request->isCustomize != 1) {

                if ($request['discount_type'] == 'percent') {
                    $dis = ($foodPrice / 100) * $request['discount'];
                } else {
                    $dis = $request['discount'];
                }
                if ($foodPrice <= $dis) {
                    return redirect()->back()->withErrors(__('messages.discount_can_not_be_more_than_or_equal'))->withInput();
                }

                if ($foodPrice <= $dis  || $validator->fails()) {
                    return back()
                        ->withErrors($validator)
                        ->withInput();
                }
            }


            $tag_ids = [];
            if ($request->tags != null) {
                $tags = explode(",", $request->tags);
            }
            if (isset($tags)) {
                foreach ($tags as $key => $value) {
                    $tag = Tag::firstOrNew(
                        ['tag' => $value]
                    );
                    $tag->save();
                    array_push($tag_ids, $tag->id);
                }
            }

            $food = Food::find($request->food_id);
            $food->name = $request->name;

            $category = [];

            if ($request->filled('category_id')) {
                $category[] = [
                    'id' => $request->category_id,
                    'position' => 1,
                ];
            }

            $food->restaurant_menu_id = $request->restaurant_menu_id;
            $food->restaurant_submenu_id = $request->restaurant_submenu_id ?? null;
            $food->category_id = $request->category_id;
            $food->description =  $request->description;
            $food->isRecommended = $request->isRecommended ?? 0;




            $variations = [];
            if ($request->has('options')) {
                foreach ($request->options as $option) {
                    $temp_variation = [
                        'name' => $option['name'],
                        'type' => $option['type'],
                        'min' => $option['min'] ?? 0,
                        'max' => $option['max'] ?? 0,
                        'required' => $option['required'] ?? 'off',
                    ];

                    if ($temp_variation['min'] > 0 && ($temp_variation['min'] > $temp_variation['max'])) {
                        return redirect()->back()->withErrors(__('messages.minimum_value_can_not_be_greater_then_maximum_value'))->withInput();
                    }

                    if (!isset($option['values'])) {
                        return redirect()->back()->withErrors(__('messages.please_add_options_for') . $option['name'])->withInput();
                    }
                    if ($option['type'] == "multi") {
                        if ($temp_variation['min'] > 0 && ($temp_variation['max'] < count($option['values']))) {
                            return redirect()->back()->withErrors('Options Can\'t be Greater Than Max')->withInput();
                        }
                    }

                    $temp_value = [];

                    foreach ($option['values'] as $value) {
                        $temp_option = [
                            'optionPrice' => $value['optionPrice']
                        ];
                        if (isset($value['label'])) {
                            $temp_option['label'] = $value['label'];
                        }
                        if (isset($value['optionMargin'])) {
                            $temp_option['optionMargin'] = $value['optionMargin'];
                        }
                        $temp_value[] = $temp_option;
                    }

                    $temp_variation['values'] = $temp_value;
                    $variations[] = $temp_variation;
                }
            }
            $variations = $request->isCustomize == 1 ? $variations : [];
            //combinations end
            $food->isCustomize = $request->isCustomize ?? 0;
            $food->variations =  json_encode($variations);
            $food->price = $request->isCustomize == 1 ? 0 : $foodPrice;
            $food->admin_margin = $request->isCustomize == 1 ? 0 : ($request->admin_margin ?? 0);
            $food->restaurant_price = $restaurant_price;

            if ($request->file('image')) {

                $food->image = Helpers::updateFile($request->file('image'), 'product/', $food->image);
            }
            $food->available_time_starts = $request->available_time_starts;
            $food->available_time_ends = $request->available_time_ends;
            $food->discount = $request->discount_type == 'amount' ? $request->discount : $request->discount;
            $food->discount_type = $request->discount_type;
            $food->discount_by = $request->discount_by;
            $food->packing_charge =  $request->packing_charge ?? 0;

            $food->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
            $food->add_ons = $request->has('addon_ids') ? json_encode($request->addon_ids) : json_encode([]);
            $food->restaurant_id = Session::get('restaurant')->id;

            $food->type = Helpers::getFoodType($request->food_type);
            $food->save();
            $food->tags()->sync($tag_ids);
            return back()->with('success', 'Food Updated');
        } catch (\Throwable $th) {
            // dd($th);
            return back()->with('error', $th->getMessage());
        }
    }

    public function status(Request $req)
    {
        try {

            $status =(bool) $req->query('status');
            $foodId = $req->query('food_id');

            $food = Food::find($foodId); 
            $food->status = $status;
            $food->save();


            return response()->json(['message' => 'Status changed'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function bulkImport()
    {
        return view('vendor-views.food.bulk-import');
    }

    public function bulk_import_save(Request $request)
    {
        try {
            $collections = (new FastExcel)->import($request->file('file'));
        } catch (\Error $e) {
            Session::flash('error', __('messages.you_have_uploaded_a_wrong_format_file'));
            Log::error("Errorn With bulk import  Import , Message :" . $e->getMessage() . " , line : " . $e->getLine() . "file :" + $e->getFile());
            return back();
        }
        Log::info("checking the start");

        try {
            $createdCount = 0;
            $updatedCount = 0;


            foreach ($collections as $collection) {
                $validator = Validator::make($collection, [
                    'name' => 'required|string',
                    'category_id' => 'nullable|integer',
                    'menu_no' => 'required|integer',
                    'submenu_no' => 'nullable|integer',
                    'type' => 'required|in:veg,non veg',
                    'restaurant_price' => 'required|numeric|min:0',
                    'admin_margin' => 'nullable|numeric|min:0',
                    'price' => 'required|numeric|min:0',
                    'discount' => 'nullable|numeric|min:0|max:100',
                    'discount_by' => 'nullable|string|in:admin,restaurant',
                    'description' => 'nullable|string',
                    'available_time_starts' => 'required|date_format:H:i:s',
                    'available_time_ends' => 'required|date_format:H:i:s',
                ], [
                    'discount_type.in' => 'The discount type must be either "percent" or "amount" for admin and only "amount" for restaurant.'
                ]);

                // Conditional validation for 'discount_type'
                $validator->sometimes('discount_type', 'in:percent,amount', function ($input) {
                    return $input->discount_by === 'admin';
                });

                $validator->sometimes('discount_type', 'in:amount', function ($input) {
                    return $input->discount_by === 'restaurant';
                });

                if ($validator->fails()) {
                  	$_collectionName = $collection['name'] ?? '';
                    Session::flash('error', __('messages.please_fill_all_required_fields') . " for the food name: {$_collectionName}. " . implode(", ", $validator->errors()->all()));
                    Log::error(__('messages.please_fill_all_required_fields') . " for the food name: {$_collectionName}. " . json_encode(implode(", ", $validator->errors()->all())));
                    return back();
                }
            }

            // dd($collections);

            DB::beginTransaction();

            foreach ($collections as $collection) {
                $menu = RestaurantMenu::where('restaurant_id', Session::get('restaurant')->id)->where('custom_id', $collection['menu_no'])->first();
                if ($menu == null) {
                    throw new \Exception('Menu Not Found');
                }
                $submenu = null;
                if (isset($collection['submenu_no']) && $collection['submenu_no'] != null && $collection['submenu_no'] != 0) {
                    $submenu = RestaurantSubMenu::where('restaurant_menu_id', $menu->id)->where('custom_id', $collection['submenu_no'])->first();
                    // dd($submenu);
                    if (!$submenu) {
                        Session::flash('error', __('Sub Menu Have Incorrect Relation'));
                        return back();
                    }
                }


                $tempData = [
                    'name' => $collection['name'],
                    'category_id' => empty($collection['category_id']) ? null : $collection['category_id'],
                    'category_ids' => json_encode([]),
                    'restaurant_menu_id' =>  $menu->id,
                    'restaurant_submenu_id' => $submenu ? $submenu->id : null,
                    'restaurant_price' => (float) $collection['restaurant_price'] ?? 0,
                    'admin_margin' => (float) $collection['admin_margin'] ?? 0,
                    'price' => (int) $collection['restaurant_price'] > 0 ? (int) $collection['restaurant_price'] + (int) $collection['admin_margin'] : 0,
                    'discount_by' => ((string) $collection['discount_by'] == 'restaurant') ? $collection['discount_by'] : 'admin',
                    'type' => (string) $collection['type'],
                    'discount' => (float) $collection['discount'] ?? 0,
                    'discount_type' => (string) $collection['discount_type'],
                    'packing_charge' => (float) $collection['packing_charge'] ?? 0,
                    'description' => (string) $collection['description'],
                    'available_time_starts' =>  Carbon::parse($collection['available_time_starts'])->format('H:i:s'),
                    'available_time_ends' => Carbon::parse($collection['available_time_ends'])->format('H:i:s'),
                    'restaurant_id' => Helpers::get_restaurant_id(),
                    'add_ons' => json_encode([]),
                    'attributes' => json_encode([]),
                    'choice_options' => json_encode([]),
                    'variations' => json_encode([]),
                ];

                // dd($tempData);

                // Update or create the food record
                $food = Food::updateOrCreate(
                    [
                        'id' => $collection['food_id'] ?? null,
                        'restaurant_id' => $menu->restaurant_id,
                    ],
                    $tempData
                );

                // Check if the record was created or updated
                if ($food->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }
            // dd($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            // Log error or handle exception
            Log::error('Food import error: ' . $e->getMessage(). ", line : ". $e->getLine()." , fileName :".$e->getFile());
            Session::flash('error', __('messages.failed_to_import_data'));
            return back();
        }
        // Log the results or use them as needed
        Session::flash('success', "Imported: $createdCount, Updated: $updatedCount");
        // Session::flash('success', __('messages.product_imported_successfully', ['count' => count($data)]));
        return back();
    }

    public function SampleFoodXlsx()
    {
        $restaurantId = Session::get('restaurant')->id;
        // Define headers
        $csvHeaders = [
            'name',
            'category_id',
            'menu_no',
            'submenu_no',
            'type',
            'restaurant_price',
            'admin_margin',
            'price',
            'discount',
            'discount_type',
            'discount_by',
            'packing_charge',
            'description',
            'available_time_starts',
            'available_time_ends',
            'food_id',
            'restaurant_id',
        ];
        $data = [
            ['Sample Food', 1, 1, 1, 'veg', 100, 10, 110, 10, 'percent', 'admin', 5, 'Sample description', Carbon::now()->format('H:i:s'), Carbon::now()->addHours(2)->format('H:i:s'), 1, $restaurantId],
            ['Sample Food 2', 1, 1, 1, 'non veg', 200, 20, 220, 15, 'amount', 'restaurant', 10, 'Sample description 2', Carbon::now()->format('H:i:s'), Carbon::now()->addHours(3)->format('H:i:s'), 2, $restaurantId],
            ['Sample Food 3', 1, 1, 1, 'veg', 150, 15, 165, 5, 'percent', 'admin', 7, 'Sample description 3', Carbon::now()->format('H:i:s'), Carbon::now()->addHours(4)->format('H:i:s'), 3, $restaurantId],
            ['Sample Food 4', 1, 1, 1, 'non veg', 250, 25, 275, 20, 'amount', 'restaurant', 15, 'Sample description 4', Carbon::now()->format('H:i:s'), Carbon::now()->addHours(5)->format('H:i:s'), 4, $restaurantId],
            ['Sample Food 5', 1, 1, 1, 'veg', 300, 30, 330, 25, 'percent', 'admin', 20, 'Sample description 5', Carbon::now()->format('H:i:s'), Carbon::now()->addHours(6)->format('H:i:s'), 5, $restaurantId],
            ['Sample Food 6', 1, 1, 1, 'non veg', 350, 35, 385, 30, 'amount', 'restaurant', 25, 'Sample description 6', Carbon::now()->format('H:i:s'), Carbon::now()->addHours(7)->format('H:i:s'), 6, $restaurantId],
            ['Sample Food 7', 1, 1, 1, 'veg', 400, 40, 440, 35, 'percent', 'admin', 30, 'Sample description 7', Carbon::now()->format('H:i:s'), Carbon::now()->addHours(8)->format('H:i:s'), 7, $restaurantId],
            ['Sample Food 8', 1, 1, 1, 'non veg', 450, 45, 495, 40, 'amount', 'restaurant', 35, 'Sample description 8', Carbon::now()->format('H:i:s'), Carbon::now()->addHours(9)->format('H:i:s'), 8, $restaurantId],
            ['Sample Food 9', 1, 1, 1, 'veg', 500, 50, 550, 45, 'percent', 'admin', 40, 'Sample description 9', Carbon::now()->format('H:i:s'), Carbon::now()->addHours(10)->format('H:i:s'), 9, $restaurantId],
            ['Sample Food10', 1, 1, 1, 'non veg', 550, 55, 605, 50, 'amount', 'restaurant', 45, 'Sample description10', Carbon::now()->format('H:i:s'), Carbon::now()->addHours(11)->format('H:i:s'), 10, $restaurantId]
        ];

        return Helpers::generateExcel($csvHeaders, $data, 'product-sample');
    }

    public function exportFood()
    {
        $foods = Food::with(['restaurant', 'category', 'menu', 'submenu'])->where('restaurant_id', Session::get('restaurant')->id)->latest()->get();
        if ($foods) {
            // Define headers
            $csvHeaders = [
                'name',
                'category_id',
                'menu_no',
                'submenu_no',
                'type',
                'restaurant_price',
                'admin_margin',
                'price',
                'discount',
                'discount_type',
                'discount_by',
                'packing_charge',
                'description',
                'available_time_starts',
                'available_time_ends',
                'food_id',
                'restaurant_id',
            ];

            $data = [];
            foreach ($foods as $food) {


                $data[] = [
                    $food->name,                                //     'name',
                    $food->category->id ?? null,                 //     'category Id',
                    $food->menu->custom_id,                   //     'menu Id',
                    ($food->submenu ? $food->submenu->custom_id : null), //     'menu Id',
                    $food->type,                            //     'type',
                    $food->restaurant_price,               //     'restaurant_price',
                    $food->admin_margin,                  //     'admin_margin',
                    $food->price,                        //     'price',
                    $food->discount,                    //     'discount',
                    $food->discount_type,              //     'discount_type',
                    $food->discount_by,               //     'discount_by',
                    $food->packing_charge,              //   packing charge
                    $food->description,               //    'description',
                    $food->available_time_starts,   //     'available_time_starts',
                    $food->available_time_ends,      //     'available_time_ends',
                    $food->id,                     //      'food Id',
                    $food->restaurant_id           //       restaurant id

                ];
            }
            return Helpers::generateExcel($csvHeaders, $data, 'product-existing');
        } else {
            return back()->with('error', 'Foods Not Available');
        }
    }


    public function get_submenu_option(Request $request)
    {
        $menuId = $request->query('menu_id');
        if ($menuId != null && $menuId !== "all") {
            $submenus = RestaurantSubMenu::where('restaurant_menu_id', $menuId)->isActive()->get();
            return response()->json($submenus);
        } else {
            return response()->json([]);
        }
    }

    // public function get_foods(Request $request)
    // {
    //     $foods = Food::withoutGlobalScope(RestaurantScope::class)->with('restaurant')->whereHas('restaurant', function($query)use($request){
    //         $query->where('zone_id', $request->zone_id);
    //     })->get();
    //     $res = '';
    //     if(count($foods)>0 && !$request->data)
    //     {
    //         $res = '<option value="' . 0 . '" disabled selected>---'.translate('messages.Select').'---</option>';
    //     }

    //     foreach ($foods as $row) {
    //         $res .= '<option value="'.$row->id.'" ';
    //         if($request->data)
    //         {
    //             $res .= in_array($row->id, $request->data)?'selected ':'';
    //         }
    //         $res .= '>'.$row->name.' ('.$row->restaurant->name.')'. '</option>';
    //     }
    //     return response()->json([
    //         'options' => $res,
    //     ]);
    // }
}
