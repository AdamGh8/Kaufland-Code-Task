<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'entity_id',
        'category_name',
        'sku',
        'name',
        'description',
        'shortdesc',
        'price',
        'link',
        'image',
        'brand',
        'rating',
        'caffeine_type',
        'count',
        'flavored',
        'seasonal',
        'instock',
        'facebook',
        'is_kcup',
    ];
}

