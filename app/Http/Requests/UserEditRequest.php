<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
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
            'email' => 'required|email|min:5|max:99|unique:users,email,'.$this->user->id.',id',
            'celular' => 'required|min:15|max:20',
            'telefone' => 'max:20',
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
        ];
    }
}
