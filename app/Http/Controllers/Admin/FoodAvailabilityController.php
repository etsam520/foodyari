<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodAvailabilityTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoodAvailabilityController extends Controller
{
    /**
     * Display the food availability management page
     */
    public function index(Request $request)
    {
        $query = Food::with(['restaurant', 'availabilityTimes']);
        
        // Search functionality
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('restaurant', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }
        
        $foods = $query->paginate(15);
        
        return view('admin-views.food-availability.index', compact('foods'));
    }

    /**
     * Show availability times for a specific food
     */
    public function show($foodId)
    {
        $food = Food::with(['restaurant', 'availabilityTimes' => function($query) {
            $query->orderBy('day')->orderBy('start_time');
        }])->findOrFail($foodId);

        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday', 
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];

        return view('admin-views.food-availability.show', compact('food', 'daysOfWeek'));
    }

    /**
     * Store a new availability time
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'food_id' => 'required|exists:food,id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check for time overlaps for the same day and food
        $existingTime = FoodAvailabilityTime::where('food_id', $request->food_id)
            ->where('day', $request->day)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($existingTime) {
            return back()->withErrors(['time_conflict' => 'This time slot overlaps with an existing availability time.'])->withInput();
        }

        FoodAvailabilityTime::create([
            'food_id' => $request->food_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return back()->with('success', 'Availability time added successfully!');
    }

    /**
     * Update an availability time
     */
    public function update(Request $request, $id)
    {
        $availabilityTime = FoodAvailabilityTime::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check for time overlaps (excluding current record)
        $existingTime = FoodAvailabilityTime::where('food_id', $availabilityTime->food_id)
            ->where('day', $request->day)
            ->where('id', '!=', $id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($existingTime) {
            return back()->withErrors(['time_conflict' => 'This time slot overlaps with an existing availability time.'])->withInput();
        }

        $availabilityTime->update([
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return back()->with('success', 'Availability time updated successfully!');
    }

    /**
     * Delete an availability time
     */
    public function destroy($id)
    {
        $availabilityTime = FoodAvailabilityTime::findOrFail($id);
        $availabilityTime->delete();

        return back()->with('success', 'Availability time deleted successfully!');
    }

    /**
     * Bulk delete availability times for a food
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'food_id' => 'required|exists:food,id',
            'day' => 'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $query = FoodAvailabilityTime::where('food_id', $request->food_id);
        
        if ($request->day) {
            $query->where('day', $request->day);
            $message = 'All availability times for ' . ucfirst($request->day) . ' deleted successfully!';
        } else {
            $message = 'All availability times deleted successfully!';
        }

        $query->delete();

        return back()->with('success', $message);
    }
}
