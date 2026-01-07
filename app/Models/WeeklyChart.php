<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyChart extends Model
{
    use HasFactory;

    protected $fillable = ['week','day','breakfast','lunch','dinner','mess_id'];
   						
    public $timestamps = false;

}
