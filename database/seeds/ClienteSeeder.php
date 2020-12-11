<?php

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$value = Cliente::firstOrCreate([
    		'nomerazao' => 'J B DISTRIBUIDORA DE GAS LTDA',
    		'fantasia' => 'JOAO DO GAS',
    		'cpfcnpj' => '30.820.309/0001-99',
    		'rgie' => '',
    		'email' => 'jbgas@gmail.com',
    		'telefone' => '(46) 3556-1434',
    		'celular' => '(46) 99973-0562',
            'tipo' => 'Jurídica',
        ]);

    	$value = Cliente::firstOrCreate([
    		'nomerazao' => 'MATHEUS TIECHER',
    		'cpfcnpj' => '113.452.729-28',
    		'rgie' => '123456QWE',
    		'email' => 'matheus@gmail.com',
    		'telefone' => '(46) 2555-9007',
    		'celular' => '(46) 99933-8729',
    		'tipo' => 'Física',
        ]);

    	$value = Cliente::firstOrCreate([
    		'nomerazao' => 'LETICIA DESSANTI TIECHER',
    		'cpfcnpj' => '113.452.809-47',
    		'rgie' => '123456QWE',
    		'email' => 'leticia@gmail.com',
    		'telefone' => '(00) 0000-0000',
    		'celular' => '(00) 00000-0000',
    		'tipo' => 'Física',
        ]);

        // factory(Cliente::class, 1)->create();

        echo "Clientes adicionados com sucesso \n";
    }
}
