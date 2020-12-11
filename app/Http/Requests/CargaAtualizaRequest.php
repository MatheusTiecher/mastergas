<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CargaAtualizaRequest extends FormRequest
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
            'observacao' => 'max:99',
        ];
    }

    public function messages()
    {
        return [
            'observacao.max' => 'Valor máximo de caracteres atingido',
        ];
    }
}
