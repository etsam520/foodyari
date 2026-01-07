<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\DeliveryManJoineeForm;
use App\Models\DeliverymanKyc;
use App\Models\Document;
use App\Models\DocumentDetails;
use GPBMetadata\Google\Api\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log as FacadesLog;

class DeliverymanJoineeController extends Controller
{
    public function joinRequest() {
        $r_requests = DeliveryManJoineeForm::latest()->get();
        return view('admin-views.joinas.deliveryman._request-table', compact('r_requests'));
    }

    public function joinRequestShow($id) {
        $joinAsDeliveryman = DeliveryManJoineeForm::with('kyc')->findOrFail($id);
        $documentDetails = $joinAsDeliveryman->kyc->documentDetails()->with('document')->get();
        // dd($documents);

        return view('admin-views.joinas.deliveryman._view_page', compact('joinAsDeliveryman', 'documentDetails'));
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
        $deliverymanKyc = DeliverymanKyc::findOrFail($id);
        $documentsApproved = true;
        foreach($deliverymanKyc->documentDetails as $documentDetail){
            if($documentDetail->status !='approved'){
                $documentsApproved = false;
                break;
            }
        }
        if( $documentsApproved){
            $deliverymanKyc->update([
                'status' => $status,
            ]);
            return redirect()->back()->with('success', 'KYC Status Updated Successfully');
        }else{
            return redirect()->back()->with('error', 'All Documents are not approved yet');
        }
    }

    public function joinRequestFormUpdateStatus(Request $request, $id, $status){

        $joinAsDeliverymanForm = DeliveryManJoineeForm::findOrFail($id);
        // dd($joinAsRestaurantForm);
        $kyc = $joinAsDeliverymanForm->kyc;
        // dd($kyc);/
        if($status != 'approved'){
            $joinAsDeliverymanForm->update([
                'status' => $status,
            ]);
            return redirect()->back()->with('success', 'Form Status Rejected Successfully');
        }

        if($kyc->status != 'approved'){
            return redirect()->back()->with('error', 'KYC is not approved yet');
        }
        $joinAsDeliverymanForm->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'Form Status Approved Successfully');
    }


    public function createDeliveryman(Request $request, $id)
    {
        $joinAsDeliveryman = DeliveryManJoineeForm::findOrFail($id);

        try {
            DB::beginTransaction();

            $admin = Auth::guard('admin')->user();

            // Create new DeliveryMan
            $dm = new DeliveryMan();
            $dm->f_name = $joinAsDeliveryman->deliveryman_name;
            $dm->phone = $joinAsDeliveryman->deliveryman_phone;
            $dm->email = $joinAsDeliveryman->deliveryman_email;
            $dm->address = $joinAsDeliveryman->deliveryman_address;
            $dm->admin_id = $admin->id;
            $dm->type = 'admin';
            $dm->active = 0;
            $dm->password = bcrypt($request->password);
            $dm->save();

            // Update the join form with the new deliveryman ID
            $joinAsDeliveryman->update([
                'deliveryman_id' => $dm->id
            ]);

            // Update associated KYC
            DeliverymanKyc::where('joinee_form_id', $joinAsDeliveryman->id)
                ->update(['deliveryman_id' => $dm->id]);

            DB::commit();

            return back()->with('success', 'Deliveryman created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            FacadesLog::error('Deliveryman creation failed: ' . $th->getMessage());
            return back()->with('error', 'Failed to create deliveryman: ' . $th->getMessage());
        }
    }

    public function deleteJoinRequest(Request $request, $id){
        $joinAsDeliveryman = DeliveryManJoineeForm::findOrFail($id);
        try {
            DB::beginTransaction();
            // Delete associated documents
            $kyc = DeliverymanKyc::where('joinee_form_id', $joinAsDeliveryman->id)->first();
            if ($kyc) {
                foreach ($kyc->documentDetails as $documentDetail) {
                    // Delete media file if exists
                    if ($documentDetail->media_value && file_exists(public_path($documentDetail->media_value))) {
                        unlink(public_path($documentDetail->media_value));
                    }
                    $documentDetail->delete();
                }
                $kyc->delete();
            }
            // Delete the join request
            $joinAsDeliveryman->delete();

            DB::commit();
            return back()->with('success', 'Deliveryman Join Request Deleted Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete join request: ' . $th->getMessage());
        }
    }

}


