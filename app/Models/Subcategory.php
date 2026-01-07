<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $table = "subcategories";
    protected $fillable = ['name','image', 'category_id','restaurant_id','status'];

    public function parentCategory()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class,'restaurant_id');
    }
    public function scopeIsActive($query, $active)
    {
        return $query->where('status',$active);
    }

}
