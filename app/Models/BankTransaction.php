<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['amount','txn_type','received_from','paid_to','wallet_id','customer_id','admin_fund_id',
                    'payment_method','remarks','deteails'];

    protected function adminFund()
    {
        return $this->belongsTo(AdminFund::class);
    }
}
