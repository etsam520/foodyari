<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryManCashInHand extends Model
{
    use HasFactory;
    protected $fillable = ['deliveryman_id', 'balance'];

    public function cashTxns()
    {
        return $this->hasMany(CashTransaction::class,'deliveryman_cash_in_hand_id');
    }
}
