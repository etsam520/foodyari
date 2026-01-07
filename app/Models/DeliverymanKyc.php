<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliverymanKyc extends Model
{
    use HasFactory;
    protected $fillable = [
        'deliveryman_id',
        'joinee_form_id',
        'status',
    ];

    public function deliveryman()
    {
        return $this->belongsTo(DeliveryMan::class,'deliveryman_id');
    }

    public function joineeForm()
    {
        return $this->belongsTo(DeliveryManJoineeForm::class);
    }
    public function documentDetails()
    {
        return $this->hasMany(DocumentDetails::class,'kyc_key' );
    }
}
