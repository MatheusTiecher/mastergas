<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnderecoRequest extends FormRequest
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
            'rua' => 'required|min:3|max:50',
            'bairro' => 'required|min:3|max:50',
            'numero'  => 'required|min:1|max:5',
            'cep' => 'required|min:9|max:9',
            'complemento' => 'max:99',
            'cidade_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'rua.required' => 'Este campo é de preenchimento obrigatório',
            'rua.min' => 'Valor mínimo 3 caracteres',
            'rua.max' => 'Valor máximo de caracteres atingido',
            'bairro.required' => 'Este campo é de preenchimento obrigatório',
            'bairro.min' => 'Valor mínimo 3 caracteres',
            'bairro.max' => 'Valor máximo de caracteres atingido',
            'numero.required' => 'Este campo é de preenchimento obrigatório',
            'numero.min' => 'Valor mínimo 1 caracteres',
            'numero.max' => 'Valor máximo de caracteres atingido',
            'cep.required' => 'Este campo é de preenchimento obrigatório',
            'cep.min' => 'CEP inválido',
            'cep.max' => 'CEP inválido',
            'complemento.max' => 'Valor máximo de caracteres atingido',
            'cidade_id.required' => 'Este campo é de preenchimento obrigatório',
        ];
    }
    
}
