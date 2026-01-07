<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['amount','txn_type','received_from','paid_to','wallet_id','customer_id','admin_fund_id',
                    'deliveryman_cash_in_hand_id','vendor_cash_in_hand_id','remarks','deteails'];

    protected function deliverymanCashInHand()
    {
        return $this->belongsTo(DeliveryManCashInHand::class);
    }

    protected function adminFund()
    {
        return $this->belongsTo(AdminFund::class);
    }
}
