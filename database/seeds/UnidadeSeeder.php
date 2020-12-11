<?php

use Illuminate\Database\Seeder;
use App\Models\Unidade;

class UnidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$value = Unidade::firstOrCreate([
    		'descricao' => 'Unitário',
    		'sigla' => 'UN',
    		'inteiro' => '1',
    	]);

    	$value = Unidade::firstOrCreate([
    		'descricao' => 'Metro',
    		'sigla' => 'M',
    		'inteiro' => '0',
    	]);

    	$value = Unidade::firstOrCreate([
    		'descricao' => 'Litro',
    		'sigla' => 'L',
    		'inteiro' => '0',
    	]);

    	$value = Unidade::firstOrCreate([
    		'descricao' => 'Quilograma',
    		'sigla' => 'KG',
    		'inteiro' => '0',
    	]);

    	echo "Unidades adicionadas com sucesso \n";
    }
}
