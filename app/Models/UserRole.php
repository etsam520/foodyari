<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'role','status'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function admins(){
        return $this->hasMany(Admin::class,'role_id');
    }
}
