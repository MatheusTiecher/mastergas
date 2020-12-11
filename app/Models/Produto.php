<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // importação do softdelete

use DB;

class Produto extends Model
{
 	use SoftDeletes; // trade do soft deletele 

 	protected $table = 'produtos';
 	protected $primaryKey = 'id';
 	protected $fillable = ['descricao', 'valorvenda', 'minimo', 'unidade_id'];


 	// RELACIONAMENTO
 	public function vendaitem()
 	{
 		return $this->hasMany('App\Models\VendaItem');
 	}

 	public function cargaitem()
 	{
 		return $this->hasMany('App\Models\CargaItem');
 	}
 	
 	// public function mensagem()
 	// {
 	// 	return $this->hasMany('App\Models\Mensagem')->withTrashed();
 	// }

 	public function estoque()
 	{
 		return $this->hasMany('App\Models\Estoque');
 	}	

 	public function unidade()
 	{
 		return $this->belongsTo('App\Models\Unidade')->withTrashed();
 	}

 	// CONSULTA ESTOQUE MINIMO
 	public static function minimo()
 	{
 		return DB::select("SELECT produtos.id, produtos.descricao, produtos.minimo, unidades.sigla, COALESCE(SUM(estoques.restante), 0) AS res FROM produtos JOIN unidades ON unidades.id = produtos.unidade_id LEFT JOIN estoques ON produtos.id = estoques.produto_id WHERE produtos.deleted_at IS NULL GROUP BY produtos.id HAVING produtos.minimo >= res");
 	}

 	// MUTATOR
 	public function setValorvendaAttribute($value)
 	{	
 		$value = str_replace (',', '.', str_replace ('.', '', $value));

 		// dd($value);

 		$this->attributes['valorvenda'] = $value;
 	}

 	public function setMinimoAttribute($value)
 	{	
 		$value = str_replace (',', '.', str_replace ('.', '', $value));

 		// dd($value);

 		$this->attributes['minimo'] = $value;
 	}

 }
