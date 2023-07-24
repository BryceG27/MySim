<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'Name' => 'required|min:3|max:50',
            'Surname' => 'required|min:3|max:50',
            'email' => 'required|email:rfc,dns',
            'Phone' => 'max:30',
            'Address' => 'max:200',
            'City' => 'max:20',
            'County' => 'max:10',
            'Country' => 'max:50',
        ];
    }
}
