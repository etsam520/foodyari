<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Subcategory;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {

        $categories = Category::latest()->get();
        return view('admin-views.category.index',compact('categories'));
    }



    public function edit(Request $request, $id)
    {

        $category = Category::find($id);
        return view('admin-views.category._edit',compact('category'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            // 'position' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);

        try {
            DB::beginTransaction();

            if($request->has('image')){
                $filename = Helpers::uploadFile($request->file('image'), 'Category')  ;
            }else{
               $filename = null;
            }
            Category::create([
                'name' => $request->name,
                // 'position' => $request->position,
                'image' => $filename,
            ]);

            DB::commit();

            return redirect()->route('admin.category.add')->with('success', __('messages.category-created'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'id' => 'required',
            // 'position' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);

        try {
            DB::beginTransaction();
            $category = Category::find($request->id);
            if(!$category){
                throw new \Exception('Category Not Found');
            }

            if($request->has('image')){
                $filename = Helpers::updateFile($request->file('image'), 'Category', $category->image)  ;
            }else{
               $filename =  $category->image;
            }

            $category->name =  $request->name;
            // 'position' => $request->position,
            $category->image = $filename;
            $category->save();


            DB::commit();

            return redirect()->route('admin.category.add')->with('success', __('Category Updated'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function status (Request $request,$id){
        $category = Category::find($id);
        $category->status = $request->status;
        $category->save();
        if($request->status == 1){
            return back()->with('success', 'Category Activated');
          }elseif($request->status == 0){
            return back()->with('warning', 'Category Deactivated');
        }
        return back();

    }


    public function get_categories(){
        return response()->json(Category::get());
    }

}
