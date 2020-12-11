<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MensagemRequest extends FormRequest
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
        if (isset($this->mensagem->id)) {
            return [
                'nome' => 'required|min:3|max:50|unique:mensagems,nome,'.$this->mensagem->id.',id',  
                'msg'  => 'required|min:20|max:500',
                'hora'  => 'required|min:5|max:5',
                'rotina' => 'required',
                'produto_id' => 'required',
            ];
        } else{
            return [
                'nome' => 'required|min:3|max:50|unique:mensagems,nome',
                'msg'  => 'required|min:20|max:500',
                'hora'  => 'required|min:5|max:5',
                'rotina' => 'required',
                'produto_id' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'nome.required' => 'Este campo é de preenchimento obrigatório',
            'nome.min' => 'Valor mínimo 3 caracteres',
            'nome.max' => 'Valor máximo de caracteres atingido',
            'nome.unique' =>  'Outra mensagem já possui esse nome',
            'msg.required' => 'Este campo é de preenchimento obrigatório',
            'msg.min' => 'Valor mínimo 20 caracteres',
            'msg.max' => 'Valor máximo de caracteres atingido',
            'hora.required' => 'Este campo é de preenchimento obrigatório',
            'hora.min' => 'Formato inválido',
            'hora.max' => 'Valor máximo de caracteres atingido',
            'rotina.required' => 'Este campo é de preenchimento obrigatório',
            'produto_id.required' => 'Este campo é de preenchimento obrigatório',
        ];
    }
}
