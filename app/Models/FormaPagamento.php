<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPagamento extends Model
{
	protected $table = 'forma_pagamentos';
	protected $primaryKey = 'id';
	protected $fillable = ['descricao']; 

	public function venda()
	{
		return $this->hasMany('App\Models\Venda');
	}	
}
