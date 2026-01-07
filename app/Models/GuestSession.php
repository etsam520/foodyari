<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestSession extends Model
{
    use HasFactory;
    protected $table = 'guest_sessions';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'guest_id',
        'session_token',
        'ip_address',
        'device_info',
        'user_agent',
        'guest_location',

    ];
}
