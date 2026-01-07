<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessQR extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id','mess_id','attendance_checklist_id','mess_deliveryman_id','diet_coupon_id','checked_at','encrypted_code','otp'];


    public function ckecklist()
    {
        return $this->belongsTo(AttendaceCheckList::class , 'attendance_checklist_id');
    }

    public function coupon()
    {
        return $this->belongsTo(DietCoupon::class , 'diet_coupon_id');
    }
    
    

}
