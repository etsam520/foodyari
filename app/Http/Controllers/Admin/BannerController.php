<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Food;
use App\Models\Marquee;
use App\Models\Restaurant;
use App\Models\VendorMess;
use App\Models\Zone;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class BannerController extends Controller
{
    function index()
    {
        $banners = Banner::latest()->paginate(config('default_pagination'));
        return view('admin-views.banner.index', compact('banners'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // dd($request->file('image'));
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:191',
            'image' => 'required|max:2048',
            'banner_type' => 'required',
            'zone_id' => 'required',
            'restaurant_id' => 'required_if:banner_type,restaurant_wise',
            'item_id' => 'required_if:banner_type,item_wise',
        ], [
            'zone_id.required' => __('messages.select_a_zone'),
            'restaurant_id.required_if'=> __('messages.Restaurant is required when banner type is restaurant wise'),
            'item_id.required_if'=> __('messages.Food is required when banner type is food wise'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Failed The Job');
        }

        $banner = new Banner;
        $banner->title = $request->title;
        $banner->type = $request->banner_type;
        $banner->zone_id = $request->zone_id;
        $banner->image = Helpers::uploadFile($request->file('image'), 'banner/',);
        if($banner->type == 'location'){
            $banner->latitude = $request->latitude;
            $banner->longitude = $request->longitude;
            $banner->radius = $request->radius??null;
        }elseif($banner->type == 'food'){
            $banner->food_id = $request->food;
        }elseif($banner->type == 'restaurant'){
            $banner->restaurant_id = $request->restaurant;
            $banner->screen_to = $request->screen_to ;
        }
        $banner->link = $request->link??null;

        $banner->save();

        return redirect()->back()->with('success', 'Banner Added');
    }

    public function edit(Request $request)
    {
        $banner_id = $request->query('banner_id'); // Use query() to get the query parameter
        $banner = Banner::find($banner_id);
        return view('admin-views.banner._edit',compact('banner'));
    }

    public function getPartials(Request $request)
    {
        try{

            $name = $request->query('name');
            $zone = $request->query('zone');
            $restaurants = $foods = '' ;

            if($name == 'restaurant') {
                $restaurants = Restaurant::isActive()->where('zone_id', $zone)->get();
            }elseif ($name == 'food') {
                $foods = Food::isActive()
                    ->whereHas('restaurant', function($query) use($zone) {
                        $query->where('zone_id', $zone);
                    })
                    ->with('restaurant')
                    ->get();
            }


            return response()->json([
                'view' => view('admin-views.banner.partials.sub-index', compact('name', 'restaurants','foods' ))->render(),
            ]);

        }catch(\Throwable $th){
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 404);
        }
    }
    public function getPartialsSaved(Request $request)
    {
        try{

            $name = $request->query('name');
            $zone = $request->query('zone');
            $restaurants = $foods = '' ;
            $type = $request->query('type');
            $typeId = $request->query('type_id');

            $savedItem = null;
            if($type == "banner"){
                $savedItem = Banner::findOrfail($typeId);
            }elseif($type == "marquee"){
                $savedItem = Marquee::findOrfail($typeId);
            }

            if($name == 'restaurant') {
                $restaurants = Restaurant::isActive()->where('zone_id', $zone)->get();
            }elseif ($name == 'food') {
                $foods = Food::isActive()
                    ->whereHas('restaurant', function($query) use($zone) {
                        $query->where('zone_id', $zone);
                    })
                    ->with('restaurant')
                    ->get();
            }


            return response()->json([
                'view' => view('admin-views.banner.partials.sub-index-saved', compact('name', 'restaurants','foods','savedItem' ))->render(),
            ]);

        }catch(\Throwable $th){
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 404);
        }
    }
    public function status(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        $banner->status = $request->status;
        $banner->save();
        return back()->with('success', __('messages.banner_status_updated'));
    }

    public function update(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'banner_id' => 'required',
                'title' => 'required|max:191',
                'banner_type' => 'required',
                'zone_id' => 'required',
                'image' => 'nullable|image|max:2048',
            ], [
                'zone_id.required' => __('messages.select_a_zone'),
            ]);
            $banner = Banner::find($request->banner_id);

            if (!$banner) {
                throw new Error('Banner Not Found');
            }
            if ($validator->fails()) {
                return response()->json(['errors' => Helpers::error_processor($validator)]);
            }

            $banner->title = $request->title;
            $banner->type = $request->banner_type;
            $banner->zone_id = $request->zone_id;

            $banner->latitude = $request->latitude??null;
            $banner->longitude = $request->longitude??null;
            $banner->radius =$request->radius??null;
            $banner->food_id = $request->food??null;
            $banner->restaurant_id = $request->restaurant??null;

            if ($request->hasFile('image')) {
                $banner->image = Helpers::updateFile($request->file('image'), 'banner/', $banner->image);
            }

            if($banner->type == 'location'){
                $banner->latitude = $request->latitude;
                $banner->longitude = $request->longitude;
                $banner->radius = $request->radius??null;
            }elseif($banner->type == 'food'){
                $banner->food_id = $request->food;
            }elseif($banner->type == 'restaurant'){
                $banner->restaurant_id = $request->restaurant;
                $banner->screen_to = $request->screen_to ;
            }

            if($request->link !=null){
                $banner->link = $request->link??null;
            }
            $banner->save();
            return redirect()->route('admin.banner.add-new')->with('success','Banner Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('error' ,$th->getMessage());
        }
    }


    public function delete( $id)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return back()->with('warning', 'Marquee Not Found');
        }
        if($banner['image']){
            $filePath = public_path('banner/' . $banner['image']);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $banner->delete();

        return back()->with('success',__('messages.banner_deleted_successfully'));
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $banners=Banner::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.banner.partials._table',compact('banners'))->render(),
            'count'=>$banners->count()
        ]);
    }
}
