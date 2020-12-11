<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // importação do softdelete

class Mensagem extends Model
{
 	// use SoftDeletes; // trade do soft deletele 

 	// protected $table = 'mensagems';
 	// protected $primaryKey = 'id';
 	// protected $fillable = ['nome', 'msg', 'ativo', 'rotina', 'hora', 'status', 'produto_id', 'rotina_id'];

 	// public function produto()
 	// {
 	// 	return $this->belongsTo('App\Models\Produto')->withTrashed();
 	// }
 }