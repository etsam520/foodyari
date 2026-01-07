<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'name',
        'is_required',
        'is_text',
        'is_text_required',
        'is_media',
        'is_media_required',
        'has_expiry_date',
        'status',
    ];

    protected $appends = [
        'text_input_name',
        'media_input_name',
        'expire_date_input_name'
    ];

    public function getTextInputNameAttribute()
    {
        return self::textToInputName($this->name . '_text');
    }
    public function getMediaInputNameAttribute()
    {
        return self::textToInputName($this->name . '_media');
    }
    public function getExpireDateInputNameAttribute()
    {
        return self::textToInputName($this->name . '_expire_date');
    }

    private static function textToInputName($text)
    {
        // Replace spaces with underscores and make it lowercase
        $name = strtolower(str_replace(' ', '_', $text));

        // Remove any non-alphanumeric characters except underscores
        $name = preg_replace('/[^\w_]/', '', $name);

        return $name;
    }

    public function documentDetails()
    {
        return $this->hasMany(DocumentDetails::class);
    }

}
