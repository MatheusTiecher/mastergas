<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
	protected $table = 'caixas';
	protected $primaryKey = 'id';
	protected $fillable = ['inicial', 'entrada', 'saida', 'user_id'];


	public function lancamento()
	{
		return $this->hasMany('App\Models\Lancamento');
	}

	public function user()
	{
		return $this->belongsTo('App\User')->withTrashed();
	}
}
