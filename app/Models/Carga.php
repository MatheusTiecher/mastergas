<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carga extends Model
{
	protected $table = 'cargas';
	protected $primaryKey = 'id';
	protected $fillable = ['observacao', 'status', 'user_id'];


	public function user()
	{
		return $this->belongsTo('App\User')->withTrashed();
	}

	public function cargaitem()
	{
		return $this->hasMany('App\Models\CargaItem');
	}

	public function venda()
	{
		return $this->hasMany('App\Models\Venda');
	}
}
