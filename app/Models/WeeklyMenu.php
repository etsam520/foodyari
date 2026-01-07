<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyMenu extends Model
{
    use HasFactory;

    protected $fillable =["id","name","type","description","image","addons","service","day","mess_id","status"];    
}
