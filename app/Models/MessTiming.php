<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessTiming extends Model
{
    use HasFactory;
    // protected $timestamp = t;

    public function mess()
    {
        return $this->belongsTo(VendorMess::class);
    }
}
