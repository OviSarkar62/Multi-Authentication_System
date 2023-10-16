<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['product_name', 'product_description', 'product_price', 'product_category'];

    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }
}


