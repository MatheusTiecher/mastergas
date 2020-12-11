<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
	protected $table = 'permissoes';
	protected $primaryKey = 'id';
	protected $fillable = ['nome','descricao', 'modulo'];

	public function cargos()
	{
		return $this->belongsToMany(Cargo::class);
	}
}
