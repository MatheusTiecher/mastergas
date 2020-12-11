<?php

use Illuminate\Database\Seeder;
use App\Models\Cargo;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$value = Cargo::firstOrCreate([
    		'nome' => 'Admin',
    		'descricao' => 'Acesso total ao sistema'
    	]);

    	$value = Cargo::firstOrCreate([
    		'nome' => 'Gerente',
    		'descricao' => 'Gerenciamento do sistema'
    	]);

    	$value = Cargo::firstOrCreate([
    		'nome' => 'Usuário',
    		'descricao' => 'Acesso ao site como usuário'
    	]);

    	echo "Cargos Criados com Sucesso \n";
    }
}
