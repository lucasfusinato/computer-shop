<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use App\Order;
use App\OrderProduct;

class OrderForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
        return [
            'client_id' => 'required',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required',
            'items.*.quantity' => 'required|min:1',
            'items.*.unit_price' => 'required',
            'items.*.total_discount' => ''
        ];
    }
    
    /**
     * Persist order data in database.
     *
     * @param \App\Order $order
     */
    public function persist(Order $order)
    {
        DB::beginTransaction();

        $order->client_id = $this->client_id;
        $order->save();
        
        foreach($this->items as $item) {
            if(isset($item->product_id) && !empty($item->product_id)) {
                $orderProduct = new OrderProduct();
                $orderProduct->product_id = $item->product_id;
                $orderProduct->quantity = $item->quantity;
                $orderProduct->unit_price = $item->unit_price;
                $orderProduct->total_discount = $item->total_discount ?: 0;
                $order->items()->save($orderProduct);
            }
        }

        DB::commit();
    }
}
