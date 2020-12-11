<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OcorrenciaEntrega extends Model
{
	protected $table = 'ocorrencia_entregas';
	protected $primaryKey = 'id';
	protected $fillable = ['status', 'anotacao', 'ocorrencia', 'dataagendada', 'endereco_id', 'entrega_id', 'user_id']; 


	public function entrega()
	{
		return $this->belongsTo('App\Models\Entrega');
	}

	public function user(){
		return $this->belongsTo('App\User')->withTrashed();
	}

	public function endereco(){
		return $this->belongsTo('App\Models\Endereco');
	}

	//MUTATOR
	// Corrigindo formato de data hora
	public function getDataagendadaAttribute($value)
	{
		// dd($value);
		if($value == null){
			return null;
		}
		return date('d/m/Y H:i', strtotime($value));
	} 

	// Corrigindo formato de data hora
	public function setDataagendadaAttribute($value)
	{
		$value = (str_replace( '/', '-', $value));

		$this->attributes['dataagendada'] = date('Y-m-d H:i', strtotime($value));
	}
}
