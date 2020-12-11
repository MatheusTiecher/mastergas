<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendaItemRequest extends FormRequest
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
            'valorvenda' => 'required|min:4|max:8',
            'quantidade' => 'required|min:1|max:8',
            'produto_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'valorvenda.required' => 'Este campo é de preenchimento obrigatório',
            'valorvenda.min' => 'Valor mínimo 4 caracteres',
            'valorvenda.max' => 'Valor máximo de caracteres atingido',            
            'quantidade.required' => 'Este campo é de preenchimento obrigatório',
            'quantidade.min' => 'Valor mínimo 1 caracteres',
            'quantidade.max' => 'Valor máximo de caracteres atingido',
            'produto_id.required' => 'Este campo é de preenchimento obrigatório',
        ];
    }
}
