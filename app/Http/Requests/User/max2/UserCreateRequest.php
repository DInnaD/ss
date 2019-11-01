<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'email' =>  'required|email|unique:users|string',
            'password'  =>  'required|string|min:6|max:20',
            'first_name'  =>  'min:1|max:20|string',
            'last_name' => 'min:1|max:40|string',
            'country' => 'min:1|max:100|string',
            'city' => 'min:1|max:100|string',
            'phone' => 'min:1|max:30|string',
            'role' => 'min:1|max:20|string',

        ];
    }
}
