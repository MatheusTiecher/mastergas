<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LancamentoRequest extends FormRequest
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
            'valor' => 'required|min:4|max:8',
            'descricao' => 'required|min:3|max:50',
        ];
    }

    public function messages()
    {
        return [
            'valor.required' => 'Este campo é de preenchimento obrigatório',
            'valor.min' => 'Valor mínimo 4 caracteres',
            'valor.max' => 'Valor máximo de caracteres atingido',   
            'descricao.required' => 'Este campo é de preenchimento obrigatório',
            'descricao.min' => 'Valor mínimo 3 caracteres',
            'descricao.max' => 'Valor máximo de caracteres atingido',
        ];
    }
}
