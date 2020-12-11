<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // importação do softdelete

class Unidade extends Model
{
 	use SoftDeletes; // trade do soft deletele 

 	protected $table = 'unidades';
 	protected $primaryKey = 'id';
 	protected $fillable = ['descricao', 'sigla', 'inteiro'];
 	
 	
 	public function produto()
 	{
 		return $this->hasMany('App\Models\Produto')->withTrashed();
 	}	

 	public function getSiglaAttribute($value)
 	{
 		return mb_strtoupper($value);
 	}
 }
