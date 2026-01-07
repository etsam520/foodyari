<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminToRestaurantSubscriptonPackageTXN extends Model
{
    use HasFactory;
    protected $fillable = ['id','package_details','package_id','restaurant_id','price','float','validity','payment_method','reference','paid_amount','discount','created_by'];
    
    protected $casts = [
        'id'=> 'string',
        'package_details' => 'array',
        'package_id'=>'integer',
        'restaurant_id'=>'integer',
        'price'=>'float',
        'validity'=>'integer',
        'payment_method'=>'string',
        'reference' => 'string',
        'paid_amount'=>'float',
        'discount' =>'float',
        'created_by' => 'string',
    ];

    public function restaurant()
    {
        return $this->hasOne(Restaurant::class,'id', 'restaurant_id');
    }
    public function package()
    {
        return $this->belongsTo(AdminToRestaurantSubscriptonPackage::class, 'package_id', 'id');
    }
}
