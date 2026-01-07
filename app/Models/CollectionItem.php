<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'collection_id', 'item_id', 'type',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function foods()
    {
        return $this->morphedByMany(Food::class, 'item_id');
    }

    public function restaurants()
    {
        return $this->morphedByMany(Restaurant::class, 'item_id');
    }

}
