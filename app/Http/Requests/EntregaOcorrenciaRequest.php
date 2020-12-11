<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntregaOcorrenciaRequest extends FormRequest
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
            'endereco_id' => 'required',
            'user_id' => 'required',
            'anotacao' => 'max:99',
            'ocorrencia' => 'required|min:3|max:99',
            'dataagendada' => 'required|min:16|max:16',
        ];
    }

    public function messages()
    {
        return [
            'endereco_id.required' => 'Este campo é de preenchimento obrigatório',
            'user_id.required' => 'Este campo é de preenchimento obrigatório',
            'ocorrencia.required' => 'Este campo é de preenchimento obrigatório',
            'ocorrencia.min' => 'Valor mínimo 3 caracteres',
            'ocorrencia.max' => 'Valor máximo de caracteres atingido',
            'anotacao.max' => 'Valor máximo de caracteres atingido',
            'dataagendada.required' => 'Este campo é de preenchimento obrigatório',
            'dataagendada.min' => 'Data inválida',
            'dataagendada.max' => 'Data inválida',
        ];
    }
}