<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessDeliverymanOrderAccept extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id','checkList_id','dm_id','mess_qrId','status','delivery_address','coordinates','accepted_at'];

    public function deliveryman()
    {
      return  $this->belongsTo(DeliveryMan::class, 'dm_id');
    }

    public function checklist()
    {
      return  $this->belongsTo(AttendaceCheckList::class, 'checkList_id');
    }

    public function messdietQr()
    {
      return  $this->belongsTo(MessQR::class, 'mess_qrId');
    }

    public function customer()
    {
      return  $this->belongsTo(Customer::class, 'customer_id');
    }

}
