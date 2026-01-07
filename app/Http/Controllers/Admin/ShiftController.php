<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\Zone;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function list()
    {
        $shifts = Shift::with('zone')->latest()->get();
        $zones = Zone::where('status', 1)->get();
        
        return view('admin-views.shift.list',[
            'shifts' => $shifts,
            'zones' => $zones,
        ]);
    }

    // Zone-specific shift listing
    public function listByZone($zone_id)
    {
        $zone = Zone::findOrFail($zone_id);
        $shifts = Shift::where('zone_id', $zone_id)->with('zone')->latest()->get();
        
        return view('admin-views.shift.zone-list',[
            'shifts' => $shifts,
            'zone' => $zone,
        ]);
    }

    // Create shift form for specific zone
    public function createForZone($zone_id)
    {
        $zone = Zone::findOrFail($zone_id);
        return view('admin-views.shift.create-for-zone', compact('zone'));
    }

    public function edit(Request $request, $id)
    {
        $shift = Shift::with('zone')->find($id);
        $zones = Zone::where('status', 1)->get();
        return view('admin-views.shift.edit', compact('shift', 'zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'name' => 'required|max:191',
            'zone_id' => 'required|exists:zones,id',
        ],[
            'end_time.after' => __('messages.End time must be after the start time'),
            'zone_id.required' => __('messages.Zone is required'),
            'zone_id.exists' => __('messages.Selected zone is invalid'),
        ]);
        
        try{
            // Check for overlapping shifts in the same zone
            $temp = Shift::where('zone_id', $request->zone_id)
                ->where(function ($q) use ($request) {
                    return $q->where(function ($query) use ($request) {
                        return $query->where('start_time', '<=', $request->start_time)->where('end_time', '>=', $request->start_time);
                    })->orWhere(function ($query) use ($request) {
                        return $query->where('start_time', '<=', $request->end_time)->where('end_time', '>=', $request->end_time);
                    });
                })
                ->first();
            
            if (isset($temp)) {
                throw new \Exception(__('messages.Shift_overlaped_in_zone'));
            }

            $shift = new Shift();
            $shift->name = $request->name;
            $shift->start_time = $request->start_time;
            $shift->end_time = $request->end_time;
            $shift->zone_id = $request->zone_id;
            $shift->save();
            
            return response()->json(['success' => __('messages.shift_added_successfully')]);
        }catch(\Exception $ex){
            return response()->json(['errors' => $ex->getMessage()]);
        }   
    }

    // Store shift for specific zone
    public function storeForZone(Request $request, $zone_id)
    {
        $request->merge(['zone_id' => $zone_id]);
        return $this->store($request);
    }
    public function status(Request $request)
    {
        $shift = Shift::findOrFail($request->id);
        $shift->status = $request->status;
        $shift->save();
        return back()->with('success', __('messages.shift_status_updated'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'name' => 'required|max:191',
            'zone_id' => 'required|exists:zones,id',
        ],[
            'end_time.after' => __('messages.End time must be after the start time'),
            'zone_id.required' => __('messages.Zone is required'),
            'zone_id.exists' => __('messages.Selected zone is invalid'),
        ]);
        
        try{
            // Check for overlapping shifts in the same zone (excluding current shift)
            $temp = Shift::where('id', '!=', $id)
                ->where('zone_id', $request->zone_id)
                ->where(function ($q) use ($request) {
                    return $q->where(function ($query) use ($request) {
                        return $query->where('start_time', '<=', $request->start_time)->where('end_time', '>=', $request->start_time);
                    })->orWhere(function ($query) use ($request) {
                        return $query->where('start_time', '<=', $request->end_time)->where('end_time', '>=', $request->end_time);
                    });
                })
                ->first();
            
            if (isset($temp)) {
                throw new \Exception(__('messages.Shift_overlaped_in_zone'));
            }
            
            Shift::find($id)->update([
                'name' => $request->name,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'zone_id' => $request->zone_id,
            ]);
            
            return response()->json(['success' => __('messages.shift_updated_successfully')]);
        }catch(\Exception $ex){
            return response()->json(['errors' => $ex->getMessage()]);
        }
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        return back()->with('success', __('messages.shift_deleted_successfully'));
    }

    public function search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $zone_id = $request->zone_id;
        
        $shifts = Shift::with('zone')
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            })
            ->when($zone_id, function ($query) use ($zone_id) {
                return $query->where('zone_id', $zone_id);
            })
            ->latest()
            ->paginate(50);
            
        return response()->json([
            'view' => view('admin-views.shift.partials._table', compact('shifts'))->render(),
            'total' => $shifts->total()
        ]);
    }

    // Zone-specific search
    public function searchByZone(Request $request)
    {
        $key = explode(' ', $request['search']);
        $zone_id = $request->zone_id;
        
        $shifts = Shift::with('zone')
            ->where('zone_id', $zone_id)
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            })
            ->latest()
            ->paginate(50);
            
        return response()->json([
            'view' => view('admin-views.shift.partials._table', compact('shifts'))->render(),
            'total' => $shifts->total()
        ]);
    }

    // Get all zones for dropdown
    public function getZones()
    {
        $zones = Zone::where('status', 1)->select('id', 'name')->get();
        return response()->json($zones);
    }

    // Get shifts by zone
    public function getShiftsByZone($zone_id)
    {
        $shifts = Shift::where('zone_id', $zone_id)
            ->where('status', 1)
            ->select('id', 'name', 'start_time', 'end_time')
            ->get();
        return response()->json($shifts);
    }

    // Get zone-wise summary
    public function getZoneWiseSummary()
    {
        $summary = Zone::withCount(['shifts as total_shifts', 'shifts as active_shifts' => function($query) {
            $query->where('status', 1);
        }])
        ->where('status', 1)
        ->get();
        
        return response()->json($summary);
    }

    // Assign zone to shift
    public function assignZone(Request $request)
    {
        $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'zone_id' => 'required|exists:zones,id',
        ]);

        try {
            $shift = Shift::findOrFail($request->shift_id);
            $shift->zone_id = $request->zone_id;
            $shift->save();

            return response()->json(['success' => __('messages.zone_assigned_successfully')]);
        } catch (\Exception $ex) {
            return response()->json(['errors' => $ex->getMessage()]);
        }
    }

    // Bulk assign zone to multiple shifts
    public function bulkAssignZone(Request $request)
    {
        $request->validate([
            'shift_ids' => 'required|array',
            'shift_ids.*' => 'exists:shifts,id',
            'zone_id' => 'required|exists:zones,id',
        ]);

        try {
            Shift::whereIn('id', $request->shift_ids)->update(['zone_id' => $request->zone_id]);
            return response()->json(['success' => __('messages.zones_assigned_successfully')]);
        } catch (\Exception $ex) {
            return response()->json(['errors' => $ex->getMessage()]);
        }
    }

    // Transfer shift from one zone to another
    public function transferZone(Request $request)
    {
        $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'from_zone_id' => 'required|exists:zones,id',
            'to_zone_id' => 'required|exists:zones,id|different:from_zone_id',
        ]);

        try {
            $shift = Shift::where('id', $request->shift_id)
                ->where('zone_id', $request->from_zone_id)
                ->firstOrFail();
            
            $shift->zone_id = $request->to_zone_id;
            $shift->save();

            return response()->json(['success' => __('messages.shift_transferred_successfully')]);
        } catch (\Exception $ex) {
            return response()->json(['errors' => $ex->getMessage()]);
        }
    }

    // Bulk transfer shifts between zones
    public function bulkTransferZone(Request $request)
    {
        $request->validate([
            'shift_ids' => 'required|array',
            'shift_ids.*' => 'exists:shifts,id',
            'from_zone_id' => 'required|exists:zones,id',
            'to_zone_id' => 'required|exists:zones,id|different:from_zone_id',
        ]);

        try {
            Shift::whereIn('id', $request->shift_ids)
                ->where('zone_id', $request->from_zone_id)
                ->update(['zone_id' => $request->to_zone_id]);
                
            return response()->json(['success' => __('messages.shifts_transferred_successfully')]);
        } catch (\Exception $ex) {
            return response()->json(['errors' => $ex->getMessage()]);
        }
    }

    // Bulk status update for zone shifts
    public function zoneBulkStatusUpdate(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'shift_ids' => 'required|array',
            'shift_ids.*' => 'exists:shifts,id',
            'status' => 'required|in:0,1',
        ]);

        try {
            Shift::whereIn('id', $request->shift_ids)
                ->where('zone_id', $request->zone_id)
                ->update(['status' => $request->status]);
                
            return response()->json(['success' => __('messages.shifts_status_updated_successfully')]);
        } catch (\Exception $ex) {
            return response()->json(['errors' => $ex->getMessage()]);
        }
    }

    // Bulk delete shifts in a zone
    public function zoneBulkDelete(Request $request, $zone_id)
    {
        $request->validate([
            'shift_ids' => 'required|array',
            'shift_ids.*' => 'exists:shifts,id',
        ]);

        try {
            Shift::whereIn('id', $request->shift_ids)
                ->where('zone_id', $zone_id)
                ->delete();
                
            return response()->json(['success' => __('messages.shifts_deleted_successfully')]);
        } catch (\Exception $ex) {
            return response()->json(['errors' => $ex->getMessage()]);
        }
    }

    // Export zone shifts
    public function exportZoneShifts($zone_id)
    {
        $zone = Zone::findOrFail($zone_id);
        $shifts = Shift::where('zone_id', $zone_id)->with('zone')->get();
        
        // You can implement your export logic here (Excel, PDF, CSV)
        return response()->json([
            'success' => __('messages.export_prepared_successfully'),
            'data' => $shifts
        ]);
    }

    // Filter zones
    public function filterZones(Request $request)
    {
        $zones = Zone::where('status', 1)
            ->when($request->search, function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->withCount('shifts')
            ->get();
            
        return response()->json($zones);
    }
}
