<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentDetails;
use App\Models\Restaurant;
use App\Models\RestaurantJoineeForm;
use App\Models\RestaurantKyc;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Comment\Doc;

class RestaurantJoineeController extends Controller
{
    //
    public function joinRequest() {
        $r_requests = RestaurantJoineeForm::latest()->get();
        return view('admin-views.joinas.restaurant._request-table', compact('r_requests'));
    }

    public function joinRequestShow($id) {
        $joinAsRestaurant = RestaurantJoineeForm::with('kyc')->findOrFail($id);
        $documentDetails = DocumentDetails::with('document')
                    ->where('kyc_key', $joinAsRestaurant->kyc->id)
                    ->get();
        return view('admin-views.joinas.restaurant._view_page', compact('joinAsRestaurant', 'documentDetails'));
    }

    public function joinRequestDocUpdate(Request $request) {
        $rules = [
            'document_id' => 'required|exists:documents,id',
            'document_detail_id' => 'required',
        ];
        $kycDocument = Document::findOrFail($request->document_id);
        if ($kycDocument->is_text && $kycDocument->is_text_required) {
            $rules[$kycDocument->text_input_name] = 'required|string|max:255'; // Update with your specific rules
        }

        if ($kycDocument->is_media && $kycDocument->is_media_required) {
            $rules[$kycDocument->media_input_name] = 'nullable|mimes:jpg,jpeg,png,pdf|max:10240'; // Update with your specific rules
        }
        if ($kycDocument->has_expiry_date) {
            $rules[$kycDocument->expire_date_input_name] = 'nullable|date'; // Update with your specific rules
        }

        $validatedData = $request->validate($rules);
        try{
            // $request->validate($rules);
            $documentDetail = DocumentDetails::where('id', $request->document_detail_id)->first();
            $updatedata ['text_value'] = $request[$kycDocument->text_input_name] ?? null;
            if($request->hasFile($kycDocument->media_input_name)){
                $updatedata ['media_value'] = Helpers::updateFile($request->file($kycDocument->media_input_name),'uploads/kyc', $documentDetail->media_value);
            }
            if($request->has($kycDocument->expire_date_input_name)){
                $updatedata ['expire_date'] = $request[$kycDocument->expire_date_input_name] ?? null;
            }

            $documentDetail->update($updatedata);
            return redirect()->back()->with('success', 'Document Updated Successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput($validatedData);
        }
    }

    public function joinRequestDocUpdateStatus(Request $request, $id, $status){
        $documentDetail = DocumentDetails::where('id', $id)->first();
        $documentDetail->update([
            'status' => $status,
            'approved_at' => $status== "approved"? now():null,
        ]);
        return redirect()->back()->with('success', 'Document Status Updated Successfully');
    }

    public function joinRequestKycUpdateStatus(Request $request, $id, $status){
        $restaurantKyc = RestaurantKyc::findOrFail($id);
        $documentsApproved = true;
        foreach($restaurantKyc->documentDetails as $documentDetail){
            if($documentDetail->status !='approved'){
                $documentsApproved = false;
                break;
            }
        }
        if( $documentsApproved){
            $restaurantKyc->update([
                'status' => $status,
            ]);
            return redirect()->back()->with('success', 'KYC Status Updated Successfully');
        }else{
            return redirect()->back()->with('error', 'All Documents are not approved yet');
        }
    }

    public function joinRequestFormUpdateStatus(Request $request, $id, $status){

        $joinAsRestaurantForm = RestaurantJoineeForm::findOrFail($id);
        // dd($joinAsRestaurantForm);
        $kyc = $joinAsRestaurantForm->kyc;
        if($status != 'approved'){
            $joinAsRestaurantForm->update([
                'status' => $status,
            ]);
            return redirect()->back()->with('success', 'Form Status Rejected Successfully');
        }

        if($kyc->status != 'approved'){
            return redirect()->back()->with('error', 'KYC is not approved yet');
        }
        $joinAsRestaurantForm->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'Form Status Approved Successfully');
    }

    public function createRestaurant(Request $request, $id){
        $joinAsRestaurant = RestaurantJoineeForm::findOrFail($id);
        try {
            DB::beginTransaction();
            if($joinAsRestaurant != null){
                $vendor = new Vendor();
                $vendor->f_name = $joinAsRestaurant->restaurant_owner_name;
                $vendor->email = $joinAsRestaurant->restaurant_email;
                $vendor->phone = $joinAsRestaurant->restaurant_phone;
                $vendor->password = bcrypt('password1234');
                $vendor->save();
                if($vendor){
                    $restaurant = new Restaurant();
                    $restaurant->name = $joinAsRestaurant->restaurant_name;
                    $restaurant->email = $joinAsRestaurant->restaurant_email;
                    $restaurant->address = json_encode([
                                                'street' => $joinAsRestaurant->restaurant_address,
                                            ]);
                    $restaurant->vendor_id = $vendor->id;
                    $restaurant->save();
                }
                $joinAsRestaurant->update([
                    'restaurant_id' => $restaurant->id,
                ]);
                $joinAsRestaurant->kyc->update([
                    'restaurant_id' => $restaurant->id,
                ]);
            }
            DB::commit();
            return back()->with('success', 'Restaurant Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function deleteJoinRequest(Request $request, $id){
        $joinAsRestaurant = RestaurantJoineeForm::findOrFail($id);
        try {
            DB::beginTransaction();
            // Delete associated documents
            if($joinAsRestaurant->kyc){
                foreach($joinAsRestaurant->kyc->documentDetails as $documentDetail){
                    // Delete media file if exists
                    if($documentDetail->media_value){
                        Helpers::deleteFile($documentDetail->media_value);
                    }
                    $documentDetail->delete();
                }
                $joinAsRestaurant->kyc->delete();
            }
            // Delete the join request
            $joinAsRestaurant->delete();

            DB::commit();
            return back()->with('success', 'Join Request Deleted Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
