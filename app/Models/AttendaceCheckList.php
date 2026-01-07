<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psy\CodeCleaner\ReturnTypePass;

class AttendaceCheckList extends Model
{
    use HasFactory;
    protected $fillable = ['id',	'attendance_time',	'coupon_id','attendance_date','addons',	'service','sign_to_delivery' , 'checked'	,'attendace_id'];

    public function attendance()
    {
       return $this->belongsTo(Attendance::class, 'attendance_id');
    }

    public function messservice(){
      return  $this->belongsTo(MessService::class,'service_id');
    }

    public function allotTodeliveryMen()
    {
        return $this->belongsToMany(MessDeliveryMan::class, 'attendace_checklist_delivery_man');
    }

    public function coupon()
    {
      return $this->belongsTo(DietCoupon::class, 'coupon_id');
    }

    public function qr()
    {
      return $this->hasOne(MessQR::class);
    }
}
