<?php

namespace App\Http\Controllers\Mess;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    public function attendance()
    {
        $today = Carbon::now('Asia/Kolkata')->toDateString();
        $attendances = Attendance::whereDate('created_at', $today)
            ->with(['checklist', 'customers'])
            ->where('mess_id', Session::get('mess')->id)
            ->get();
        
        $dataToReturn = [];
        foreach ($attendances as $index => $att) {
            $temp = [];
            $temp['S.I.'] = $index + 1; // Using the loop index
            $temp['Date'] = $today;
            $temp['Name'] = ucfirst($att->customers->f_name) . " " . ucfirst($att->customers->l_name);
            $temp['Phone'] = $att->customers->phone;
            
            $temp['services'] = '';
            foreach ($att->checklist as $chklist) {
                $temp['services'] .= ucfirst($chklist->service) . ", ";
            }
            // Trim the trailing comma and space
            $temp['services'] = rtrim($temp['services'], ', ');
            
            $dataToReturn[] = $temp;
        }

        return response()->json(['attendances' => $dataToReturn]);
    }


    public function today()
    {
        $year = date('Y');
        $attendances = Attendance::whereYear('created_at', $year)
            ->with(['checklist', 'customers'])
            ->where('mess_id', Session::get('mess')->id)
            ->get();
        return response()->json(['attendances' => $attendances]);
    }
    
    public function daily()
    {
        $today = Carbon::now('Asia/Kolkata')->toDateString(); 
        $attendances = Attendance::whereDate('created_at', $today)
            ->with(['checklist'])
            ->where('mess_id', Session::get('mess')->id)
            ->limit(150)
            ->latest()->get();

        $report = [];
        $temp = [
            'date' => null,
            'attendance' => 0,
            'breakfast' => 0,
            'lunch' => 0,
            'dinner' => 0,
        ];

        foreach ($attendances as $attendance) {
            $attendanceDate = $attendance->created_at->toDateString();
            if ($temp['date'] === null) {
                $temp['date'] = $attendanceDate;
            } elseif ($temp['date'] !== $attendanceDate) {
                $report[] = $temp;
                $temp = [
                    'date' => $attendanceDate,
                    'attendance' => 0,
                    'breakfast' => 0,
                    'lunch' => 0,
                    'dinner' => 0,
                ];
            }

            $temp['attendance']++;
            foreach ($attendance->checklist as $chklist) {
                if ($chklist->service === 'breakfast') {
                    $temp['breakfast']++;
                } elseif ($chklist->service === 'lunch') {
                    $temp['lunch']++;
                } elseif ($chklist->service === 'dinner') {
                    $temp['dinner']++;
                }
            }
        }

        if ($temp['date'] !== null) {
            $report[] = $temp;
        }

        return response()->json(['report' => $report]);
    }

    public function monthly()
    {
        $startOfMonth = Carbon::now('Asia/Kolkata')->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now('Asia/Kolkata')->endOfMonth()->toDateString();
        
        $attendances = Attendance::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->with(['checklist', 'customers'])
            ->where('mess_id', Session::get('mess')->id)
            ->get();
        return response()->json(['attendances' => $attendances]);
    }
}
