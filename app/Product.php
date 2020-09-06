<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'manufacturer',
        'sale_price'
    ];
}
