<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendaItem extends Model
{
	protected $table = 'venda_items';
	protected $primaryKey = 'id';
	protected $fillable = ['sequencia', 'descricao', 'quantidade', 'status', 'valorvenda', 'venda_id', 'produto_id', 'estoque_id'];


	public function produto()
	{
		return $this->belongsTo('App\Models\Produto')->withTrashed();
	}

	public function venda()
	{
		return $this->belongsTo('App\Models\Venda');
	}	

	public function estoque()
	{
		return $this->belongsTo('App\Models\Estoque');
	}

 	// MUTATOR
	public function setValorvendaAttribute($value)
	{	
		$value = str_replace (',', '.', str_replace ('.', '', $value));

 		// dd($value);

		$this->attributes['valorvenda'] = $value;
	}

	public function setQuantidadeAttribute($value)
	{	
		$value = str_replace (',', '.', str_replace ('.', '', $value));

 		// dd($value);

		$this->attributes['quantidade'] = $value;
	}
}
