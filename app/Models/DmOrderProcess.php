<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DmOrderProcess extends Model
{
    use HasFactory;
    protected $table = 'dm_order_processes';

    protected $fillable = [
        'dm_id',
        'order_id',
        'start_time',
        'picked_up_time',
        'end_time',
        'start_langitude',
        'start_longitude',
        'picked_up_langitude',
        'picked_up_longitude',
        'end_langitude',
        'end_longitude',
        'start_address',
        'end_address',
        'avg_distance',
        'actual_distance',
        'deliver_by'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'deliver_by' => 'datetime',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function deliveryMan()
    {
        return $this->belongsTo(DeliveryMan::class, 'dm_id');
    }
}
