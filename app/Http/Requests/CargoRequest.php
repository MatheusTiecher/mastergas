<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CargoRequest extends FormRequest
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
        if (isset($this->cargo->id)) {
            return [
                'nome' => 'required|min:3|max:60|unique:cargos,nome,'.$this->cargo->id.',id',
                'descricao'  => 'required|min:5|max:99',
            ];
        } else{
            return [
                'nome' => 'required|min:3|max:60|unique:cargos,nome',
                'descricao'  => 'required|min:5|max:99',
            ];
        }
    }

    public function messages()
    {
        return [
            'nome.required' => 'Este campo é de preenchimento obrigatório',
            'nome.min' => 'Valor mínimo 3 caracteres',
            'nome.max' => 'Valor máximo de caracteres atingido',
            'nome.unique' =>  'Outro cargo já possui esse nome',
            'descricao.required' => 'Este campo é de preenchimento obrigatório',
            'descricao.min' => 'Valor mínimo 5 caracteres',
            'descricao.max' => 'Valor máximo de caracteres atingido',
        ];
    }
}
