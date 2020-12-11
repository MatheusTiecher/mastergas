<?php

use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$value = Produto::firstOrCreate([
    		'descricao' => 'Mangueira de Gás',
    		'valorvenda' => '15,00',
    		'minimo' => '5',
    		'unidade_id' => '2',
    	]);

    	$value = Produto::firstOrCreate([
    		'descricao' => 'Botijão Gás 13/L',
    		'valorvenda' => '120,00',
    		'minimo' => '50',
    		'unidade_id' => '1',
    	]); 

        $value = Produto::firstOrCreate([
            'descricao' => 'Recarga Botijão Gás 13/L',
            'valorvenda' => '80,00',
            'minimo' => '60',
            'unidade_id' => '1',
        ]);

        $value = Produto::firstOrCreate([
            'descricao' => 'Botijão Gás 20/L',
            'valorvenda' => '150,00',
            'minimo' => '20',
            'unidade_id' => '1',
        ]); 

        $value = Produto::firstOrCreate([
            'descricao' => 'Recarga Botijão Gás 20/L',
            'valorvenda' => '110,00',
            'minimo' => '25',
            'unidade_id' => '1',
        ]);

        $value = Produto::firstOrCreate([
            'descricao' => 'Galão de Água 20L',
            'valorvenda' => '12,00',
            'minimo' => '10',
            'unidade_id' => '1',
        ]);

        // factory(Produto::class, 100)->create();

        echo "Produtos adicionados com sucesso \n";
    }
}
