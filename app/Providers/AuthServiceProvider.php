<?php

namespace App\Providers;

use Auth;
use App\Models\Permissao;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (Schema::hasTable('permissoes')) // esse if fara a verificação se a tabela existe para poder fazer o foreach
        {
            $permissoes = Permissao::all();
            foreach ($permissoes as $key => $value) {
                Gate::define($value->nome, function($user) use ($value){
                    return in_array($value->nome, $user->userPermissoes()) || $user->isAdmin();
                    // return in_array($value, $user->userPermissoes()) || $user->isAdmin();
                });
            }
        }
    }
}
