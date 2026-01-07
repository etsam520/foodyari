<?php

namespace App\Models;

use App\CentralLogics\Helpers;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
//use Spatie\Permission\Traits\HasRoles;

class Vendor extends Model implements AuthenticatableContract
{
    // use HasFactory;

    use Authenticatable;

    protected $fillable = [
        'f_name', 'l_name', 'phone', 'email', 'password', 'status', 'is_blocked', 'blocked_at', 'blocked_reason'
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_blocked' => 'boolean',
        'blocked_at' => 'datetime',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeNotBlocked($query)
    {
        return $query->where('is_blocked', 0);
    }


    public function restaurants()
    {
        $admin = Helpers::getAdmin();
        // $admin->assignRole('admin');
        return $this->hasMany(Restaurant::class,'vendor_id');
    }

    public function messes()
    {
        return $this->hasMany(VendorMess::class,'vendor_id');
    }
}
