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
        'client_id',
        'discount'
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

    /**
     * Calcula o valor total do pedido.
     * 
     * @return int
     */
    public function totalPrice()
    {
        $totalPrice = 0;
        foreach($this->items as $item) {
            $totalPrice += $item->totalPrice();
        }
        return $totalPrice - ($totalPrice * $this->discount / 100);
    }
}
