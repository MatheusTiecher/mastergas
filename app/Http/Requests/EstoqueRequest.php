<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstoqueRequest extends FormRequest
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
            'total' => 'required|min:1|max:8',
            'valorcusto' => 'required|min:4|max:8',
            'fornecedor_id' => 'required',
            'produto_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'total.required' => 'Este campo é de preenchimento obrigatório',
            'total.min' => 'Valor mínimo 1 caracteres',
            'total.max' => 'Valor máximo de caracteres atingido',
            'valorcusto.required' => 'Este campo é de preenchimento obrigatório',
            'valorcusto.min' => 'Valor mínimo 4 caracteres',
            'valorcusto.max' => 'Valor máximo de caracteres atingido',
            'fornecedor_id.required' => 'Este campo é de preenchimento obrigatório',
            'produto_id.required' => 'Este campo é de preenchimento obrigatório',
        ];
    }

}
