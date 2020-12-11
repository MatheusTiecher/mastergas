<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnidadeRequest extends FormRequest
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
        if (isset($this->unidade->id)) {
            return [
                'descricao' => 'required|min:3|max:40|unique:unidades,descricao,'.$this->unidade->id.',id',
                'sigla'  => 'required|min:1|max:5',
                // 'inteiro' => 'required',
            ];
        } else{
            return [
                'descricao' => 'required|min:3|max:40|unique:unidades,descricao',
                'sigla'  => 'required|min:1|max:5',
                // 'inteiro' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'descricao.required' => 'Este campo é de preenchimento obrigatório',
            'descricao.min' => 'Valor mínimo 3 caracteres',
            'descricao.max' => 'Valor máximo de caracteres atingido',
            'descricao.unique' =>  'Outra unidade já possui essa descrição',
            'sigla.required' => 'Este campo é de preenchimento obrigatório',
            'sigla.min' => 'Valor mínimo 1 caracteres',
            'sigla.max' => 'Valor máximo de caracteres atingido',
            // 'inteiro.required' => 'Este campo é de preenchimento obrigatório',
        ];
    }
}
