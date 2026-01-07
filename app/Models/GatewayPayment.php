<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewayPayment extends Model
{
    use HasFactory;
    protected $fillable = ['amount','payment_status','txn_id','merchant_txn_id','currency','gateway',
                            'assosiate','assosiate_id','responseCode','payload','details'];


// 'amount'=> ,
// 'payment_status'=> ,
// 'txn_id'=> ,
// 'merchant_txn_id'=> ,
// 'currency'=> ,
// 'gateway'=> ,
// 'assosiate'=> ,
// 'assosiate_id'=> ,
// 'responseCode'=> ,
// 'payload'=> ,
// 'details'=> ,
}
