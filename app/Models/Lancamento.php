<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lancamento extends Model
{
	protected $table = 'lancamentos';
	protected $primaryKey = 'id';
	protected $fillable = ['tipo_lancamento', 'descricao', 'valor', 'caixa_id', 'venda_id', 'user_id'];

	public function venda()
	{
		return $this->belongsTo('App\Models\Venda');
	}

	public function caixa()
	{
		return $this->belongsTo('App\Models\Caixa');
	}

	public function user()
	{
		return $this->belongsTo('App\User')->withTrashed();
	}

 	// MUTATOR
	public function setValorAttribute($value)
	{	
		$value = str_replace (',', '.', str_replace ('.', '', $value));

	 		// dd($value);

		$this->attributes['valor'] = $value;
	}
}
