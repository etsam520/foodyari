<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCalculationStatement extends Model
{
    use HasFactory;

    protected $fillables = ['order_id','customerData','restaurantData','adminData'];
    public $timestamps = true ;

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }



}
