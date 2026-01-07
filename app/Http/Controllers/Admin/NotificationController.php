<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DeliveryMan;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\DB;
// use App\Models\Notification;
use App\Models\Restaurant;
use App\Models\Zone;
use App\Notifications\CommonNotification;
use App\Notifications\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = NotificationTemplate::latest()->paginate(20);

        return view('admin-views.notification.index', compact('notifications'));
    }

    public function targetClient(Request $request) {
        $response = [];  // Corrected typo: $respose -> $response
        $zone_id = $request->query('zone');
        // dd($zone_id);

        if($request->query('filter') == 'customer') {
            // Fetch active customers
            $customers = Customer::isActive()->select('id', 'f_name','phone')->get();
            foreach($customers as $customer) {
                $response[] = [
                    'id' => $customer->id,
                    'name' => $customer->f_name,
                    'phone' => $customer->phone,
                ];
            }
        } elseif ($request->query('filter') == 'restaurant') {
            // Fetch active restaurants in the specified zone
            $restaurants = Restaurant::when($zone_id != "all", function($q) use($zone_id) {
                return $q->where('zone_id', $zone_id);
            })->isActive()->select('id', 'name')->get();
            foreach ($restaurants as $restaurant) {
                $response[] = [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,

                ];
            }
        } elseif ($request->query('filter') == 'deliveryman') {
            // Fetch active deliverymen in the specified zone
            $deliverymen = DeliveryMan::when($zone_id != "all", function($q) use($zone_id) {
                return $q->where('zone_id', $zone_id);
            })->isActive()->where('type', 'admin')->select('id', 'f_name','phone')->get();
            foreach ($deliverymen as $deliveryman) {
                $response[] = [
                    'id' => $deliveryman->id,
                    'name' => $deliveryman->f_name,
                    'phone' => $deliveryman->phone,
                ];
            }
        }

        // Return the response as JSON
        return response()->json($response);
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'notification_title' => 'required|max:191',
            'description' => 'required|max:1000',
            'target' => 'required',
            'tergatClient' => 'required',
            'zone'=>'required'
        ], [
            'notification_title.required' => 'Title is required!',
        ]);



        if ($validator->fails()) {
           return back()->withErrors($validator)->withInput();
        }

        $target = $request->target;
        $targetClient = $request->tergatClient;
        $targetZone = $request->zone ?? 'all';
        $targetZoneIds = Zone::when($targetZone !== 'all', function($query) use($targetZone) {
            return $query->where('id', $targetZone);
        })->isActive()->pluck('id')->toArray();

        $sendTo = null;


        if ($target == 'customer') {
            if($targetZone != 'all'){
                $customers = $this->getNearestCustomers($targetZone);
                // dd($customers);
                $sendTo = Customer::whereIn('id', $customers->pluck('id')->toArray())->isActive()->get();

            }else{
                $sendTo = Customer::when(! (is_array($targetClient) && count($targetClient) === 1 && $targetClient[0] === "all"), function($q) use($targetClient) {
                    return $q->whereIn('id', $targetClient);
                })->isActive()->get();
            }
        } elseif ($target == 'deliveryman') {
            $sendTo = DeliveryMan::when($targetClient != "all", function($q) use($targetClient){
                return $q->whereIn('id', $targetClient);
            })->isActive()
                ->whereIn('zone_id', $targetZoneIds)
                ->where('type', 'admin')
                ->get();
        } elseif ($target == 'restaurant') {
            $sendTo = Restaurant::when($targetClient != "all", function($q) use($targetClient){
                return $q->whereIn('id', $targetClient);
            })->isActive()
                ->whereIn('zone_id', $targetZoneIds)
                ->get();
        }else{
            return back()->with('info', 'Choose Target Client');
        }
        $isFile = false;
        if($request->hasFile('image')){
            $isFile = true ;
            $finame = Helpers::uploadFile($request->image, 'uploads/notifications');
        }

        NotificationTemplate::create([
            'title' => $request->notification_title,
            'description' => $request->description,
            'file' => $isFile ? $finame : null,
            'target' => json_encode($target),
            'targetZone' => json_encode($targetZone) ,
            'targetClient' => json_encode($targetClient) ,
        ]);

        $notification = [
            'type' => 'Manual',
            'subject' => $request->notification_title,
            'message' => $request->description,
        ];
        if($isFile){
            $notification['image'] = asset('uploads/notifications/'.$finame);
        }

        Notification::send($sendTo, new FirebaseNotification($notification));
        return back()->with('success', 'Send Success')->withInput();
    }

    public function edit($id)
    {
        $notification = Notification::findOrFail($id);
        return view('admin-views.notification.edit', compact('notification'));
    }

    public function update(Request $request, $id)
    {
        if (env('APP_MODE') == 'demo') {
            // Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        $request->validate([
            'notification_title' => 'required|max:191',
            'description' => 'required|max:1000',
            'tergat' => 'required',
        ]);

        $notification = Notification::findOrFail($id);

        if ($request->has('image')) {
            $image_name = Helpers::update('notification/', $notification->image, 'png', $request->file('image'));
        } else {
            $image_name = $notification['image'];
        }

        $notification->title = $request->notification_title;
        $notification->description = $request->description;
        $notification->image = $image_name;
        $notification->tergat= $request->tergat;
        $notification->zone_id = $request->zone=='all'?null:$request->zone;
        $notification->updated_at = now();
        $notification->save();

        $topic_all_zone=[
            'customer'=>'all_zone_customer',
            'deliveryman'=>'all_zone_delivery_man',
            'restaurant'=>'all_zone_restaurant',
        ];

        $topic_zone_wise=[
            'customer'=>'zone_'.$request->zone.'_customer',
            'deliveryman'=>'zone_'.$request->zone.'_delivery_man',
            'restaurant'=>'zone_'.$request->zone.'_restaurant',
        ];
        $topic = $request->zone == 'all'?$topic_all_zone[$request->tergat]:$topic_zone_wise[$request->tergat];

        if($request->has('image'))
        {
            $notification->image = url('/').'/storage/app/public/notification/'.$image_name;
        }

        try {
            Helpers::send_push_notif_to_topic($notification, $topic, 'general');
        } catch (\Exception $e) {
            Toastr::warning(translate('messages.push_notification_faild'));
        }
        Toastr::success(translate('messages.notification').' '.translate('messages.updated_successfully'));
        return back();
    }

    public function status(Request $request)
    {
        $notification = Notification::findOrFail($request->id);
        $notification->status = $request->status;
        $notification->save();
        // Toastr::success(translate('messages.notification_status_updated'));
        return back();
    }

    public function delete(Request $request)
    {
        $notification = NotificationTemplate::findOrFail($request->id);
        $notification['file'] = $notification['file']??'abc.png';
        if (file_exists(public_path('uploads/notifications/'.$notification['file']))) {
            unlink(public_path('uploads/notifications/').$notification['file']);
        }
        $notification->delete();
        return back()->with('success',__('messages.notification_deleted_successfully'));
    }
    public function clearData(Request $request)
    {
        $notifications = NotificationTemplate::get();
        foreach ($notifications as $notification) {
            $notification['file'] = $notification['file']??'abc.png';
            if (file_exists(public_path('uploads/notifications/'.$notification['file']))){
                unlink(public_path('uploads/notifications/').$notification['file']);
            }
            $notification->delete();
        }
        DB::table('notifications')->truncate();

        return back()->with('success',__('messages.notification_deleted_successfully'));
    }

    private function __getNearestCustomers ($zoneId){
        $zone = Zone::find($zoneId);
        if (!$zone) {
            return [];
        }
        $coordinates = json_decode($zone->coordinates, true);
        $zone_latitude = $coordinates['latitude'];
        $zone_longitude = $coordinates['longitude'];
        $zone_radius = $zone->radius;

        /* distance calculation
        (
            6371 * acos(
              cos(radians(:zone_lat)) *
              cos(radians(ca.latitude)) *
              cos(radians(ca.longitude) - radians(:zone_lng)) +
              sin(radians(:zone_lat)) *
              sin(radians(ca.latitude))
            )
          ) AS distance */

          $addressSub = DB::table('customer_addresses')
          ->select('customer_id', 'id', 'latitude', 'longitude', 'address', 'is_default')
          ->whereRaw('1 = 1')
          ->orderByDesc('is_default')
          ->orderByDesc('id');

      // Group to keep one per customer
      $addressSub = DB::table(DB::raw("({$addressSub->toSql()}) as ca"))
          ->mergeBindings($addressSub)
          ->groupBy('ca.customer_id');

      $customers = DB::table('customers as c')
          ->where('c.status', 1)
          ->joinSub($addressSub, 'ca', function ($join) {
              $join->on('c.id', '=', 'ca.customer_id');
          })
          ->select(
              'c.id', 'c.f_name', 'c.l_name', 'c.phone', 'c.email', 'c.fcm_token', 'c.image', 'c.status',
              'ca.id as address_id', 'ca.latitude', 'ca.longitude', 'ca.address', 'ca.is_default',
              DB::raw("(
                  6371 * acos(
                      cos(radians(?)) *
                      cos(radians(ca.latitude)) *
                      cos(radians(ca.longitude) - radians(?)) +
                      sin(radians(?)) *
                      sin(radians(ca.latitude))
                  )
              ) as distance")
          )
          ->addBinding($zone_latitude, 'select')
          ->addBinding($zone_longitude, 'select')
          ->addBinding($zone_latitude, 'select')
          ->having('distance', '<=', $zone_radius)
          ->get();
        return $customers;
    }
    private function getNearestCustomers ($zoneId){
        $zone = Zone::find($zoneId);
        if (!$zone) {
            return [];
        }
        $coordinates = json_decode($zone->coordinates, true);
        $zone_latitude = $coordinates['latitude'];
        $zone_longitude = $coordinates['longitude'];
        $zone_radius = $zone->radius;

        /* distance calculation
        (
            6371 * acos(
              cos(radians(:zone_lat)) *
              cos(radians(ca.latitude)) *
              cos(radians(ca.longitude) - radians(:zone_lng)) +
              sin(radians(:zone_lat)) *
              sin(radians(ca.latitude))
            )
          ) AS distance */

        $addressSub = DB::table('customer_addresses as ca1')
        ->select('ca1.*')
        ->whereRaw('ca1.id = (
            SELECT ca2.id FROM customer_addresses as ca2
            WHERE ca2.customer_id = ca1.customer_id
            ORDER BY ca2.is_default DESC, ca2.id DESC
            LIMIT 1
        )');

        // Final query
        $customers = DB::table('customers as c')
        ->where('c.status', 1)
        ->joinSub($addressSub, 'ca', function ($join) {
            $join->on('c.id', '=', 'ca.customer_id');
        })
        ->select(
            'c.id', 'c.f_name', 'c.l_name', 'c.phone', 'c.email', 'c.fcm_token', 'c.image', 'c.status',
            'ca.id as address_id', 'ca.latitude', 'ca.longitude', 'ca.address', 'ca.is_default',
            DB::raw("(
                6371 * acos(
                    cos(radians(?)) *
                    cos(radians(ca.latitude)) *
                    cos(radians(ca.longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(ca.latitude))
                )
            ) as distance")
        )
        ->addBinding($zone_latitude, 'select')
        ->addBinding($zone_longitude, 'select')
        ->addBinding($zone_latitude, 'select')
        ->having('distance', '<=', $zone_radius)
        ->get();

        return $customers;
    }

    /**
     * Admin Notification Inbox System
     */

    /**
     * Display admin notification inbox
     */
    public function inbox()
    {
        $admin = auth('admin')->user();
        $unreadCount = $admin->unreadNotifications()->count();
        
        return view('admin-views.notification.inbox', compact('unreadCount'));
    }

    /**
     * Fetch admin notifications with pagination
     */
    public function fetchInboxNotifications(Request $request)
    {
        try {
            $admin = auth('admin')->user();
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 15);
            $type = $request->input('type', 'all'); // all, unread, read
            
            $query = $admin->notifications();
            
            // Filter by type
            if ($type === 'unread') {
                $query->whereNull('read_at');
            } elseif ($type === 'read') {
                $query->whereNotNull('read_at');
            }
            
            $notifications = $query
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $notifications->items(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                    'has_more' => $notifications->hasMorePages()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a specific admin notification as read
     */
    public function markInboxAsRead(Request $request, $id)
    {
        try {
            $admin = auth('admin')->user();
            $notification = $admin->notifications()->findOrFail($id);
            
            if (!$notification->read_at) {
                $notification->markAsRead();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read!',
                'unread_count' => $admin->unreadNotifications()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all admin notifications as read
     */
    public function markAllInboxAsRead(Request $request)
    {
        try {
            $admin = auth('admin')->user();
            $admin->unreadNotifications()->update(['read_at' => now()]);
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read!',
                'unread_count' => 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific admin notification
     */
    public function deleteInboxNotification(Request $request, $id)
    {
        try {
            $admin = auth('admin')->user();
            $notification = $admin->notifications()->findOrFail($id);
            $notification->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully!',
                'unread_count' => $admin->unreadNotifications()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete all admin notifications
     */
    public function deleteAllInboxNotifications(Request $request)
    {
        try {
            $admin = auth('admin')->user();
            $type = $request->input('type', 'all'); // all, read
            
            $query = $admin->notifications();
            
            if ($type === 'read') {
                $query->whereNotNull('read_at');
            }
            
            $query->delete();
            
            return response()->json([
                'success' => true,
                'message' => $type === 'read' ? 'All read notifications deleted!' : 'All notifications deleted!',
                'unread_count' => $admin->unreadNotifications()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get admin unread notification count
     */
    public function getInboxUnreadCount(Request $request)
    {
        try {
            $admin = auth('admin')->user();
            $count = $admin->unreadNotifications()->count();
            
            return response()->json([
                'success' => true,
                'unread_count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get unread count',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display notification settings for admin
     */
    public function inboxSettings()
    {
        $admin = auth('admin')->user();
        
        // Get or create notification settings
        $settings = \App\Models\NotificationSetting::firstOrCreate(
            ['user_id' => $admin->id, 'user_type' => 'admin'],
            [
                'order_notifications' => true,
                'customer_notifications' => true,
                'restaurant_notifications' => true,
                'delivery_notifications' => true,
                'system_notifications' => true,
                'email_notifications' => true,
                'sound_notifications' => true,
            ]
        );
        
        return view('admin-views.notification.settings', compact('settings'));
    }

    /**
     * Update notification settings for admin
     */
    public function updateInboxSettings(Request $request)
    {
        try {
            $admin = auth('admin')->user();
            
            $settings = \App\Models\NotificationSetting::updateOrCreate(
                ['user_id' => $admin->id, 'user_type' => 'admin'],
                [
                    'order_notifications' => $request->boolean('order_notifications'),
                    'customer_notifications' => $request->boolean('customer_notifications'),
                    'restaurant_notifications' => $request->boolean('restaurant_notifications'),
                    'delivery_notifications' => $request->boolean('delivery_notifications'),
                    'system_notifications' => $request->boolean('system_notifications'),
                    'email_notifications' => $request->boolean('email_notifications'),
                    'sound_notifications' => $request->boolean('sound_notifications'),
                ]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Notification settings updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test method to send a notification to admin (remove in production)
     */
    public function testSendNotification()
    {
        try {
            $admin = auth('admin')->user();
            
            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not authenticated'
                ], 401);
            }
            
            // Use the notification service to send a test notification
            $notificationService = new \App\Services\NotificationService();
            $result = $notificationService->sendToAdmin(
                'Test Notification',
                'This is a test notification to verify your inbox system is working correctly. Generated at ' . now()->format('Y-m-d H:i:s'),
                'system',
                route('admin.dashboard'),
                ['test' => true],
                $admin->id
            );
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test notification sent successfully! Check your notification inbox.',
                    'redirect' => route('admin.notification.inbox')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send test notification through service'
                ], 500);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the notification test page
     */
    public function testPage()
    {
        return view('admin-views.notification.test');
    }

}
