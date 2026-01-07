<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = ['id','balance','customer_id','deliveryman_id','vendor_id','admin_id','created_at'];

    public function customer()
    {

        return $this->belongsTo(Customer::class);
    }
    public function delivery_man()
    {

        return $this->belongsTo(DeliveryMan::class);
    }

    public function WalletTransactions ()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
