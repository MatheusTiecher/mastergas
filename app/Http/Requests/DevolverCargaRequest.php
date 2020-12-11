<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DevolverCargaRequest extends FormRequest
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
            'quantidade' => 'required|min:1|max:8',
            'descricao' => 'required|min:3|max:50',
            'desconto' => 'required|min:4|max:8',
        ];
    }

    public function messages()
    {
        return [
            'quantidade.required' => 'Este campo é de preenchimento obrigatório',
            'quantidade.min' => 'Valor mínimo 1 caracteres',
            'quantidade.max' => 'Valor máximo de caracteres atingido',   
            'descricao.required' => 'Este campo é de preenchimento obrigatório',
            'descricao.min' => 'Valor mínimo 3 caracteres',
            'descricao.max' => 'Valor máximo de caracteres atingido',
            'desconto.required' => 'Este campo é de preenchimento obrigatório',
            'desconto.min' => 'Valor mínimo 4 caracteres',
            'desconto.max' => 'Valor máximo de caracteres atingido',
        ];
    }
}
