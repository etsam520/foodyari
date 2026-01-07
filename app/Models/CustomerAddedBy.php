<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddedBy extends Model
{
    use HasFactory;
    protected $table = "customer_added_by";
    public $fillable = ['id', 'customer_id', 'restaurant_id', 'mess_id', 'admin_id', 'created_at', 'updated_at', 'added_by'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
