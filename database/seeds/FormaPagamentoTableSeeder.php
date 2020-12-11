<?php

use Illuminate\Database\Seeder;
use App\Models\FormaPagamento;


class FormaPagamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $value = FormaPagamento::firstOrCreate([
            'descricao' => 'À Vista',
        ]);        

        $value = FormaPagamento::firstOrCreate([
            'descricao' => 'Cheque',
        ]);

        $value = FormaPagamento::firstOrCreate([
            'descricao' => 'Cartão de Crédito',
        ]);

        $value = FormaPagamento::firstOrCreate([
        	'descricao' => 'Cartão de Débito',
        ]);

        $value = FormaPagamento::firstOrCreate([
            'descricao' => 'Avaria',
        ]);

        echo "Formas de pagamento adicionado com sucesso \n";
    }
}
