<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // importação do softdelete

class Fornecedor extends Model
{
 	use SoftDeletes; // trade do soft deletele 

 	protected $table = 'fornecedors';
 	protected $primaryKey = 'id';
 	protected $fillable = ['nomerazao', 'fantasia', 'cpfcnpj', 'rgie', 'email', 'telefone', 'celular', 'tipo']; 
 	
 	
 	public function estoque()
 	{
 		return $this->hasMany('App\Models\Estoque');
 	}

	// Corrigindo para puchar tudo em maiusculo
 	public function getNomerazaoAttribute($value)
 	{
 		if($value == null){
 			return null;
 		}
 		return strtoupper($value);
 	}

 	public function setNomerazaoAttribute($value)
 	{	
 		$value = strtoupper($value);

 		// dd($value);

 		$this->attributes['nomerazao'] = $value;
 	}
 	
 	public function getTipoAttribute($value)
 	{
 		if($value == null){
 			return null;
 		}
 		return strtoupper($value);
 	}
 }
