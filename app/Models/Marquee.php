<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marquee extends Model
{
    use HasFactory;

    public function scopeIsActive($query, $active = true)
    {
        return $query->where('status', $active);
    }
}
