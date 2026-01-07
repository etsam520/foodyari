<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessFoodProcessing extends Model
{
    use HasFactory;
    protected $fillable = ["id","mess_id","service","speciality","steps","data","dine_in","delivery","created_at","updated_at"];
     

    public function scopeGetProcess($query, $messId, $service, $step = null)
    {
        $today = Carbon::now()->toDateString();


        return $query->whereDate('created_at', $today)
            ->where('mess_id', $messId)
            ->where('service', $service)
            ->when($step !== null, function ($q) use ($step) {
                return $q->where('steps', $step);
            })
            ->latest();
    }

}
