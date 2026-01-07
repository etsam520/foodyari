<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class VendorEmployee extends Model
{
    use HasFactory , Notifiable, HasRoles, HasPermissions;
    protected $fillable = ['id','user_id','vendor_id','restaurant_id','status','address'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
