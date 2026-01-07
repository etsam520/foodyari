<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessMenu extends Model
{
    use HasFactory;

    protected $fillable = ['id',	'name',	'image',	'description',	'addons',	'available_time_starts',	'available_time_ends',	'status',	'veg',	'mess_id'];
    


    public function messServices()
    {
        return $this->belongsToMany(MessService::class);
    }
}
