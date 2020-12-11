<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
	protected $table = 'estoques';
	protected $primaryKey = 'id';
	protected $fillable = ['total', 'restante', 'valorcusto', 'fornecedor_id', 'produto_id'];


	public function fornecedor()
	{
		return $this->belongsTo('App\Models\Fornecedor')->withTrashed();
	}	

	public function produto()
	{
		return $this->belongsTo('App\Models\Produto')->withTrashed();
	}

	public function vendaitem()
	{
		return $this->hasMany('App\Models\VendaItem');
	}

	public function cargaitem()
	{
		return $this->hasMany('App\Models\CargaItem');
	}

	// MUTATOR
	public function setValorcustoAttribute($value)
	{	
		$value = str_replace (',', '.', str_replace ('.', '', $value));

 		// dd($value);

		$this->attributes['valorcusto'] = $value;
	}

	public function setTotalAttribute($value)
	{	
		$value = str_replace (',', '.', str_replace ('.', '', $value));

 		// dd($value);

		$this->attributes['total'] = $value;
	}

	public function setRestanteAttribute($value)
	{	
		$value = str_replace (',', '.', str_replace ('.', '', $value));

 		// dd($value);

		$this->attributes['restante'] = $value;
	}
}
