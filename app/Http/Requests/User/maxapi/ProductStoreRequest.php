<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'product.name' => 'required|string|max:40',
            'product.description' => 'required|string|max:5000',
            'product.weight' => 'numeric',
            'product.image_1' => 'required',
            'product.image_2' => 'required',
            'product.barcode' => 'required|numeric',
            'product.category_id' => 'required|numeric',
            'comment.price' => 'required|numeric',
            'comment.comment' => 'string',
            'comment.rating' => 'required|numeric',
        ];
    }
}
