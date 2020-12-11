<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
	protected $table = 'estados';
	protected $primaryKey = 'id';
	protected $fillable = ['ibge_id', 'sigla', 'nome'];


	public function cidade(){
		return $this->hasMany('App\Models\Cidade');
	}
}
