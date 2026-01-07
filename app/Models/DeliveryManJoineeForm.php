<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryManJoineeForm extends Model
{
    use HasFactory;
    protected $fillable = [
        'registration_no',
        'deliveryman_name',
        'deliveryman_phone',
        'deliveryman_email',
        'deliveryman_address',
        'bike_number',
        'status',
    ];

    public function kyc()
    {
        return $this->hasOne(DeliverymanKyc::class, 'joinee_form_id');
    }

}
