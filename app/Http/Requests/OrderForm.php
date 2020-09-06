<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use App\Order;
use App\OrderProduct;
use \Exception;

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
        return [
            'client_id' => 'required|numeric',
            'discount' => 'nullable|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|numeric',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_discount' => 'nullable|numeric|min:0'
        ];
    }
    
    /**
     * Persist order data in database.
     *
     * @param \App\Order $order
     */
    public function persist(Order $order)
    {
        try {
            DB::beginTransaction();

            $order->client_id = $this->client_id;
            $order->discount = $this->discount;
            $order->save();
            
            $order->items()->delete();
            foreach($this->items as $item) {
                $orderProduct = (isset($item['id']) && !empty($item['id'])) ? OrderProduct::withTrashed()->find($item['id']) : new OrderProduct();
                $orderProduct->product_id = $item['product_id'];
                $orderProduct->quantity = $item['quantity'];
                $orderProduct->unit_price = $item['unit_price'];
                $orderProduct->total_discount = $item['total_discount'] ?: 0;
                if($order->items()->where('product_id', $orderProduct->product_id)->count() > 0) {
                    throw new Exception('O pedido não pode ter produtos duplicados.');
                }
                if($orderProduct->totalPrice() < 0) {
                    throw new Exception('O desconto não pode gerar preço negativo.');
                }
                if($orderProduct->trashed()) {
                    $orderProduct->restore();
                }
                $order->items()->save($orderProduct);
            }
    
            if($order->items()->count() == 0) {
                throw new Exception('O pedido deve ter pelo menos um item.');
            }
    
            DB::commit();
        } catch(Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }
}
