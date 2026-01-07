<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\BankingDetails;
use App\Models\BankingDetailsHistory;
use App\Traits\BankingDetailsValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BankingDetailsController extends Controller
{
    use BankingDetailsValidation;

    public function index(Request $request)
    {
        $bankingDetails = $this->getVendorBankingDetails();
        $histories = $this->getBankingHistories();
        
        return view('vendor-views.banking.index', compact('bankingDetails', 'histories'));
    }

    public function storeDetails(Request $request)
    {
        try {
            // Use trait validation
            $validator = $this->validateBankingDetails($request);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $vendorId = Session::get('restaurant')->vendor_id;

            // Start transaction
            DB::beginTransaction();

            // Sanitize input data
            $sanitizedData = $this->sanitizeBankingDetails($request);

            // Get existing banking details for comparison
            $existingDetails = BankingDetails::where('vendor_id', $vendorId)->first();

            // Create or update banking details
            $bankDetails = BankingDetails::updateOrCreate(
                ['vendor_id' => $vendorId],
                array_merge($sanitizedData, [
                    'data' => [
                        'payment_note' => $sanitizedData['payment_note'],
                        'last_updated_at' => now(),
                        'updated_from_ip' => $request->ip(),
                        'completeness_check' => $this->checkBankingDetailsCompleteness($sanitizedData),
                    ]
                ])
            );

            DB::commit();

            $message = $existingDetails ? 'Banking details updated successfully' : 'Banking details saved successfully';

            return response()->json([
                'message' => $message,
                'data' => $this->formatBankingDetailsForDisplay($bankDetails)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Failed to save banking details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDetails(Request $request)
    {
        try {
            $bankingDetails = $this->getVendorBankingDetails();
            
            // Return raw data for form editing, but include formatted version for display
            if ($bankingDetails) {
                $response = $bankingDetails->toArray();
                $response['formatted'] = $this->formatBankingDetailsForDisplay($bankingDetails);
                return response()->json($response);
            }
            
            return response()->json(null);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve banking details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDetails(Request $request, $id)
    {
        try {
            $vendorId = Session::get('restaurant')->vendor_id;
            
            $bankDetails = BankingDetails::where('id', $id)
                ->where('vendor_id', $vendorId)
                ->firstOrFail();

            // Use trait validation
            $validator = $this->validateBankingDetails($request);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Sanitize input data
            $sanitizedData = $this->sanitizeBankingDetails($request);

            $bankDetails->update(array_merge($sanitizedData, [
                'data' => array_merge($bankDetails->data ?? [], [
                    'payment_note' => $sanitizedData['payment_note'],
                    'last_updated_at' => now(),
                    'updated_from_ip' => $request->ip(),
                    'completeness_check' => $this->checkBankingDetailsCompleteness($sanitizedData),
                ])
            ]));

            return response()->json([
                'message' => 'Banking details updated successfully',
                'data' => $this->formatBankingDetailsForDisplay($bankDetails->fresh())
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update banking details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteDetails(Request $request, $id)
    {
        try {
            $vendorId = Session::get('restaurant')->vendor_id;
            
            $bankDetails = BankingDetails::where('id', $id)
                ->where('vendor_id', $vendorId)
                ->firstOrFail();

            $bankDetails->delete();

            return response()->json(['message' => 'Banking details deleted successfully']);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete banking details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getHistory(Request $request)
    {
        try {
            $histories = $this->getBankingHistories();
            
            return response()->json($histories);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve history: ' . $e->getMessage()
            ], 500);
        }
    }

    // Private helper methods
    private function getVendorBankingDetails()
    {
        $vendorId = Session::get('restaurant')->vendor_id;
        return BankingDetails::where('vendor_id', $vendorId)->first();
    }

    private function getBankingHistories()
    {
        $vendorId = Session::get('restaurant')->vendor_id;
        return BankingDetailsHistory::byVendor($vendorId)
            ->recent()
            ->take(50)
            ->get();
    }
}
