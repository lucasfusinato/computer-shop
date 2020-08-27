<?php

namespace App\Http\Requests;

use App\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProductForm extends FormRequest
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
            'name' => 'required|max:200',
            'manufacturer' => 'required|max:200'
        ];
    }

    /**
     * Persist product data in database.
     *
     * @return array
     */
    public function persist(Product $product)
    {
        $product->name = $this->name;
        $product->manufacturer = $this->manufacturer;

        $product->save();
    }
}
