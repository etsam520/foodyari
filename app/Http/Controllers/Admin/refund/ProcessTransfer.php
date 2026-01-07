<?php
namespace App\Http\Controllers\Admin\refund;

use App\Models\Admin;
use App\Models\AdminFund;
use App\Models\BankTransaction;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Wallet;
use Illuminate\Http\Request;

class ProcessTransfer
{
    protected $refund;
    protected $order;
    protected $customer;
    protected $admin;

    public function __construct(Refund $refund, Order $order, Customer $customer, Admin $admin)
    {
        $this->refund = $refund;
        $this->order = $order;
        $this->customer = $customer;
        $this->admin = $admin;
    }

    // Process refund based on method
    public function handle(Request $request)
    {
        if ($request['refund_method'] === Refund::METHOD_WALLET) {
            $this->processWalletRefund($this->refund, $this->customer);
        }
        if ($request['refund_method'] === Refund::METHOD_BANK_TRANSFER) {
            // Logic for bank transfer refund
            $this->refund->update([
                'refund_status' => Refund::STATUS_PROCESSED,
                'transaction_reference' => 'BANKTRANS_' . time()
            ]);
            $this->processBankTransferRefund($this->refund);
        }
        if ($request['refund_method'] === Refund::METHOD_ORIGINAL_PAYMENT) {
            // Logic for original payment refund (e.g., via payment gateway)
            // This is a placeholder; actual implementation would depend on the payment gateway used
            $this->refund->update([
                'refund_status' => Refund::STATUS_PROCESSED,
                'transaction_reference' => 'ORIGPAY_' . time()
            ]);
            $this->processOriginalPaymentRefund($this->refund);
        }
    }



    // Process wallet refund
    private function processWalletRefund(Refund $refund, $customer)
    {
        // Get customer wallet
        $customerWallet = Wallet::firstOrCreate(['customer_id' => $customer->id]);
        $vendorWallet = Wallet::where(['vendor_id' => $this->order->restaurant->vendor_id])->first();

        
        // Add refund amount to wallet
        $customerWallet->balance += (double) $refund->refund_amount;
        $customerWallet->save();

        // Create wallet transaction
        $customerWallet->walletTransactions()->create([
            'amount' => (double) $refund->refund_amount,
            'type' => 'received',
            'customer_id' => $customer->id,
            'remarks' => "Refund for Order #{$refund->order->id} - {$refund->refund_reason}",
        ]);

        // Deduct from admin fund
        $adminFund = AdminFund::getFund();
        $adminFund->balance -= (double) $refund->refund_amount;
        $adminFund->save();

        $adminFund->txns()->create([
            'amount' => (double) $refund->refund_amount,
            'txn_type' => 'paid',
            'paid_to' => 'customer',
            'customer_id' => $customer->id,
            'remarks' => "Refund processed for Order #{$refund->order->id} to {$customer->f_name} {$customer->l_name}"
        ]);

        // If vendor wallet exists, deduct from vendor wallet
        if ($vendorWallet) {
            $vendorWallet->balance -= (double) $refund->restaurant_deduction_amount;
            $vendorWallet->save();

            $vendorWallet->walletTransactions()->create([
                'amount' => (double) $refund->restaurant_deduction_amount,
                'type' => 'paid',
                'admin_id' => $this->admin->id,
                'restaurant_id' => $this->order->restaurant->id,
                'vendor_id' => $this->order->restaurant->vendor_id,
                'remarks' => "Refund processed for Order #{$refund->order->id} to {$customer->f_name} {$customer->l_name}"
            ]);

            // Add to admin fund for vendor deduction
            $adminFund = AdminFund::getFund();
            $adminFund->balance += (double) $refund->restaurant_deduction_amount;
            $adminFund->save();

            $adminFund->txns()->create([
                'amount' => (double) $refund->restaurant_deduction_amount,
                'txn_type' => 'received',
                'paid_to' => 'vendor',
                'restaurant_id' => $this->order->restaurant->id,
                'remarks' => "Refund processed for Order #{$refund->order->id} to {$customer->f_name} {$customer->l_name}"
            ]);
        }

        // Update refund status to processed
        $refund->update([
            'refund_status' => Refund::STATUS_PROCESSED,
            'transaction_reference' => 'WALLET_' . time()
        ]);
    }

    private function processOriginalPaymentRefund(Refund $refund)
    {
        // Placeholder for original payment refund logic
        // Actual implementation would depend on the payment gateway used
        $refund->update([
            'refund_status' => Refund::STATUS_PROCESSED,
            'transaction_reference' => 'ORIGPAY_' . time()
        ]);
        if(preg_match('/wallet/i', $this->order->payment_method)) {
            $this->processWalletRefund($refund, $this->customer);
        } else{
            $this->processBankTransferRefund($refund);
        }
    }

    private function processBankTransferRefund(Refund $refund)
    {
        // Placeholder for bank transfer refund logic
        // Actual implementation would depend on the bank transfer process
        $refund->update([
            'refund_status' => Refund::STATUS_PROCESSED,
            'transaction_reference' => 'BANKTRANS_' . time()
        ]);

        // GET VENDOR WALLET
        $vendorWallet = Wallet::where(['vendor_id' => $this->order->restaurant  ->vendor_id])->first();

        // Deduct from admin fund
        $adminFund = AdminFund::getFund();
        $adminFund->balance -= (double) $refund->refund_amount;
        $adminFund->save();

        $adminFund->txns()->create([
            'amount' => (double) $refund->refund_amount,
            'txn_type' => 'paid',
            'paid_to' => 'customer',
            'customer_id' => $this->customer->id,
            'remarks' => "Refund processed for Order #{$refund->order->id} to {$this->customer->f_name} {$this->customer->l_name}"
        ]);
        

        BankTransaction::create([
            'amount' =>(double) $refund->refund_amount,
            'txn_type' => 'paid',
            'paid_to' => 'customer',
            'customer_id' => $this->customer->id,
            'remarks' => "Refund processed for Order #{$refund->order->id} to {$this->customer->f_name} {$this->customer->l_name}",
            'admin_fund_id' => $adminFund->id,
            'payment_method' => 'bank_transfer',
            'deteails' => json_encode([
                'transaction_reference' => $refund->transaction_reference,
                'bank_details' => $this->customer->bank_details
            ])
        ]);

         // If vendor wallet exists, deduct from vendor wallet
         if ($vendorWallet) {
            $vendorWallet->balance -= (double) $refund->restaurant_deduction_amount;
            $vendorWallet->save();

            $vendorWallet->walletTransactions()->create([
                'amount' => (double) $refund->restaurant_deduction_amount,
                'type' => 'paid',
                'admin_id' => $this->admin->id,
                'restaurant_id' => $this->order->restaurant->id,
                'remarks' => "Refund processed for Order #{$refund->order->id} to {$this->customer->f_name} {$this->customer->l_name}"
            ]);

            // Add to admin fund for vendor deduction
            $adminFund = AdminFund::getFund();
            $adminFund->balance += (double) $refund->restaurant_deduction_amount;
            $adminFund->save();

            $adminFund->txns()->create([
                'amount' => $refund->restaurant_deduction_amount,
                'txn_type' => 'received',
                'paid_to' => 'vendor',
                'restaurant_id' => $this->order->restaurant->id,
                'remarks' => "Refund processed for Order #{$refund->order->id} to {$this->customer->f_name} {$this->customer->l_name}"
            ]);
        }
    }
}