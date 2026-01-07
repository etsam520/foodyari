<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantKyc extends Model
{
    use HasFactory;
    protected $fillable = [
        'restaurant_id',
        'joinee_form_id',
        'status',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function joineeForm()
    {
        return $this->belongsTo(RestaurantJoineeForm::class);
    }
    public function documentDetails()
    {
        return $this->hasMany(DocumentDetails::class,'kyc_key' );
    }
}
