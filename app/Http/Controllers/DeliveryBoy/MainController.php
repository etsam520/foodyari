<?php

namespace App\Http\Controllers\DeliveryBoy;

use App\CentralLogics\Deliveryman\DeliverymanLastLocation;
use App\CentralLogics\Helpers;
use App\CentralLogics\Redis\RedisHelper;
use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\DeliverymanAttendance;
use App\Models\FuelTransaction;
use App\Services\JsonDataService;
use Carbon\Carbon;
use Google\Rpc\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;
// use Intervention\Image\Facades\Image;

use function PHPUnit\Framework\fileExists;

class MainController extends Controller
{
    public function profile()
    {
        $dm = DeliveryMan::find(auth('delivery_men')->user()->id);
        return view('deliveryman.admin.profile', compact('dm'));
    }

    public function profileUpdate(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'f_name' => 'required|string',
            'l_name' => 'nullable|string',
            'phone' => 'required|numeric|digits:10',
            'email' => 'required|email',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'merital_status' => 'required|in:single,married',
            'anniversary_date' => 'nullable|date',
            'street' => 'required|string',
            'city' => 'required|string',
            'pincode' => 'required|digits:6',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        }
        $dm = DeliveryMan::find(auth('delivery_men')->user()->id);
        $dm->f_name = $request->f_name;
        $dm->l_name = $request->l_name;
        $dm->phone = $request->phone;
        $dm->email = $request->email;
        $dm->gender = $request->gender;
        $dm->dob = $request->dob;
        $dm->marital_status = $request->merital_status;
        $dm->anniversary_date = $request->anniversary_date;
        $dm->address = json_encode([
            'street' => $request->street,
            'city' => $request->city,
            'pincode' => $request->pincode
        ]);
        $dm->blood_group = $request->blood_group;

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $image_name = Helpers::updateFile($image, 'delivery-man/', $dm->image);
            $dm->image = $image_name;
        }
        $dm->save();
        return response()->json(['success' => 'Profile updated successfully'], 200);
    }

    public function locationUpdate(Request $request)
    {
        $today = Carbon::now()->toDateString();
        $dmId = $request->json('dm_id');
        $dm = DeliveryMan::find($dmId);
        $status = DeliverymanAttendance::whereDate('created_at', $today)->where('deliveryman_id', $dmId)->latest()->limit(1)->first();
        $dmData = new JsonDataService($dm->id);
        $dmData->name = ucfirst($dm->f_name) . ' ' . ucfirst($dm->l_name);
        $dmData->mess_id = $dm->mess_id ?? null;
        $dmData->admin_id = $dm->admin_id ?? null;
        $dmData->restaurant_id = $dm->restaurant_id ?? null;
        $dmData->active = $status ? $status->is_online == 1 : false;
        $dmData->currentOrders = $dm->current_orders ?? 0;
        $dmData->last_location = $request->position;
        $dmData->updated_at = Carbon::now()->toDateTimeString();
        $dmData->save();
        return response()->json([], 200);
    }

    /**
     * Activate or Deactivate the delivery man
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     * @throws \Exception
     * $redis->set("deliveryman:{$dmData->dm_id}:active:{$date}", $dmData->active ? '1' : '0', 3600 *3);
     * $redis->set("deliveryman:{$dmData->dm_id}:last_location:{$date}", json_encode($dmData->last_location), 3600);
     * $redis->set("deliveryman:{$dmData->dm_id}:name:{$date}", $dmData->name, 3600 * 18); // 24 hours
     * $redis->set("deliveryman:{$dmData->dm_id}:current_orders:{$date}", $dmData->currentOrders, 3600 * 18);
     */

    public function activate(Request $request)
    {
        try {
            $checked = $request->query('checked');
            $today = Carbon::now()->toDateString();
            $deliveryman = DeliveryMan::find(auth('delivery_men')->user()->id);

            if (!$deliveryman) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $dmData = new JsonDataService($deliveryman->id);
            $dmData = $dmData->readData();
            $dmData->active = filter_var($checked, FILTER_VALIDATE_BOOLEAN);
            $dmData->name = ucfirst($deliveryman->f_name) . ' ' . ucfirst($deliveryman->l_name);
            $dmData->save();

            $redis = new RedisHelper();
            $date =  date('d-m-Y');

            $redis->set("deliveryman:{$dmData->dm_id}:active:{$date}", $dmData->active ? '1' : '0', 3600 * 3);
            $redis->set("deliveryman:{$dmData->dm_id}:last_location:{$date}", json_encode($dmData->last_location), 3600);
            $redis->set("deliveryman:{$dmData->dm_id}:name:{$date}", $dmData->name, 3600 * 18); // 24 hours
            $redis->set("deliveryman:{$dmData->dm_id}:current_orders:{$date}", $dmData->currentOrders, 3600 * 18); // 24 hours
            // Set value with 1 hour TTL (default)


            $attendance = DeliverymanAttendance::whereDate('created_at', $today)->where('deliveryman_id', $deliveryman->id)->first();

            if (!$attendance) {
                $attendance = new DeliverymanAttendance();
                $attendance->deliveryman_id =  $deliveryman->id;
            }
            if ($attendance->check_out != null) {
                throw new \Exception('Already Checked Out');
            }

            $attendance->is_online = filter_var($checked, FILTER_VALIDATE_BOOLEAN);

            $attendance->deliveryman_id =  $deliveryman->id;

            $attendance->location = json_encode($dmData->last_location);
            $attendance->last_checked = Carbon::now();
            if ($attendance->check_in == null) {
                $attendance->check_in = now();
                $address = Helpers::getAddressLocation($dmData->last_location['lat'] . ',' . $dmData->last_location['lng']);
                $attendance->check_in_address = $address;
                $attendance->check_in_location = json_encode($dmData->last_location);
            }
            $attendance->save();

            return response()->json(['message' => 'Status Updated : ' . $attendance->id, 'checkIn' => $attendance->is_online], 200);
        } catch (\Throwable $th) {
            // dd($th);
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function meter_check_in(Request $request)
    {
        try {

            $request->validate([
                'meter_reading' => 'required|numeric',
                'note' => 'nullable',
                'meter_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ]);

            $today = Carbon::now()->toDateString();
            $deliveryman = DeliveryMan::find(auth('delivery_men')->user()->id);

            if (!$deliveryman) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            $dmData = new JsonDataService($deliveryman->id);
            $dmData = $dmData->readData();


            $attendance = $deliveryman->attendances()->whereDate('created_at', $today)->first();
            if (!$attendance) {
                $attendance = new DeliverymanAttendance();
                $attendance->deliveryman_id = $deliveryman->id;
                $attendance->is_online = 1;
            }

            $attendance->check_in_meter = $request->meter_reading;
            // ✅ Resize and save image using Intervention
            if ($request->hasFile('meter_image')) {
                $image = $request->file('meter_image');
                $filename = 'meter_' . time() . '.' . $image->getClientOriginalExtension();
                $path = public_path('uploads/meters/' . $filename);

                // Create directory if not exists
                if (!file_exists(public_path('uploads/meters'))) {
                    mkdir(public_path('uploads/meters'), 0755, true);
                }

                // Resize and compress image
                $image = Image::read($image)
                    ->resize(300, 200)
                    ->encodeByExtension('jpg', 75)
                    ->save($path);
                // ->resize(800, null, function ($constraint) {
                //     $constraint->aspectRatio();
                //     $constraint->upsize();
                // });

                $attendance->check_in_image =  $filename;
            }

            $attendance->check_in_note = $request->note;
            if ($attendance->check_in == null) {
                $attendance->check_in = now();
                $address = Helpers::getAddressLocation($dmData->last_location['lat'] . ',' . $dmData->last_location['lng']);
                $attendance->check_in_address = $address;
                $attendance->check_in_location = json_encode($dmData->last_location);
            }
            $attendance->save();
            $redis = new RedisHelper();
            $date =  date('d-m-Y');
            $redis->set("deliveryman:{$dmData->dm_id}:active:{$date}", $dmData->active ? '1' : '0', 3600 * 3);
            // $redis->set("deliveryman:{$dmData->dm_id}:last_location:{$date}", json_encode($dmData->last_location), 3600);
            $redis->set("deliveryman:{$dmData->dm_id}:name:{$date}", $dmData->name, 3600 * 18); // 24 hours
            $redis->set("deliveryman:{$dmData->dm_id}:current_orders:{$date}", $dmData->currentOrders, 3600 * 18);
            $dloc = new DeliverymanLastLocation($dmData->dm_id, $dmData->last_location['lat'], $dmData->last_location['lng'], date('d-m-Y H:i:s'));
            $dloc->saveLastLocation();
            // return response()->json(['message' => 'Status Updated :-'.$attendance->id, 'checkIn'=> $attendance->is_online], 200);
            return back()->with('success', 'Saved Successfully');
        } catch (\Throwable $th) {
            Log::error('Meter Check In Error: ' . $th->getMessage());
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function meter_check_out(Request $request)
    {
        try {

            $request->validate([
                'meter_reading' => 'required|numeric',
                'note' => 'nullable',
                'meter_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ]);

            $today = Carbon::now()->toDateString();
            $deliveryman = DeliveryMan::find(auth('delivery_men')->user()->id);

            if (!$deliveryman) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            $dmData = new JsonDataService($deliveryman->id);
            $dmData = $dmData->readData();


            $attendance = $deliveryman->attendances()->whereDate('created_at', $today)->first();
            if (!$attendance) {
                $attendance = new DeliverymanAttendance();
            }
            $attendance->check_out = now();
            $attendance->check_out_location = json_encode($dmData->last_location);
            $attendance->check_out_meter = $request->meter_reading;
            $attendance->check_out_note = $request->note;
            $address = Helpers::getAddressLocation($dmData->last_location['lat'] . ',' . $dmData->last_location['lng']);
            $attendance->check_out_address = $address;

            //  Resize and save image using Intervention
            if ($request->hasFile('meter_image')) {
                $image = $request->file('meter_image');
                $filename = 'meter_' . time() . '.' . $image->getClientOriginalExtension();
                $path = public_path('uploads/meters/' . $filename);

                // Create directory if not exists
                if (!file_exists(public_path('uploads/meters'))) {
                    mkdir(public_path('uploads/meters'), 0755, true);
                }

                // Resize and compress image
                $image = Image::read($image)
                    ->resize(300, 200)
                    ->encodeByExtension('jpg', 75)
                    ->save($path);
                // ->resize(800, null, function ($constraint) {
                //     $constraint->aspectRatio();
                //     $constraint->upsize();
                // });

                $attendance->check_out_image =  $filename;
            }
            $attendance->last_checked = now();
            $attendance->is_online = false;
            $attendance->save();

            $distance = $attendance->check_out_meter - $attendance->check_in_meter;
            FuelTransaction::create([
                'dm_id' => $deliveryman->id,
                'amount' => ($distance > 0 ? $distance : 0) * $deliveryman->fuel_rate,
                'type' => 'deduct',
                'distance' => $distance > 0 ? $distance : 0,
                'rate' => $deliveryman->fuel_rate,
                'note' => $request->note ?? "Fuel Top Up",
                'attendance_id' => $attendance->id
            ]);

            // return response()->json(['message' => 'Status Updated :-'.$attendance->id, 'checkIn'=> $attendance->is_online], 200);
            return back()->with('success', 'Saved Successfully');
        } catch (\Throwable $th) {
            // dd($th);
            return back()->with('error', $th->getMessage());
            // return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function attendance(Request $request)
    {
        $dm = DeliveryMan::find(auth('delivery_men')->user()->id);
        $today = \Carbon\Carbon::now()->format('Y-m-d');
        $halfDayTime = Carbon::createFromTimeString(env('HALF_DAY_TIME', '12:00:00'))->toTimeString(); // e.g. '12:00:00'
        $checkOutHalfDay = Carbon::createFromTimeString(env('CHECK_OUT_HALF_DAY', '21:45:00'))->toTimeString(); // e.g. '21:45:00'

        $attendance = $dm->attendances()->whereDate('created_at', $today)->first();

        $attendance_query = DB::table('deliveryman_attendances as dm_attendances')
            ->where('dm_attendances.deliveryman_id', $dm['id'])
            ->select([
                DB::raw('SUM(dm_attendances.check_out_meter - dm_attendances.check_in_meter) as sum_distance'),
                DB::raw("SUM(
                CASE
                    WHEN TIME(dm_attendances.check_in) >= '$halfDayTime'
                        OR TIME(dm_attendances.check_out) >= '$checkOutHalfDay'
                        OR TIMESTAMPDIFF(MINUTE, dm_attendances.check_in, dm_attendances.check_out) < 240
                    THEN 0.5
                    ELSE 1
                END
            ) as total_working_days"),
            ])
            ->whereMonth('dm_attendances.created_at', Carbon::now()->month)
            ->whereYear('dm_attendances.created_at', Carbon::now()->year);
        $attendance_result = $attendance_query->get()->first();
        // dd($attendance_result);
        $fuel_balance = DB::table('fuel_transactions')->where('dm_id', $dm['id'])->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)->select(
                'dm_id',
                DB::raw("SUM(CASE WHEN type = 'add' THEN amount ELSE 0 END) - SUM(CASE WHEN type = 'deduct' THEN amount ELSE 0 END) AS balance")
            )->groupBy('dm_id')->first();
        $fuel_balance = $fuel_balance ? $fuel_balance->balance : 0;

        $total_distance = $attendance_result->sum_distance;
        $working_days = $attendance_result->total_working_days;


        return view('deliveryman.admin.attendance.index', compact('dm', 'today', 'attendance', 'total_distance', 'working_days', 'fuel_balance'));
    }

    public function workingReport(Request $request)
    {
        try {
            $now = Carbon::now();

            $filter = $request->get('filter'); // e.g., '2025-04'

            if ($filter) {
                try {
                    $startDate = Carbon::createFromFormat('Y-m', $filter)->startOfMonth();
                    $endDate = Carbon::createFromFormat('Y-m', $filter)->endOfMonth();
                } catch (\Exception $e) {
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                }
            } else {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }

            $deliverymanId = auth('delivery_men')->user()->id;
            $halfDayTime = Carbon::createFromTimeString(env('HALF_DAY_TIME', '12:00:00'))->toTimeString(); // e.g. '12:00:00'
            $checkOutHalfDay = Carbon::createFromTimeString(env('CHECK_OUT_HALF_DAY', '21:45:00'))->toTimeString(); // e.g. '21:45:00'

            $attendance = DB::table('deliveryman_attendances as dm_attendances')
                ->where('dm_attendances.deliveryman_id', $deliverymanId)
                ->whereBetween('dm_attendances.created_at', [$startDate, $endDate])
                ->select([
                    DB::raw('DATE(dm_attendances.created_at) as date'),

                    // is_half_day: either check_in is after 12:00 or check_out is after 21:45
                    // DB::raw("IF(TIME(dm_attendances.check_in) >= '$halfDayTime' OR TIME(dm_attendances.check_out) >= '$checkOutHalfDay', 1, 0) as is_half_day"),

                    DB::raw("CASE
                                WHEN TIME(dm_attendances.check_in) >= '$halfDayTime'
                                OR TIME(dm_attendances.check_out) >= '$checkOutHalfDay'
                                OR TIMESTAMPDIFF(MINUTE, dm_attendances.check_in, dm_attendances.check_out) < 240
                                THEN 1
                                ELSE 0
                            END as is_half_day"),

                    // is_full_day: both check_in before 12:00 and check_out before 21:45
                    // DB::raw("IF(TIME(dm_attendances.check_in) < '$halfDayTime' AND TIME(dm_attendances.check_out) < '$checkOutHalfDay', 1, 0) as is_full_day"),
                    DB::raw("CASE
                                WHEN TIME(dm_attendances.check_in) < '$halfDayTime'
                                OR TIME(dm_attendances.check_out) < '$checkOutHalfDay'
                                OR TIMESTAMPDIFF(MINUTE, dm_attendances.check_in, dm_attendances.check_out) >= 240
                                THEN 1
                                ELSE 0
                            END as is_full_day"),

                    DB::raw('(dm_attendances.check_out_meter - dm_attendances.check_in_meter) as distance'),
                    'check_in_meter',
                    'check_out_meter',
                    'check_in',
                    'check_out',
                    'created_at'
                ])
                ->orderBy('dm_attendances.created_at', 'asc')
                ->get();

            $workings = DB::table('deliveryman_attendances as dm_attendances')
                ->where('dm_attendances.deliveryman_id', $deliverymanId)
                ->whereBetween('dm_attendances.created_at', [$startDate, $endDate])
                ->select([
                    DB::raw("SUM(
                        CASE
                            WHEN TIME(dm_attendances.check_in) >= '$halfDayTime'
                                OR TIME(dm_attendances.check_out) >= '$checkOutHalfDay'
                                OR TIMESTAMPDIFF(MINUTE, dm_attendances.check_in, dm_attendances.check_out) < 240
                            THEN 0.5
                            ELSE 1
                        END
                    ) as total_working_days"),

                    DB::raw("SUM(
                        CASE
                            WHEN TIME(dm_attendances.check_in) >= '$halfDayTime'
                                OR TIME(dm_attendances.check_out) >= '$checkOutHalfDay'
                                OR TIMESTAMPDIFF(MINUTE, dm_attendances.check_in, dm_attendances.check_out) < 240
                            THEN 1
                            ELSE 0
                        END
                    ) as half_days"),

                    DB::raw("SUM(
                        CASE
                            WHEN TIME(dm_attendances.check_in) < '$halfDayTime'
                                AND TIME(dm_attendances.check_out) < '$checkOutHalfDay'
                                AND TIMESTAMPDIFF(MINUTE, dm_attendances.check_in, dm_attendances.check_out) >= 240
                            THEN 1
                            ELSE 0
                        END
                    ) as full_days"),
                ])
                ->first();

            $fuel_price = DB::table('fuel_transactions')->where('dm_id', $deliverymanId)
                ->whereBetween('fuel_transactions.created_at', [$startDate, $endDate])
                ->where('type', 'add')
                ->select([
                    DB::raw("SUM(amount)  as total_fuel_price"),
                ])->get()->first();
            $fuel_price = $fuel_price->total_fuel_price ?? 0;

            $total_distance = 0;
            // dd($workings);
            $leave_days = ($endDate->diffInDays($startDate) + 1) - $workings->total_working_days;

            $total_salary = 10944;


            $attendances_formatted = array_map(function ($dm_attendance) use (&$total_distance) {
                $total_distance += $dm_attendance->distance;
                return [
                    'date' => Carbon::parse($dm_attendance->created_at)->format('d F Y'),
                    'check_in' =>  Carbon::parse($dm_attendance->check_in)->format('g:i A'),
                    'check_out' => $dm_attendance->check_out != null ? Carbon::parse($dm_attendance->check_out)->format('g:i A') : null,
                    'check_in_meter' => $dm_attendance->check_in_meter,
                    'check_out_meter' => $dm_attendance->check_out_meter,
                    'distance' => $dm_attendance->distance,
                    'status' => $dm_attendance->is_half_day == 1 ? 'Half Day' : 'Present',
                ];
            }, $attendance->toArray());

            return view('deliveryman.admin.attendance.working-report', compact('now', 'startDate', 'endDate', 'attendances_formatted', 'workings', 'total_distance', 'fuel_price', 'leave_days', 'total_salary'));
        } catch (\Throwable $th) {
            dd($th);
            return back()->with('error', $th->getMessage());
        }
    }

    public function distanceReport(Request $request)
    {
        try {

            $query = DB::table('deliveryman_attendances as dm_attendances')
                ->where('dm_attendances.deliveryman_id', auth('delivery_men')->user()->id)
                ->select(
                    'dm_attendances.*',
                    DB::raw('(dm_attendances.check_out_meter - dm_attendances.check_in_meter) as distance')
                );

            // Filtering based on user selection (day, month, year)
            $filterType = $request->query('filter', 'month'); // Default: By Day

            if ($filterType === 'day') {
                $query = $query->whereDate('created_at', Carbon::today());
            } elseif ($filterType === 'month') {
                $query = $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            } elseif ($filterType === 'year') {
                $query = $query->whereYear('created_at', Carbon::now()->year);
            }
            $dm_attendances = $query->orderBy('created_at', 'desc')->get();

            $total_distance = 0;
            $dm_attendances_formatted = array_map(function ($dm_attendance) use (&$total_distance) {
                $total_distance += $dm_attendance->distance;
                return [
                    'date' => Carbon::parse($dm_attendance->created_at)->format('d F Y'),
                    'check_in' =>  Carbon::parse($dm_attendance->check_in)->format('g:i A'),
                    'check_out' => $dm_attendance->check_out != null ? Carbon::parse($dm_attendance->check_out)->format('g:i A') : null,
                    'check_in_meter' => $dm_attendance->check_in_meter,
                    'check_out_meter' => $dm_attendance->check_out_meter,
                    'check_in_location' =>  json_decode($dm_attendance->check_in_location, true),
                    'check_out_location' => json_decode($dm_attendance->check_out_location, true),
                    'check_in_address' => $dm_attendance->check_in_address,
                    'check_out_address' => $dm_attendance->check_out_address,
                    'check_in_image' => $dm_attendance->check_in_image != null ? asset('uploads/meters/' . $dm_attendance->check_in_image) : asset('assets/dm/images/shapes/istockphoto-1226328537-612x612.jpg'),
                    'check_out_image' => $dm_attendance->check_out_image != null ? asset('uploads/meters/' . $dm_attendance->check_out_image) : asset('assets/dm/images/shapes/istockphoto-1226328537-612x612.jpg'),
                    'distance' => $dm_attendance->distance,

                ];
            }, $dm_attendances->toArray());


            return view('deliveryman.admin.attendance.distance-report', compact('dm_attendances_formatted', 'total_distance'));
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    public function fuelReport(Request $request)
    {
        try {
            $dm = auth('delivery_men')->user();
            $filterType = $request->query('filter', 'month'); // default: month

            $query = DB::table('fuel_transactions')->where('dm_id', $dm->id); // Correct column name

            if ($filterType === 'day') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($filterType === 'month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            } elseif ($filterType === 'year') {
                $query->whereYear('created_at', Carbon::now()->year);
            } elseif ($filterType === 'week') {
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ]);
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();
            $balance = $query->select(
                'dm_id',
                DB::raw("SUM(CASE WHEN type = 'add' THEN amount ELSE 0 END) - SUM(CASE WHEN type = 'deduct' THEN amount ELSE 0 END) AS balance")
            )->groupBy('dm_id')->first()->balance;

            $grouped = $transactions->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->startOfWeek()->format('Y-m-d');
            });

            $formattedData = $grouped->map(function ($transactions, $date) {
                $total = 0;
                foreach ($transactions as $txn) {
                    if ($txn->type === 'add') {
                        $total += $txn->amount; // Add amount
                    } else {
                        $total -= $txn->amount; // Deduct amount
                    }
                }

                return [
                    'date' => $date,
                    'total' => $total,
                    'transactions' => $transactions
                ];
            });

            // dd($formattedData);

            return view('deliveryman.admin.attendance.fuel-report', compact('formattedData', 'balance'));
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
