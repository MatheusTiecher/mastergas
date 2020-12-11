<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // importação do softdelete

class Rota extends Model
{
 	use SoftDeletes; // trade do soft deletele 

 	protected $table = 'rotas';
 	protected $primaryKey = 'id';
 	protected $fillable = ['nome', 'descricao', 'cidade_id']; 


 	public function venda()
 	{
 		return $this->hasMany('App\Models\Venda');
 	}
 	
 	public function cidade()
 	{
 		return $this->belongsTo('App\Models\Cidade');
 	}
 }