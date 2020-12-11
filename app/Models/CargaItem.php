<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargaItem extends Model
{
	protected $table = 'carga_items';
	protected $primaryKey = 'id';
	protected $fillable = ['sequencia', 'quantidade', 'status', 'descricao', 'carga_id', 'produto_id', 'estoque_id'];


	public function carga()
	{
		return $this->belongsTo('App\Models\Carga');
	}

	public function estoque()
	{
		return $this->belongsTo('App\Models\Estoque');
	}

	public function produto()
	{
		return $this->belongsTo('App\Models\Produto')->withTrashed();
	}

	public function setQuantidadeAttribute($value)
	{	
		$value = str_replace (',', '.', str_replace ('.', '', $value));

 		// dd($value);

		$this->attributes['quantidade'] = $value;
	}
}
