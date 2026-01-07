<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAccountTxn extends Model
{
    use HasFactory ;
   protected $fillable = [ 'amount','txn_type','received_from','paid_to','wallet_id',
   'admin_fund_id','cash_account_id','remarks','deteails'];
           
}
