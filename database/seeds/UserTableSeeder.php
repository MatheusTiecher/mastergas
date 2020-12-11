<?php

use Illuminate\Database\Seeder;
use App\User;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $usr1 = User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@admin.admin',
            'password' => 'admin@admin.admin',
            'remember_token' => '8NBaLN4qImj9dXK6YPRE14TZkSizUphffnArAhcLMwoERZFQR9NsVsjZL4vg',
        ]);

        $usr1 = User::firstOrCreate([
            'name' => 'Matheus Tiecher',
            'email' => 'tiecheroficial@gmail.com',
            'password' => 'tiecheroficial@gmail.com',
            'remember_token' => '8NBaLN4qImj9dXK6YPRE14TZkSizUphffnArAhcLMwoERZFQR9NsVsjZL4vg',
        ]);

        $usr1 = User::firstOrCreate([
            'name' => 'Visitante',
            'email' => 'visitante@gmail.com',
            'password' => 'visitante123',
            // 'remember_token' => '',
        ]);

        $usr1 = User::firstOrCreate([
            'name' => 'Maicon',
            'email' => 'maicon@teste.com',
            'password' => 'maicon123',
            // 'remember_token' => '',
        ]);

        $usr1 = User::firstOrCreate([
            'name' => 'Ariel',
            'email' => 'ariel@teste.com',
            'password' => 'ariel123',
            // 'remember_token' => '',
        ]);

        echo "Usuario Criado com Sucesso \n";
    }
}
