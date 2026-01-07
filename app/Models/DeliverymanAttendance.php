<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

    class DeliverymanAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'deliveryman_id',
        'is_online',
        'last_checked',
        'check_in', 'check_out',
        'check_in_location', 'check_out_location',
        'check_in_image', 'check_out_image',
        'check_in_meter', 'check_out_meter',
        'check_in_address', 'check_out_address',
        'check_in_note', 'check_out_note',

    ];

    public function deliveryman()
    {
        return $this->belongsTo(Deliveryman::class, 'deliveryman_id');
    }

}
