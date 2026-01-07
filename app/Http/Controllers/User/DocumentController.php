<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function kyc()
    {
        $user = auth('user')->user();
        $kycDetails = UserMeta::get($user->id, 'kyc_details', []);
        $documents = Document::where('type', 'user_kyc')->where('status', 'active')->get();

        return response()->view('profile.kyc', compact('user', 'kycDetails', 'documents'));
    }

    public function updateKycDetails(Request $request)
    {
        $rules = [];
        $kycDocuments = Document::where('type', 'user_kyc')->where('status', 'active')->get();
        foreach ($kycDocuments as $document) {
            if ($document->is_text && $document->is_text_required) {
                $rules[$document->text_input_name] = 'required|string|max:255'; // Update with your specific rules
            }

            if ($document->is_media && $document->is_media_required) {
                $rules[$document->media_input_name] = 'required|integer'; // Update with your specific rules
            }
            if ($document->has_expiry_date) {
                $rules[$document->expire_date_input_name] = 'nullable|date'; // Update with your specific rules
            }
        }
        $validated = $request->validate($rules);
        $validated['status'] = 'pending';

        $user = User::findOrFail(auth('user')->id());
        $response = UserMeta::set($user->id, 'kyc_details', $validated);
        if ($response == 'success') {
            $user->kyc_status = $validated['status'];
            $user->save();
            return response('KYC details updated successfully');
        }
    }
}
