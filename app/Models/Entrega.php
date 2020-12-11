<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
	protected $table = 'entregas';
	protected $primaryKey = 'id';
	protected $fillable = ['status', 'dataentrega', 'forma_entrega', 'venda_id']; 


	public function venda(){
		return $this->belongsTo('App\Models\Venda');
	}
	
	public function ocorrenciaentrega()
	{
		return $this->hasMany('App\Models\OcorrenciaEntrega');
	}

	// Corrigindo formato de data hora
	public function getDataentregaAttribute($value)
	{
		// dd($value);
		if($value == null){
			return null;
		}
		return date('d/m/Y H:i', strtotime($value));
	}
}
