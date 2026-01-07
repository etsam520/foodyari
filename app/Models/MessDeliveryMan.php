<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;

class MessDeliveryMan extends Model implements AuthenticatableContract
{
    use Authenticatable;
    protected $fillable = ['application_status','vehicle_id','shift_id','f_name','l_name','phone','email','address',
                            'identity_number','identity_type','identity_image','image','password','fcm_token','mess_id','status',
                            'status','active'];

    public function attendanceCheckLists()
    {
        return $this->belongsToMany(AttendaceCheckList::class, 'attendace_checklist_delivery_man','attendance_checklist_id','delivery_man_id');
    }
    
}
