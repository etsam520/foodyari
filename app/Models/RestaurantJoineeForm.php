<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantJoineeForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_no',
        'restaurant_name',
        'restaurant_address',
        'restaurant_phone',
        'restaurant_email',
        'restaurant_owner_name',
        'status',
        'approved_at',
        'restaurant_id'
    ];

    public function kyc()
    {
        return $this->hasOne(RestaurantKyc::class, 'joinee_form_id');
    }
}
