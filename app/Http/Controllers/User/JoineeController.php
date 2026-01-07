<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\DeliveryManJoineeForm;
use App\Models\Document;
use App\Models\RestaurantJoineeForm;
use App\Models\RestaurantKyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class JoineeController extends Controller
{
    
    public function asRestaurant(){
        $documents = Document::where('type', 'restaurant_kyc')->where('status', 'active')->get();
        // dd($documents);
        return view('user-views.joinas.restaurant', compact('documents'));
    }

    public function joinAsRestaurantStore(Request $request)
    {
        

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('reCAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
        ]);
        $reCaptchadata = $response->json();

        if ($reCaptchadata['success'] && $reCaptchadata['score'] >= 0.5) {
            // success
    
            // dd($response->status() ,$response->() );
            $rules = [
                'restaurant_name' => 'required|string|max:255',
                'restaurant_phone' => 'required|string|max:15',
                'restaurant_email' => 'required|email|unique:restaurants,email',
                'restaurant_address' => 'required|string',
                'restaurant_owner_name' => 'required|string|max:255',
                ];
            $kycDocuments = Document::where('type', 'restaurant_kyc')->where('status', 'active')->get();
            foreach ($kycDocuments as $document) {
                if ($document->is_text && $document->is_text_required) {
                    $rules[$document->text_input_name] = 'required|string|max:255'; // Update with your specific rules
                }

                if ($document->is_media && $document->is_media_required) {
                    $rules[$document->media_input_name] = 'required|mimes:jpg,jpeg,png,pdf|max:10240'; // Update with your specific rules
                }
                if ($document->has_expiry_date) {
                    $rules[$document->expire_date_input_name] = 'nullable|date'; // Update with your specific rules
                }
            }
            try {
                $request->validate($rules);

                $regno = "FYARI-" . rand(100000, 999999);
                $joinAsRestaurant = RestaurantJoineeForm::create([
                    'registration_no' => $regno,
                    'restaurant_name' => $request->restaurant_name,
                    'restaurant_phone' => $request->restaurant_phone,
                    'restaurant_email' => $request->restaurant_email,
                    'restaurant_address' => $request->restaurant_address,
                    'restaurant_owner_name' => $request->restaurant_owner_name,
                    'status' => 'pending',
                ]);

                $kyc = $joinAsRestaurant->kyc()->create([
                    'status' => 'pending',
                ]);

                $documentdata = [];
                foreach ($kycDocuments as $document) {
                    $documentdata[] = [
                        'document_id' => $document->id,
                        'text_value' => $request[$document->text_input_name] ?? null,
                        'media_value' => Helpers::uploadFile($request->file($document->media_input_name), 'uploads/kyc'),
                        'expire_date' => $request[$document->expire_date_input_name] ?? null,
                        'status' => 'pending',
                        'associate' => 'restaurant',
                    ];
                }

                $documentDetails = $kyc->documentDetails()->createMany($documentdata);
                if($documentDetails){
                    return view('user-views.joinas._restaurant-success',compact('joinAsRestaurant','kyc','documentDetails'));
                    // return back()->with('success', 'Your request has been submitted successfully.');
                }
            } catch (\Exception $e) {
                dd($e);
                return back()->with('error', $e->getMessage());
            }
        } else {
            return back()->with('info', 'reCAPTCHA verification failed. Please try again.');
        }

    }

    public function asDeliveryMan(){
        $documents = Document::where('type', 'deliveryman_kyc')->where('status', 'active')->get();
        return view('user-views.joinas.deliveryman', compact('documents'));
    }

    public function joinAsDelivlerymanStore(Request $request)
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('reCAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
        ]);
        $reCaptchadata = $response->json();

        if (!($reCaptchadata['success'] && $reCaptchadata['score'] >= 0.5))  return back()->with('info', 'reCAPTCHA verification failed. Please try again.');

        $rules = [
            'dm_name' => 'required|string|max:255',
            'bike_no' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:restaurants,email',
            'dm_address' => 'required|string',
        ];
        $kycDocuments = Document::where('type', 'deliveryman_kyc')->where('status', 'active')->get();
        foreach ($kycDocuments as $document) {
            if ($document->is_text && $document->is_text_required) {
                $rules[$document->text_input_name] = 'required|string|max:255'; // Update with your specific rules
            }

            if ($document->is_media && $document->is_media_required) {
                $rules[$document->media_input_name] = 'required|mimes:jpg,jpeg,png,pdf|max:10240'; // Update with your specific rules
            }
            if ($document->has_expiry_date) {
                $rules[$document->expire_date_input_name] = 'nullable|date'; // Update with your specific rules
            }
        }
        try {
            $request->validate($rules);

            $regno = "FYARI-" . rand(100000, 999999);
            $joinasDeliveryman = DeliveryManJoineeForm::create([
                'registration_no' => $regno,
                'deliveryman_name' => $request->dm_name,
                'deliveryman_phone' => $request->phone,
                'deliveryman_email' => $request->email,
                'deliveryman_address' => $request->dm_address,
                'bike_number' => $request->bike_no,
                'status' => 'pending',
            ]);


            $kyc = $joinasDeliveryman->kyc()->create([
                'status' => 'pending',
            ]);

            $documentdata = [];
            foreach ($kycDocuments as $document) {
                $documentdata[] = [
                    'document_id' => $document->id,
                    'text_value' => $request[$document->text_input_name] ?? null,
                    'media_value' => Helpers::uploadFile($request->file($document->media_input_name), 'uploads/kyc'),
                    'expire_date' => $request[$document->expire_date_input_name] ?? null,
                    'status' => 'pending',
                    'associate' => 'deliveryman',
                ];
            }

            $documentDetails = $kyc->documentDetails()->createMany($documentdata);
            if($documentDetails){
                return view('user-views.joinas._deliveryman-success',compact('joinasDeliveryman','kyc','documentDetails'));
                // return back()->with('success', 'Your request has been submitted successfully.');
            }
        } catch (\Exception $e) {
            // dd($e);
            return back()->with('error', $e->getMessage());
        }

    }

}
