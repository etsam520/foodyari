<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessTiffin extends Model
{
    use HasFactory;

    
    protected $fillable = ['title', 'no', 'mess_id'];
}
