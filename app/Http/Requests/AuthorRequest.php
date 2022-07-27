<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorRequest extends FormRequest
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
            'firstname' => 'required',
            'lastname' => 'required'
        ];
    }

    public function messages(){
        return [
            'firstname:required' => 'O campo de nome é obrigatório',
            'lastname:required' => 'O campo de sobrenome é obrigatório',
        ];
    }
}
