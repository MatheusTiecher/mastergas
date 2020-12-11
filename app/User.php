<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // importação do softdelete

class User extends Authenticatable
{
    use Notifiable;

    use SoftDeletes; // trade do soft deletele 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'telefone', 'celular', 'entregador',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function venda(){
        return $this->hasMany('App\Models\Venda');
    }

    public function caixa(){
        return $this->hasMany('App\Models\Caixa');
    }

    public function carga(){
        return $this->hasMany('App\Models\Carga');
    }

    public function ocorrenciaentrega(){
        return $this->hasMany('App\Models\OcorrenciaEntrega');
    }

    public function lancamento(){
        return $this->hasMany('App\Models\Lancamento');
    }

    function userCargos() {
        return $this->belongsToMany('App\Models\Cargo');
    }

    // VERIFICA SE E ADMIN
    function isAdmin() {
        return $this->id == 1; 
    }

    // VERIFICA SE TEM PERMISSAO
    public function userPermissoes()
    {
        $permissoes = [];

        foreach ($this->userCargos as $key => $value) {
            foreach ($value->cargoPermissoes as $key => $val) {
                if (!in_array($val->nome, $permissoes)) {
                    $permissoes[] = $val->nome;
                }
            }
        }

        return $permissoes;
    }

    // MUTATOR
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }

}
