<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessCharges extends Model
{
    use HasFactory;
    protected $fillable = ['mess_id','GST','mess_charge','mess_charge_type','admin_charge','admin_charge_type','delivery_man_charge','delivery_man_charge_type'];
    public $timestamps = false;
}
