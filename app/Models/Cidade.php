<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
	protected $table = 'cidades';
	protected $primaryKey = 'id';
	protected $fillable = ['ibge_id', 'nome', 'estado_id'];


	public function estado()
	{
		return $this->belongsTo('App\Models\Estado');
	}
}
