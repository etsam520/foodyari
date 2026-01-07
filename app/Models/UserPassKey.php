<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPassKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'user_id',
        'platform',
        'agent',
        'device_id',
        'device_brand',
        'device_model',
        'os_version',
        'app_version',
        'expire_at',
    ];

}
