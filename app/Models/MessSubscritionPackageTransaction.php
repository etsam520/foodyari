<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessSubscritionPackageTransaction extends Model
{
    protected $fillable = ['id','order_id','mess_id'
    ,'state','meal_collection_type',
    'payment_details_id','special_note','product_id',
    'delivery_address','coordinates','customer_id',
    'created_at','updated_at'];
    

    use HasFactory;
}
