<?php

namespace App\Models;

use App\CentralLogics\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Log;

class DeliveryMan extends Model implements AuthenticatableContract
{
    use HasFactory, Notifiable,Authenticatable;

    protected $table = 'delivery_men';
    protected $fillable = [
        'application_status',
        'vehicle_id',
        'shift_id',
        'f_name',
        'l_name',
        'phone',
        'email',
        'identity_number',
        'identity_type',
        'identity_image',
        'image',
        'password',
        'auth_token',
        'fcm_token',
        'remember_token',
        'remember_token_created_at',
        'address',
        'type',
        'zone_id',
        'admin_id',
        'restaurant_id',
        'mess_id',
        'status',
        'active',
        'earning',
        'available',
        'fuel_rate',
        'gender',
        'dob',
        'marital_status',
        'anniversary_date',
        'blood_group',
    ];

    protected $guarded = [];

    protected $casts = [
        'vehicle_id' => 'integer',
        'zone_id' => 'integer',
        'status'=>'boolean',
        'active'=>'integer',
        'available'=>'integer',
        'earning'=>'float',
        'restaurant_id'=>'integer',
        'current_orders'=>'integer',
        'vehicle_id'=>'integer',
        'shift_id' => 'integer',
        'fcm_token' => 'string',
    ];

    protected $hidden = [
        'password',
        'auth_token',
    ];

    // public function wallet()
    // {
    //     return $this->hasOne(DeliveryManWallet::class);
    // }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function scopeZonewise($query)
    {

        return $query->where('type','zone_wise');
    }

    public function time_logs()
    {
        return $this->hasMany(TimeLog::class, 'user_id', 'id')->with('shift');
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'delivery_man_id');
    }
    public function delivery_history()
    {
        return $this->hasMany(DeliveryHistory::class, 'delivery_man_id');
    }

    public function last_location()
    {
        return $this->hasOne(DeliveryHistory::class, 'delivery_man_id')->latest();
    }

    // public function attendance()
    // {
    //     return $this->hasMany(DeliverymanAttendance::class);
    // }
    public function attendances()
    {
        return $this->hasMany(DeliverymanAttendance::class, 'deliveryman_id','id');
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'deliveryman_id');
    }

    public function scopeIsActive($query, $status= true)
    {
        return $query->where('status',$status);
    }

    public function scopeDeliverymanType($query, $type = 'admin')
    {
        return $query->where('type',$type);
    }


    public function kyc()
    {
        return $this->hasOne(DeliverymanKyc::class, 'deliveryman_id'); // Ensure 'delivery_man_id' is the correct foreign key
    }


    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    /**
     * Get the payouts for the delivery man.
     */
    public function payouts()
    {
        return $this->hasMany(DeliveryManPayout::class, 'delivery_man_id');
    }
}
