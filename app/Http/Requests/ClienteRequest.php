<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class ClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // para deixar ativo o request
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        if (isset($this->cliente->id)) {
            if ($request->pf != 1) {
                return [
                    'nomerazao' => 'required|min:5|max:70',
                    'fantasia'  => 'required|min:5|max:70',
                    'cpfcnpj' => 'required|cnpj|min:18|max:18|unique:clientes,cpfcnpj,'.$this->cliente->id.',id',
                    'rgie'  => 'max:40',
                    'email' => 'required|email|min:5|max:99|unique:clientes,email,'.$this->cliente->id.',id',
                    'celular' => 'required|min:15|max:20',
                    'telefone' => 'max:20',
                ];
            } else{
                return [
                    'nomerazao' => 'required|min:5|max:70',
                    'cpfcnpj' => 'required|cpf|min:14|max:14|unique:clientes,cpfcnpj,'.$this->cliente->id.',id',
                    'rgie'  => 'max:30',
                    'email' => 'required|email|min:5|max:99|unique:clientes,email,'.$this->cliente->id.',id',
                    'celular' => 'required|min:15|max:20',
                    'telefone' => 'max:20',
                ];
            }
        } else{
            if ($request->pf != 1) {
                return [
                    'nomerazao' => 'required|min:5|max:70',
                    'fantasia'  => 'required|min:5|max:70',
                    'cpfcnpj' => 'required|cnpj|min:18|max:18|unique:clientes,cpfcnpj',
                    'rgie'  => 'max:30',
                    'email' => 'required|email|min:5|max:99|unique:clientes,email',
                    'celular' => 'required|min:15|max:20',
                ];
            } else{
                return [
                    'nomerazao' => 'required|min:5|max:70',
                    'cpfcnpj' => 'required|cpf|min:14|max:14|unique:clientes,cpfcnpj',
                    'rgie'  => 'max:30',
                    'email' => 'required|email|min:5|max:99|unique:clientes,email',
                    'celular' => 'required|min:15|max:20',
                    'telefone' => 'max:20',
                ];
            }
        }
    }

    public function messages()
    {
        return [
            'nomerazao.required' => 'Este campo é de preenchimento obrigatório',
            'nomerazao.min' => 'Valor mínimo 5 caracteres',
            'nomerazao.max' => 'Valor máximo de caracteres atingido',
            'fantasia.required' => 'Este campo é de preenchimento obrigatório',
            'fantasia.min' => 'Valor mínimo 5 caracteres',
            'fantasia.max' => 'Valor máximo de caracteres atingido',
            'cpfcnpj.required' => 'Este campo é de preenchimento obrigatório',
            'cpfcnpj.unique' => 'Outro cliente já possui esse CPF/CNPJ',
            'cpfcnpj.cnpj' => 'Esse campo não é um CNPJ válido',
            'cpfcnpj.cpf' => 'Esse campo não é um CPF válido',
            'rgie.max' => 'Valor máximo de caracteres atingido',
            'email.required' => 'Este campo é de preenchimento obrigatório',
            'email.unique' => 'Outro cliente já possui esse Email',
            'email.min' => 'Valor mínimo 5 caracteres',
            'email.max' => 'Valor máximo de caracteres atingido',
            'celular.required' => 'Este campo é de preenchimento obrigatório',
            'celular.min' => 'Valor mínimo 15 caracteres',
            'celular.max' => 'Valor máximo de caracteres atingido',
            'telefone.max' => 'Valor máximo de caracteres atingido',
        ];
    }

}
