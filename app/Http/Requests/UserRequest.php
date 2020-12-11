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
            'name' => 'required|min:5|max:70',
            'email' => 'required|email|min:5|max:99|unique:users,email',
            'celular' => 'required|min:15|max:20',
            'telefone' => 'max:20',
            'password' => 'required|min:8|max:50',
            'password_confirmation' => 'required_with:password|same:password|min:8|max:50'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Este campo é de preenchimento obrigatório',
            'name.min' => 'Valor mínimo 5 caracteres',
            'name.max' => 'Valor máximo de caracteres atingido',
            'email.required' => 'Este campo é de preenchimento obrigatório',
            'email.unique' => 'Outro usuário já possui esse Email',
            'email.min' => 'Valor mínimo 5 caracteres',
            'email.max' => 'Valor máximo de caracteres atingido',
            'celular.required' => 'Este campo é de preenchimento obrigatório',
            'celular.min' => 'Valor mínimo 15 caracteres',
            'celular.max' => 'Valor máximo de caracteres atingido',
            'telefone.max' => 'Valor máximo de caracteres atingido',
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
