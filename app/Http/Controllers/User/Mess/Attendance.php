<?php
namespace App\Http\Controllers\User\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\CustomerSubscriptionTransactions;
use App\Models\DietCoupon;
use App\Models\MessQR;
use App\Models\MessService;
use App\Models\Subscription;
use App\Models\VendorMess;
use App\Models\WeeklyChart;
use App\Models\Attendance as ModelAttendance;
use Carbon\Carbon;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Attendance extends Controller
{
    protected $today;
    protected $now;

    public function __construct()
    {
        $this->today = Carbon::now('Asia/Kolkata')->toDateString();
        $this->now = Carbon::now()->toTimeString();
    }

    // public function index()
    // {
        
    // }

    public function index()
    {
        $dailyreport = ModelAttendance::where('customer_id', Session::get('userInfo')->id)
            ->whereDate('created_at', '>=', Carbon::now()->subYear())
            ->with(['checklist.messService', 'customers.user'])
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('user-views.attendance.index', compact('dailyreport'));
    }

    public function my_diet_qr()
    {
        return view('user-views.attendance.qrcode');
    }

    public function my_diet_qr_image()
    {
        $userId = Session::get('userInfo')->id;
        $result = MessQR::with('ckecklist')->whereDate('created_at', $this->today)
            ->where('customer_id', $userId)
            ->where('checked_at', null)
            ->whereTime('created_at', '<', $this->now)
            ->get();

        return response()->json($result);
    }
}
