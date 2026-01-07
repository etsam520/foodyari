<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AdminFund extends Model
{
    use HasFactory;
    protected $fillable = ['balance','fund_type'];

    public function scopeGetFund($query)
    {
        return $query->where('fund_type', 'admin')->get()->first();
    }

    public function scopeFundTxns($query)
    {
        return $query->with('txns')->getFund();
    }

    public function txns()
    {
        return $this->hasMany(AdminFundTxn::class , 'admin_fund_id');
    }

    public function cashTxns()
    {
        return $this->hasMany(CashTransaction::class, 'admin_fund_id');
    }

    public function bankTxns()
    {
        return $this->hasMany(BankTransaction::class, 'admin_fund_id');
    }
}
