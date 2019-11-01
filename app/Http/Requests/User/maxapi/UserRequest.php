<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            //like UpdateRequest, but store in controller
            // 'email' => 'required|string|email|max:255|unique:users',
            // 'password' => 'required|string|min:6|confirmed',
            // 'image' => 'string',
        ];
    }
}
