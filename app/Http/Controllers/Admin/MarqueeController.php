<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Marquee;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MarqueeController extends Controller
{
    function index()
    {

        $marquees = Marquee::latest()->paginate(config('default_pagination'));
        return view('admin-views.marquee.index', compact('marquees'));
    }

    public function store(Request $request)
    {
        // dd($request->file('image'));
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:191',
            'image' => 'nullable|max:2048',
            'marquee_type' => 'required',
            'zone_id' => 'required',
            'restaurant_id' => 'required_if:marquee_type,restaurant_wise',
            'item_id' => 'required_if:marquee_type,item_wise',
        ], [
            'zone_id.required' => __('messages.select_a_zone'),
            'restaurant_id.required_if'=> __('Restaurant is required when Marquee type is restaurant wise'),
            'item_id.required_if'=> __('messages.Food is required when Marquee type is food wise'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Failed The Job');
        }

        $marquee = new Marquee();
        $marquee->title = $request->title;
        $marquee->type = $request->marquee_type;
        $marquee->zone_id = $request->zone_id;
        if($request->file('image')){
            $marquee->file = Helpers::uploadFile($request->file('image'), 'marquee/',);
        }

        $marquee->link = $request->link??null;


        if($marquee->type == 'location'){
            $marquee->latitude = $request->latitude;
            $marquee->longitude = $request->longitude;
            $marquee->radius = $request->radius??null;
        }elseif($marquee->type == 'food'){
            $marquee->food_id = $request->food;
        }elseif($marquee->type == 'restaurant'){
            $marquee->restaurant_id = $request->restaurant;
            $marquee->screen_to = $request->screen_to ;
        }

        $marquee->save();

        return redirect()->back()->with('success', 'Marquee Added');
    }

    public function edit(Request $request)
    {
        $marquee_id = $request->query('marquee_id'); // Use query() to get the query parameter
        $marquee = Marquee::find($marquee_id);
        return view('admin-views.marquee._edit', compact('marquee'));

    }

    public function status(Request $request)
    {
        $marquee = Marquee::findOrFail($request->id);
        $marquee->status = $request->status;
        $marquee->save();
        return back()->with('success', __('messages.marquee_status_updated'));
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'marquee_id' => 'required',
                'title' => 'required|max:191',
                'marquee_type' => 'required',
                'zone_id' => 'required',
                'image' => 'nullable|image|max:2048',
            ], [
                'zone_id.required' => __('messages.select_a_zone'),
            ]);
            $marquee = Marquee::find($request->marquee_id);

            if (!$marquee) {
                throw new Error('Marquee Not Found');
            }
            if ($validator->fails()) {
                return response()->json(['errors' => Helpers::error_processor($validator)]);
            }

            $marquee->title = $request->title;
            $marquee->type = $request->marquee_type;
            $marquee->zone_id = $request->zone_id;

            $marquee->latitude = $request->latitude??null;
            $marquee->longitude = $request->longitude??null;
            $marquee->radius =$request->radius??null;
            $marquee->food_id = $request->food??null;
            $marquee->restaurant_id = $request->restaurant??null;

            if($request->file('image')){
                $marquee->file = Helpers::updateFile($request->file('image'), 'marquee/', $marquee->image);
                $marquee->link = null;
            }
            if($request->link){
                $marquee->link = $request->link;
            }
            if($marquee->type == 'location'){
                $marquee->latitude = $request->latitude;
                $marquee->longitude = $request->longitude;
                $marquee->radius = $request->radius??null;
            }elseif($marquee->type == 'food'){
                $marquee->food_id = $request->food;
            }elseif($marquee->type == 'restaurant'){
                $marquee->restaurant_id = $request->restaurant;
                $marquee->screen_to = $request->screen_to ;
            }

            $marquee->save();
            return redirect()->route('admin.marquee.add-new')->with('success','Marquee Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('error',$th->getMessage());
        }
    }


    public function delete($id)
{
    $marquee = Marquee::find($id);

    if (!$marquee) {
        return back()->with('warning', 'Marquee Not Found');
    }

    // Check if the file exists and delete it
    if($marquee['file']){
        $filePath = public_path('marquee/' . $marquee['file']);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $marquee->delete();

    return back()->with('success', __('Marquee Deleted Succssfully'));
}

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $marquees=Marquee::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.marquee.partials._table',compact('marquees'))->render(),
            'count'=>$marquees->count()
        ]);
    }
}
