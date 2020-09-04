<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use SoftDeletes;
    
    /**
     * The table name.
     * 
     * @var array
     */
    protected $table = 'order_product';

    /**
     * The attributes that are assignable.
     * 
     * @var array
     */
        protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_discount'
    ];
    
    /**
     * Get the related order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    /**
     * Get the related product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
