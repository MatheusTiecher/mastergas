<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CargaVendaRequest extends FormRequest
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
            'desconto' => 'required|min:4|max:8',
            'rota_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'desconto.required' => 'Este campo é de preenchimento obrigatório',
            'desconto.min' => 'Valor mínimo 4 caracteres',
            'desconto.max' => 'Valor máximo de caracteres atingido',
            'rota_id.required' => 'Este campo é de preenchimento obrigatório',
        ];
    }
}