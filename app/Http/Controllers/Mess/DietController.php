<?php

namespace App\Http\Controllers\Mess;

use App\Http\Controllers\Controller;
use App\Models\WeeklyChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DietController extends Controller
{
    public function dietCalander()
    {
        self::generateDietCalendar();
        return view('mess-views.Menu.calender');
    }

    public static function generateDietCalendar(): bool
    {
        $noOfWeeks = 5;
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $messId = Session::get('mess')->id;
        $elementCount = WeeklyChart::where('mess_id', $messId)->count();
        if ($elementCount === 0) {
            for ($i = 0; $i < $noOfWeeks; $i++) {
                foreach ($days as $day) {
                    WeeklyChart::create([
                        'week' => $i + 1,
                        'day' => $day,
                        'mess_id' => $messId,
                    ]);
                }
            }
            return true;
        } else {
            return false;
        }
    }


    public function getCalendarElements(Request $request) {
        try {
            $weekNo = $request->get('week_no'); 
            if (!$weekNo) {
                throw new \Exception('Empty Week');
            }
            $messId = Session::get('mess')->id;
    
            $calendarElements = WeeklyChart::where('week', $weekNo)->where('mess_id', $messId)->get(); 
            if ($calendarElements->isEmpty()) {
                throw new \Exception('Items Not Found');
            }
            return response()->json($calendarElements);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }

    public function updateCalenderItem(Request $request){
        try{
            $element = $request->json('element');
            $state = ['N' => 'normal', 'S' => 'special', 'O' => 'off'];
            $service = ['B' => 'breakfast', 'L' => 'lunch', 'D' => 'dinner'];
            
            $chartElem = WeeklyChart::find($element['id']);
            
            foreach($service as $key => $value) {
                if($key == $element['type']) {
                    if($element['checked'] == 0) {
                        $chartElem[$value] = $state['O'];
                    } else {
                        $chartElem[$value] = $state[$element['speciality']];
                    }
                    break;
                }
            }
            $chartElem->save();
            return response()->json(['success' => 'Saved!!']);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }
    


}
