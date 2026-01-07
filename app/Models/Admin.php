<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable, HasRoles , HasPermissions;

    protected $table = 'admins'; // Replace with your actual table name
    protected $fillable = ['id','f_name','l_name','phone','email','fcm_token','image','password','role_id','zone_id'] ;
     protected $guard_name = 'admin';




    // public function role(){
    //     return $this->belongsTo(UserRole::class);
    // }

    public function scopeZone($query)
    {
        if(isset(auth('admin')->user()->zone_id))
        {
            return $query->where('zone_id', auth('admin')->user()->zone_id);
        }
        return $query;
    }

    public function userinfo()
    {
        return $this->hasOne(UserInfo::class,'admin_id', 'id');
    }

    public function deliveryMan(){
        return $this->hasMany(DeliveryMan::class, 'admin_id');
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    // Chat relationships
    public function sentMessages()
    {
        return $this->morphMany(Message::class, 'sender');
    }

    public function sentConversations()
    {
        return $this->morphMany(Conversation::class, 'sender');
    }

    public function receivedConversations()
    {
        return $this->morphMany(Conversation::class, 'receiver');
    }

    public function conversations()
    {
        return $this->sentConversations()->union($this->receivedConversations());
    }

    public function getFullNameAttribute()
    {
        return $this->f_name . ' ' . $this->l_name;
    }

    /**
     * Get the payouts processed by this admin.
     */
    public function processedPayouts()
    {
        return $this->hasMany(DeliveryManPayout::class, 'admin_id');
    }
}
