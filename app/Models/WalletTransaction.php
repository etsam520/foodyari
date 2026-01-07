<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['amount','wallet_id','customer_id','deliveryman_id'
    ,'admin_id','mess_id','restaurant_id','type','deteails','remarks','id'];
   

    public function wallet ()
    {
        return $this->belongsTo(Wallet::class);
    }
}
