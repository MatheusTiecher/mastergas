<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RotaRequest extends FormRequest
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
        if (isset($this->rota->id)) {
            return [
                'nome' => 'required|min:3|max:60|unique:rotas,nome,'.$this->rota->id.',id',
                'cidade_id' => 'required',
                'descricao'  => 'max:99',
            ];
        } else{
            return [
                'nome' => 'required|min:3|max:60|unique:rotas,nome',
                'cidade_id' => 'required',
                'descricao'  => 'max:99',
            ];
        }
    }

    public function messages()
    {
        return [
            'nome.required' => 'Este campo é de preenchimento obrigatório',
            'nome.min' => 'Valor mínimo 3 caracteres',
            'nome.max' => 'Valor máximo de caracteres atingido',
            'nome.unique' =>  'Outra rota já possui essa descrição',
            'cidade_id.required' => 'Este campo é de preenchimento obrigatório',
            'descricao.max' => 'Valor máximo de caracteres atingido',
        ];
    }
}
