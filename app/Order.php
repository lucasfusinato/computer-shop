<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'client_id'
    ];
    
    /**
     * Get the related client.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    /**
     * Get the related items.
     */
    public function items()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
