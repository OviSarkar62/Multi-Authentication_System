<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        'option_name',
        'additional_price',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}

