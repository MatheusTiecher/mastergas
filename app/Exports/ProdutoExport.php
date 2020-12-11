<?php

namespace App\Exports;

use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use DB;

class ProdutoExport implements FromView
{

	public function __construct($status, $relatorio)
	{
		$this->status = $status;
		$this->relatorio = $relatorio;
	}

	public function view(): View
	{
		if ($this->relatorio == 1) {
			if ($this->status == 1) {
				$produtos = DB::select("SELECT p.id AS pro_id, p.descricao AS pro_des, p.minimo AS pro_min, p.valorvenda AS pro_ven, u.sigla AS uni_sig, COALESCE(SUM(e.restante), 0.0) AS res, COALESCE(SUM(e.restante * e.valorcusto), 0.0) AS cus FROM produtos AS p JOIN unidades AS u ON u.id = p.unidade_id LEFT JOIN estoques AS e ON p.id = e.produto_id WHERE p.deleted_at IS NULL GROUP BY p.id");
				return view('produto.relatorio.table', compact('produtos'));
			} elseif ($this->status == 2){
				$produtos = DB::select("SELECT p.id AS pro_id, p.descricao AS pro_des, p.minimo AS pro_min, p.valorvenda AS pro_ven, u.sigla AS uni_sig, COALESCE(SUM(e.restante), 0.0) AS res, COALESCE(SUM(e.restante * e.valorcusto), 0.0) AS cus FROM produtos AS p JOIN unidades AS u ON u.id = p.unidade_id LEFT JOIN estoques AS e ON p.id = e.produto_id WHERE p.deleted_at IS NOT NULL GROUP BY p.id");
				return view('produto.relatorio.table', compact('produtos'));
			} else {
				$produtos = DB::select("SELECT p.id AS pro_id, p.descricao AS pro_des, p.minimo AS pro_min, p.valorvenda AS pro_ven, u.sigla AS uni_sig, COALESCE(SUM(e.restante), 0.0) AS res, COALESCE(SUM(e.restante * e.valorcusto), 0.0) AS cus FROM produtos AS p JOIN unidades AS u ON u.id = p.unidade_id LEFT JOIN estoques AS e ON p.id = e.produto_id GROUP BY p.id");
				return view('produto.relatorio.table', compact('produtos'));
			}
		} else{
			if ($this->status == 1) {
				$produtos = Produto::minimo();
				return view('produto.relatorio.tableminimo', compact('produtos'));
			} elseif ($this->status == 2){
				$produtos = DB::select("SELECT produtos.id, produtos.descricao, produtos.minimo, unidades.sigla, COALESCE(SUM(estoques.restante), 0) AS res FROM produtos JOIN unidades ON unidades.id = produtos.unidade_id LEFT JOIN estoques ON produtos.id = estoques.produto_id WHERE produtos.deleted_at IS NOT NULL GROUP BY produtos.id HAVING produtos.minimo >= res");
				return view('produto.relatorio.tableminimo', compact('produtos'));
			} else {
				$produtos = DB::select("SELECT produtos.id, produtos.descricao, produtos.minimo, unidades.sigla, COALESCE(SUM(estoques.restante), 0) AS res FROM produtos JOIN unidades ON unidades.id = produtos.unidade_id LEFT JOIN estoques ON produtos.id = estoques.produto_id GROUP BY produtos.id HAVING produtos.minimo >= res");
				return view('produto.relatorio.tableminimo', compact('produtos'));
			}           
		}
	}
}
