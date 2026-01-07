<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;
    protected $fillable = ['id','customer_id','type','address','latitude','longitude','phone', 'is_default', 'created_at','updated_at'];
    


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeGetDistance($query, $latitude, $longitude)
{
    $haversine = "(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude))))";

    return $query->select('*')
        ->selectRaw("{$haversine} AS distance")
        ->orderBy('distance');
}
}
