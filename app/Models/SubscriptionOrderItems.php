<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionOrderItems extends Model
{
    use HasFactory;
    protected $fillable  = ['id',	'order_id',	'price','product_id','quantity',	'created_at',	'updated_at',	];

    public function package(){
        return $this->belongsTo(Subscription::class,'product_id');
    }
}
