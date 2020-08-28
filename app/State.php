<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    /**
     * The attributes that are assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'uf',
        'name'
    ];
}
