<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait BankingDetailsValidation
{
    /**
     * Validate banking details request
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateBankingDetails(Request $request)
    {
        return Validator::make($request->all(), [
            'bank_name' => 'nullable|string|max:255',
            'ifsc_code' => [
                'nullable',
                'string',
                'max:11',
                'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
                'required_if:account_number,!=,null'
            ],
            'account_number' => [
                'required_without:upi_id',
                'nullable',
                'string',
                'max:20',
                'min:9',
                'regex:/^[0-9]+$/'
            ],
            'account_holder_name' => [
                'required_without:upi_id',
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\.]+$/'
            ],
            'upi_id' => [
                'required_without:account_number',
                'nullable',
                'string',
                'max:255',
                'regex:/^[\w\.\-_]{2,256}@[a-zA-Z]{2,64}$/'
            ],
            'payment_note' => 'nullable|string|max:500',
        ], [
            'ifsc_code.regex' => 'IFSC code format is invalid. Example: SBIN0001234',
            'ifsc_code.required_if' => 'IFSC code is required when account number is provided',
            'account_number.regex' => 'Account number should contain only digits',
            'account_number.min' => 'Account number must be at least 9 digits',
            'account_number.max' => 'Account number cannot exceed 20 digits',
            'account_number.required_without' => 'Account number is required when UPI ID is not provided',
            'account_holder_name.regex' => 'Account holder name should contain only letters, spaces, and dots',
            'account_holder_name.required_without' => 'Account holder name is required when UPI ID is not provided',
            'upi_id.regex' => 'UPI ID format is invalid. Example: user@bank',
            'upi_id.required_without' => 'UPI ID is required when account number is not provided',
            'payment_note.max' => 'Payment note cannot exceed 500 characters',
        ]);
    }

    /**
     * Sanitize banking details input
     *
     * @param Request $request
     * @return array
     */
    public function sanitizeBankingDetails(Request $request)
    {
        return [
            'bank_name' => $request->filled('bank_name') ? trim($request->bank_name) : null,
            'ifsc_code' => $request->filled('ifsc_code') ? strtoupper(trim($request->ifsc_code)) : null,
            'account_number' => $request->filled('account_number') ? trim($request->account_number) : null,
            'account_holder_name' => $request->filled('account_holder_name') ? strtoupper(trim($request->account_holder_name)) : null,
            'upi_id' => $request->filled('upi_id') ? strtolower(trim($request->upi_id)) : null,
            'payment_note' => $request->filled('payment_note') ? trim($request->payment_note) : null,
        ];
    }

    /**
     * Check if banking details are complete
     *
     * @param array $details
     * @return array
     */
    public function checkBankingDetailsCompleteness(array $details)
    {
        $hasBank = !empty($details['account_number']) && 
                   !empty($details['ifsc_code']) && 
                   !empty($details['account_holder_name']);
        
        $hasUPI = !empty($details['upi_id']);
        
        return [
            'has_bank_account' => $hasBank,
            'has_upi' => $hasUPI,
            'is_complete' => $hasBank || $hasUPI,
            'missing_fields' => $this->getMissingFields($details),
        ];
    }

    /**
     * Get missing required fields
     *
     * @param array $details
     * @return array
     */
    private function getMissingFields(array $details)
    {
        $missing = [];
        
        $hasAccount = !empty($details['account_number']);
        $hasUPI = !empty($details['upi_id']);
        
        // If neither account nor UPI is provided
        if (!$hasAccount && !$hasUPI) {
            $missing[] = 'Either bank account details or UPI ID is required';
            return $missing;
        }
        
        // If account number is provided, check for other bank details
        if ($hasAccount) {
            if (empty($details['ifsc_code'])) {
                $missing[] = 'IFSC Code';
            }
            if (empty($details['account_holder_name'])) {
                $missing[] = 'Account Holder Name';
            }
            if (empty($details['bank_name'])) {
                $missing[] = 'Bank Name (recommended)';
            }
        }
        
        return $missing;
    }

    /**
     * Format banking details for display
     *
     * @param mixed $bankingDetails
     * @return array
     */
    public function formatBankingDetailsForDisplay($bankingDetails)
    {
        if (!$bankingDetails) {
            return null;
        }

        $formatted = [
            'id' => $bankingDetails->id ?? null,
            'account_holder_name' => $bankingDetails->account_holder_name,
            'bank_name' => $bankingDetails->bank_name,
            'ifsc_code' => $bankingDetails->ifsc_code,
            'upi_id' => $bankingDetails->upi_id,
            'created_at' => $bankingDetails->created_at,
            'updated_at' => $bankingDetails->updated_at,
        ];

        // Mask account number for security
        if ($bankingDetails->account_number) {
            $accountNumber = $bankingDetails->account_number;
            $formatted['account_number_masked'] = str_repeat('*', strlen($accountNumber) - 4) . substr($accountNumber, -4);
            $formatted['account_number_full'] = $accountNumber; // Only for form editing
        }

        // Add completeness status
        $completeness = $this->checkBankingDetailsCompleteness([
            'account_number' => $bankingDetails->account_number,
            'ifsc_code' => $bankingDetails->ifsc_code,
            'account_holder_name' => $bankingDetails->account_holder_name,
            'bank_name' => $bankingDetails->bank_name,
            'upi_id' => $bankingDetails->upi_id,
        ]);

        $formatted['completeness'] = $completeness;

        return $formatted;
    }
}