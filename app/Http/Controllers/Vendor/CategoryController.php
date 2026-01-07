<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Translation;
use App\CentralLogics\Helpers;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;//


class CategoryController extends Controller
{
    public function index()
    {
        
        $categories = Category::isActive(true)->latest()->get();

        return view('vendor-views.category.index',compact('categories'));
    }
    
   
    public function get_categories(){
        $categories = Category::isActive(true)->latest()->get();
        return response()->json($categories);
    }

    
}
