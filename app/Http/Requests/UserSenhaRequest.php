<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSenhaRequest extends FormRequest
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
            'password' => 'required|min:8|max:50',
            'password_confirmation' => 'required_with:password|same:password|min:8|max:50'
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Este campo é de preenchimento obrigatório',
            'password.min' => 'Valor mínimo 8 caracteres',
            'password.max' => 'Valor máximo de caracteres atingido',
            'password_confirmation.required' => 'Este campo é de preenchimento obrigatório',
            'password_confirmation.min' => 'Valor mínimo 8 caracteres',
            'password_confirmation.max' => 'Valor máximo de caracteres atingido',
            'password_confirmation.same' => 'Senha diferente, favor informar a mesma senha!',
        ];
    }
}
