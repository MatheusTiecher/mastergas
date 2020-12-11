<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // importação do softdelete

class Endereco extends Model
{
	use SoftDeletes; // trade do soft deletele

	protected $table = 'enderecos';
	protected $primaryKey = 'id';
	protected $fillable = ['rua', 'bairro', 'numero', 'cep', 'complemento', 'cliente_id', 'cidade_id'];


	public function cliente()
	{
		return $this->belongsTo('App\Models\Cliente');
	}

	public function cidade()
	{
		return $this->belongsTo('App\Models\Cidade');
	}

	public function ocorrenciaentrega()
	{
		return $this->hasMany('App\Models\OcorrenciaEntrega');
	}
}
