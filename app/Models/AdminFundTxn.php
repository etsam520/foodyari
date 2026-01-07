<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminFundTxn extends Model
{
    use HasFactory;
    protected $fillable = [ 'amount','admin_fund_id','txn_type','received_from','paid_to','vendor_id',
    'deliveryman_id','customer_id','restaurant_id','mess_id','remarks','deteails'];
   
    public function adminfund()
    {
        return $this->belongsTo(AdminFund::class);
    }
}
