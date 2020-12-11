<?php

use Illuminate\Database\Seeder;
use App\Models\Fornecedor;

class FornecedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $value = Fornecedor::firstOrCreate([
            'nomerazao' => 'COPAGAZ DISTRIBUIDORA DE GAS S.A',
            'fantasia' => 'COPAGAZ',
            'cpfcnpj' => '03.237.583/0001-67',
            'rgie' => '123456QWE',
            'email' => 'controladoria@copagaz.com.br',
            'telefone' => '(11) 5505-4077',
            'celular' => '(00) 00000-0000',
            'tipo' => 'Jurídica',
        ]);

        // factory(Fornecedor::class, 1000)->create();

        echo "Fornecedor adicionados com sucesso \n";
    }
}
