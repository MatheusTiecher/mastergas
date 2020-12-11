<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
	protected $table = 'vendas';
	protected $primaryKey = 'id';
	protected $fillable = ['observacao', 'desconto', 'frete', 'status', 'finalizavenda', 'cliente_id', 'user_id', 'forma_pagamento_id', 'rota_id', 'carga_id'];


	public function cliente()
	{
		return $this->belongsTo('App\Models\Cliente')->withTrashed();
	}

	public function user()
	{
		return $this->belongsTo('App\User')->withTrashed();
	}

	public function vendaitem()
	{
		return $this->hasMany('App\Models\VendaItem');
	}
	
	public function entrega()
	{
		return $this->hasOne('App\Models\Entrega');
	}

	public function formapagamento()
	{
		return $this->belongsTo('App\Models\FormaPagamento', 'forma_pagamento_id', 'id');
	}

	public function lancamento()
	{
		return $this->hasMany('App\Models\Lancamento');
	}

	public function carga()
	{
		return $this->belongsTo('App\Models\Carga');
	}

	public function rota()
	{
		return $this->belongsTo('App\Models\Rota')->withTrashed();
	}

 	// MUTATOR
	public function setFreteAttribute($value)
	{	
		$value = str_replace (',', '.', str_replace ('.', '', $value));

 		// dd($value);

		$this->attributes['frete'] = $value;
	}

	public function setDescontoAttribute($value)
	{	
		$value = str_replace (',', '.', str_replace ('.', '', $value));

 		// dd($value);

		$this->attributes['desconto'] = $value;
	}

	// Corrigindo formato de data hora
	public function getFinalizavendaAttribute($value)
	{
		// dd($value);
		if($value == null){
			return null;
		}
		return date('d/m/Y H:i', strtotime($value));
	}
}
