<?php

namespace App\Http\Controllers\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\MessMenu;
use App\Models\MessService;
use App\Models\WeeklyMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\TextUI\Help;

class MenuController extends Controller
{
    public function index()
    {
        // MessService
        return view('mess-views.Menu.add');
    }
    public function indexWeekly()
    {
        // MessService
        return view('mess-views.Menu.add-weekly');
    }
    public function editWeekly($id)
    {
        $menu = WeeklyMenu::find($id);
        return view('mess-views.Menu._edit-menu', compact('menu'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            "setTo" => 'required',
            "description" => 'required|string',
            "name" => "required|string",
            'item_type' => 'required|',
            
        ], [
            'setTo.required' => 'Select at least one Service',
        ]);

        try {
            $menu = new MessMenu;
            $menu->name = $request->name;
            $menu->type = Helpers::getFoodType($request->item_type);
            $menu->mess_id = Session::get('mess')->id; 
            $menu->service = Helpers::getService($request->setTo);
            if ($request->hasFile('image')) { 
                $menu->image = Helpers::uploadFile($request->file('image'), 'MessMenu'); 
            }
            if(is_array($request->addons)){
                $menu->addons = json_encode($request->addons);
            }
            $menu->save();
            return redirect()->route('mess.menu.list')->with('success', 'Menu Item Added');

        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        } 
    }
    public function updateWeekly(Request $request)
    {
        $request->validate([
            "menu_id"=>'required', 
            "setTo" => 'required',
            "description" => 'required|string',
            "name" => "required|string",
            'item_type' => 'required|',
            
        ], [
            'setTo.required' => 'Select at least one Service',
        ]);

        try {
            $menu = WeeklyMenu::find($request->menu_id);
            $menu->name = $request->name;
            $menu->type = Helpers::getFoodType($request->item_type);
            $menu->mess_id = Session::get('mess')->id; 
            $menu->description = $request->description;
            $menu->service = Helpers::getService($request->setTo);
            if ($request->hasFile('image')) { 
                $menu->image = Helpers::updateFile($request->file('image'), 'MessMenu',$menu->image); 
            }
            if(is_array($request->addons)){
                $menu->addons = json_encode($request->addons);
            }
            $menu->save();
            return redirect()->back()->with('success', 'Menu Item Updated');

        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        } 
    }
    public function submitWeekly(Request $request)
    {
        $request->validate([
            "setTo" => 'required',
            "description" => 'required|string',
            "name" => "required|string",
            'item_type' => 'required|',
            'day' => 'required',
            
        ], [
            'setTo.required' => 'Select at least one Service',
        ]);

        try {
            $menu = new WeeklyMenu();
            $menu->name = $request->name;
            $menu->day = Helpers::getDayname($request->day);
            $menu->type = Helpers::getFoodType($request->item_type);
            $menu->mess_id = Session::get('mess')->id; 
            $menu->description = $request->description;
            $menu->service = Helpers::getService($request->setTo);
            if ($request->hasFile('image')) { 
                $menu->image = Helpers::uploadFile($request->file('image'), 'MessMenu'); 
            }
            if(is_array($request->addons)){
                $menu->addons = json_encode($request->addons);
            }
            $menu->save();
            return redirect()->back()->with('success', 'Menu Item Added');

        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        } 
    }
    public function list()
    {
        
        $services = Helpers::getService();
        $mess_id =  Session::get('mess')->id; 
        $menus = [];
        foreach($services as $service){
            $menus[$service] = MessMenu::where('service',$service)
                                ->where('mess_id',$mess_id)
                                ->get();
        }
    //    dd($menus);
        return view('mess-views.Menu.list',$menus);
    }

    public function Weeklylist()
    {
        
        $services = Helpers::getService();
        $mess_id =  Session::get('mess')->id; 
        $menus = [];
        foreach($services as $service){
            $menus[$service] = WeeklyMenu::where('service',$service)
                                ->where('mess_id',$mess_id)
                                ->get();
        }
    //    dd($menus);
        return view('mess-views.Menu.list-weekly',$menus);
    }

    
}
//