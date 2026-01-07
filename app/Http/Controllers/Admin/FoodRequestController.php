<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\RestaurantServiceRequest;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodRequestController extends Controller
{
    public function list()
    {
        $paymentRequests = PaymentRequest::with('vendor')->latest()->get();
        $serviceFoodRequests = RestaurantServiceRequest::with('restaurant.vendor')->orderBy('id', 'desc')->get();
        // dd($serviceFoodRequests);
        // dd($paymentRequests);
        return view('admin-views.food.request._index', compact('paymentRequests','serviceFoodRequests'));
    }

    public function requestform(Request $request)
    {
        $request_key = $request->query('request_key');
        $serviceFoodRequest = RestaurantServiceRequest::with('restaurant.vendor')->find($request_key);

        // dd($serviceFoodRequest);
        return response()->json([
            'view' => view('admin-views.food.request._form',compact('serviceFoodRequest',))->render()
        ]);
    }

    public function requestformSave(Request $request)
    {
        try {
            // Debugging request data
            // dd($request->all());
            $request->validate([
                'attachement' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
                'status' => 'required',
                'remarks' => 'required',
            ]);
            DB::beginTransaction();

            $request_id = $request->request_id;
            $serviceFoodRequest = RestaurantServiceRequest::with('restaurant.vendor')->find($request_id);
            if ($request->hasFile('attachement')) {
                $serviceFoodRequest->attachement = Helpers::uploadFile($request->file('attachement'), 'foodRequest/');
            }
            $serviceFoodRequest->admin_remarks = $request->remarks ;
            $serviceFoodRequest->status = $request->status ;
            $serviceFoodRequest[$request->status] = now() ;
            $serviceFoodRequest->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Changes Applied.']);

        } catch (\Exception $e) {
            DB::rollBack();
            // Log error or handle exception
            return response()->json(['success' => false, 'message' => 'Error updating Changes request.', 'error' => $e->getMessage()], 500);
        }
    }
}
