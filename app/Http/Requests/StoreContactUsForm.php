<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactUsForm extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required:max:100', 
            'email' => 'required:max:100', 
            'subject'=> 'required:max:200', 
            'message' => 'required:max:1000', 
        ];
    }
}
