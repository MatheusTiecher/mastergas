<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(ClienteSeeder::class);
        $this->call(FornecedorSeeder::class);
        $this->call(UnidadeSeeder::class);
        $this->call(ProdutoSeeder::class);
        $this->call(FormaPagamentoTableSeeder::class);
        $this->call(CargoSeeder::class);
        $this->call(PermissaoSeeder::class);
    }
}
