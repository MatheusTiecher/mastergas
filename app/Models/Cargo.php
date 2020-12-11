<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // importação do softdelete


class Cargo extends Model
{
 	use SoftDeletes; // trade do soft deletele 

 	protected $table = 'cargos';
 	protected $primaryKey = 'id';
 	protected $fillable = ['nome','descricao'];

 	function cargoPermissoes() {
 		return $this->belongsToMany('App\Models\Permissao');
 	}

	function users()
	{
		return $this->belongsToMany('App\User')->withTrashed();
	}
 }
