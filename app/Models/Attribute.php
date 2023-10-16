<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
        'attribute_name',
        'selection_type',
        'minimum_options',
        'maximum_options',
    ];

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
