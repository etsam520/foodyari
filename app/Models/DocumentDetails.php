<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'document_id',
        'text_value',
        'media_value',
        'expire_date',
        'status',
        'kyc_key',
        'associate',
    ];
    public function document()
    {
        return $this->belongsTo(Document::class , 'document_id');
    }
    public function restaurantKyc()
    {
        return $this->belongsTo(RestaurantKyc::class, 'kyc_key')->where('associate', 'restaurant');
    }
    public function deliverymanKyc()
    {
        return $this->belongsTo(DeliverymanKyc::class, 'kyc_key ')->where('associate', 'deliveryman');
    }

    public function scopeGetRestaurantKyc($query)
    {
        return $query->where('associate', 'restaurant');
    }

    public function scopeGetDeliverymanKyc($query)
    {
        return $query->where('associate', 'deliveryman');
    }
}
