<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use App\Models\RestaurantSubMenu;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
{
    public function index()
    {
        return view('admin-views.food.index');
    }

    public function list()
    {
        $foods = Food::with(['restaurant', 'category'])->get();
        // dd($foods);
        return view('admin-views.food.list', compact('foods'));
    }

    public function edit($id)
    {
        $food = Food::with(['category','tags','menu','restaurant'])->find($id);
        $restaurantId = $food->restaurant_id;
        $categories = Category::isActive(true)->latest()->get();

        if(!$food){
            return back()->with('error', 'Food Not Found');
        }
        // dd($food);
        return view('admin-views.food._edit',compact('food','categories'));
    }


    public function store(Request $request)
    {
        // dd($request->post());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:191',

            'category_id' => 'required',
            'restaurant_menu_id' => 'required',
            'image' => 'nullable|max:2048',
            'restaurant_price' => 'nullable|numeric',
            'admin_margin' => 'nullable|numeric',
            'packing_charge' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_by' =>'nullable',
            'discount_type' => 'nullable',
            'restaurant_id' => 'required',
            'description.*' => 'max:1000',
            'food_type'=>'required'
        ], [
            'description.*.max' => __('messages.description_length_warning'),
            'category_id.required' => __('messages.category_required'),
            'food_type.required'=>__('messages.item_type_is_required')
        ]);

        $foodPrice = $request->isCustomize != 1 ? ((int) $request->restaurant_price + (int) $request->admin_margin) : 0 ;
        $restaurant_price = $request->isCustomize != 1 ? $request->restaurant_price  : 0 ;

        if( $request->isCustomize != 1){
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
        $food->category_id = $request->category_id;
        $food->description =  $request->description;
        $food->isRecommended = $request->isRecommended??0;
        $food->restaurant_menu_id = $request->restaurant_menu_id;
        $food->restaurant_submenu_id = $request->restaurant_submenu_id??null;


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
                     return redirect()->back()->withErrors( __('messages.minimum_value_can_not_be_greater_then_maximum_value'))->withInput();
                }

                if (!isset($option['values'])) {
                    return redirect()->back()->withErrors( __('messages.please_add_options_for') . $option['name'])->withInput();
                }
                if(isset($value['optionMargin'])){
                    $temp_option['optionMargin'] = $value['optionMargin'];
                }
                if($option['type'] == "multi"){
                    if ($temp_variation['min'] > 0 && ($temp_variation['max'] < count($option['values']))) {
                        return redirect()->back()->withErrors( 'Options Can\'t be Greater Than Max')->withInput();
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
                    $temp_value[] = $temp_option;
                }

                $temp_variation['values'] = $temp_value;
                $variations[] = $temp_variation;
            }
        }

        //combinations end
        $food->variations = $request->isCustomize == 1 ? json_encode($variations) : json_encode([]);
        $food->price = $request->isCustomize == 1? 0 : $foodPrice;
        $food->admin_margin =  $request->admin_margin ?? 0 ;
        $food->restaurant_price = $restaurant_price ;
        $food->isCustomize = !empty($variations) ? true : false;
        $food->image = $request->hasFile('image') ? Helpers::uploadFile($request->file('image'),'product/') : null;
        $food->available_time_starts = $request->available_time_starts;
        $food->available_time_ends = $request->available_time_ends;
        $food->discount = $request->discount_type == 'amount' ? $request->discount??0 : $request->discount??0;
        $food->discount_type = $request->discount_type;
        $food->discount_by = $request->discount_by;
        $food->packing_charge =  $request->packing_charge ?? 0 ;

        $food->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
        $food->add_ons = $request->has('addon_ids') ? json_encode($request->addon_ids) : json_encode([]);
        $food->restaurant_id = $request->restaurant_id;
        $food->type = Helpers::getFoodType($request->food_type);
        // dd($food);
        $food->save();
        // $food->tags()->sync($tag_ids);

        return redirect()->back()->with('success', 'Food Added');
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:3|max:191',

                'food_id' =>'required',
                'category_id' => 'nullable',
                'restaurant_menu_id' => 'required',
                'image' => 'nullable|max:2048',
                'restaurant_price' => 'nullable|numeric',
                'admin_margin' => 'nullable|numeric',
                'packing_charge' => 'nullable|numeric',
                'discount' => 'required|numeric|min:0',
                'discount_by' =>'nullable',
                'discount_type' => 'nullable',
                'description.*' => 'max:1000',
                'food_type' => 'required'
            ], [
                'description.*.max' => __('messages.description_length_warning'),
                'category_id.required' => __('messages.category_required'),
                'food_type.required' => __('messages.item_type_is_required')
            ]);

            // dd($validator);
            $foodPrice = $request->isCustomize != 1 ? ((int) $request->restaurant_price + (int) $request->admin_margin) : 0 ;
            $restaurant_price = $request->isCustomize != 1 ? $request->restaurant_price  : 0 ;


            if( $request->isCustomize != 1){

                if ($request['discount_type'] == 'percent') {
                    $dis = ($foodPrice / 100) * $request['discount'];
                } else {
                    $dis = $request['discount'];
                }
                if ($foodPrice <= $dis ) {
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
            if(isset($tags)){
                foreach ($tags as $key => $value) {
                    $tag = Tag::firstOrNew(
                        ['tag' => $value]
                    );
                    $tag->save();
                    array_push($tag_ids,$tag->id);
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
            $food->restaurant_submenu_id = $request->restaurant_submenu_id??null;
            $food->category_id = $request->category_id;
            $food->description =  $request->description;
            $food->isRecommended = $request->isRecommended??0;




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
                        if(isset($value['optionMargin'])){
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
            $food->isCustomize = $request->isCustomize??0;
            $food->variations =  json_encode($variations);
            $food->price = $request->isCustomize == 1? 0 : $foodPrice;
            $food->admin_margin =  $request->admin_margin ?? 0 ;
            $food->restaurant_price = $restaurant_price ;

            if($request->file('image')){

                $food->image = Helpers::updateFile($request->file('image'), 'product/', $food->image);
            }
            $food->available_time_starts = $request->available_time_starts;
            $food->available_time_ends = $request->available_time_ends;
            $food->discount = $request->discount_type == 'amount' ? $request->discount : $request->discount;
            $food->discount_type = $request->discount_type;
            $food->discount_by = $request->discount_by;
            $food->packing_charge =  $request->packing_charge ?? 0 ;

            $food->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
            $food->add_ons = $request->has('addon_ids') ? json_encode($request->addon_ids) : json_encode([]);
            // $food->restaurant_id = ;

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

            $status = $req->query('status');
            $foodId = $req->query('food_id');

            $food = Food::find($foodId);
            $food->status = filter_var($status, FILTER_VALIDATE_BOOLEAN);
            $food->save();


            return response()->json(['message'=> 'Status changed'],200);


        } catch (\Throwable $th) {
            return response()->json(['message'=> $th->getMessage()],500);
        }

    }

    public function delete($id)
    {
        $food = Food::find($id);
        if(!$food){
            return back()->with('error', 'Food Not Found');
        }
        Helpers::deleteFile('product/', $food->image);
        $food->delete();
        return back()->with('success',"food Deleted");
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
            ->orderBy('id','DESC')->get();

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function get_submenu_option(Request $request)
    {
        $menuId = $request->query('menu_id');
        if($menuId != null && $menuId !== "all"){
            $submenus = RestaurantSubMenu::where('restaurant_menu_id', $menuId)->isActive()->get();
            return response()->json($submenus);
        }else{
            return response()->json([]);
        }
    }
}
