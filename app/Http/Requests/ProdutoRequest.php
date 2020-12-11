<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
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
        if (isset($this->produto->id)) {
            return [
                'descricao' => 'required|min:3|max:60|unique:produtos,descricao,'.$this->produto->id.',id',
                'valorvenda'  => 'required|min:4|max:8',
                'minimo' => 'required|min:1|max:8',
                'unidade_id' => 'required',
            ];
        } else{
            return [
                'descricao' => 'required|min:3|max:60|unique:produtos,descricao',
                'valorvenda'  => 'required|min:4|max:8',
                'minimo' => 'required|min:1|max:8',
                'unidade_id' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'descricao.required' => 'Este campo é de preenchimento obrigatório',
            'descricao.min' => 'Valor mínimo 3 caracteres',
            'descricao.max' => 'Valor máximo de caracteres atingido',
            'descricao.unique' =>  'Outro produto já possui essa descrição',
            'valorvenda.required' => 'Este campo é de preenchimento obrigatório',
            'valorvenda.min' => 'Valor mínimo 4 caracteres',
            'valorvenda.max' => 'Valor máximo de caracteres atingido',
            'minimo.required' => 'Este campo é de preenchimento obrigatório',
            'minimo.min' => 'Valor mínimo 1 caracteres',
            'minimo.max' => 'Valor máximo de caracteres atingido',
            'unidade_id.required' => 'Este campo é de preenchimento obrigatório',
        ];
    }
}
