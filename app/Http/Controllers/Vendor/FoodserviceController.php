<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\RestaurantServiceRequest;
use App\Models\Wallet;
use Google\Rpc\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FoodserviceController extends Controller
{
    public function index()
    {
        $restaurant = Session::get('restaurant');
        $serviceFoodRequests = RestaurantServiceRequest::where('restaurant_id',$restaurant->id)->orderBy('id', 'desc')->get();
        return view('vendor-views.services.food._request', compact('serviceFoodRequests'));
    }

        public function saveFoodRequest(Request $request)
        {
            // Define validation rules
            $request->validate([
                'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
                'pdf' => 'nullable|file|mimes:pdf|max:2048',
                'excel' => 'nullable|file|mimes:xls,xlsx,csv|max:2048',
                'notes' => 'nullable|string',
            ]);

            $restaurant = Session::get('restaurant');
            $serviceRequest = new RestaurantServiceRequest();

            // Handle file uploads
            if ($request->hasFile('image')) {
                $serviceRequest->image = Helpers::uploadFile($request->file('image'), 'foodRequest/');
            } elseif ($request->hasFile('pdf')) {
                $serviceRequest->pdf = Helpers::uploadFile($request->file('pdf'), 'foodRequest/');
            } elseif ($request->hasFile('excel')) {
                $serviceRequest->excel = Helpers::uploadFile($request->file('excel'), 'foodRequest/');
            }

            // Store additional fields in the model
            $serviceRequest->restaurant_id = $restaurant->id;
            $serviceRequest->restaurant_remarks = $request->notes;

            $notification = [
                'type' => 'Manual',
                'subject' => 'New Food Service Request',
                'body' => $request->notes,
                'message' => 'A new food service request has been submitted by ' . $restaurant->name,
            ];
            // Save the service request in the database
            $serviceRequest->save();
            Helpers::sendOrderNotification(Helpers::getAdmin(), $notification);

            // Return success message
            return back()->with('success', 'Files uploaded successfully.');
        }
}
