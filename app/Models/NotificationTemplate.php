<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'file', 'target', 'targetZone', 'targetClient'];

    public function getTargetAttribute($value)
    {
        return json_decode($value);
    }
    
}
