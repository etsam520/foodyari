<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class Attendance extends Model
{
    use HasFactory;
    protected $table = "attendances";
    protected $fallable = ['id','customer_id','mess_id','status','state','created_at','attendace_check_lists'];

    public function checklist(){
        return $this->hasMany(AttendaceCheckList::class,'attendance_id');
    }

    public function customers()
{
    return $this->belongsTo(Customer::class, 'customer_id');
}
}