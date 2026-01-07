<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessService extends Model
{
    use HasFactory;

    public function messMenus()
    {
        return $this->belongsToMany(MessMenu::class);
    }

    public function cheklists(){
        return $this->hasMany(AttendaceCheckList::class);
    }
}
