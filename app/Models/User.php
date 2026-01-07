<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['f_name','l_name','phone','email','image','password','email_verification_token','role_id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function vendorEmployees()
    {
        return $this->hasMany(VendorEmployee::class);
    }

    public function role()
    {
        return $this->belongsTo(UserRole::class);
    }

    public function customersInfo()
    {
        return $this->hasMany(Customer::class);
    }

    public function scopeCustomers($query , $select = null)
    {
        if(!empty($select)){
          return $query->select($select)->whereHas('role', function($query) {
            $query->whereIn('role', ['customer']);
        });  
        }
        return $query->select('id','f_name','l_name')->whereHas('role', function($query) {
            $query->whereIn('role', ['customer']);
        });
    }
}
