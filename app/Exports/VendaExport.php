<?php

namespace App\Exports;

use App\Models\Venda;
use App\Models\Cliente;
use App\Models\Caixa;
use App\Models\Entrega;
use App\Models\OcorrenciaEntrega;
use App\Models\Produto;
use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use DateTime;
use date;
use DB;
use Illuminate\Database\Eloquent\Builder;

class VendaExport implements FromView
{

	public function __construct($datainicio, $datafim, $relatorio, $user, $status, $forma_pagamento, $entregador, $exportacao, $forma_entrega)
	{
		$this->datainicio = $datainicio;
		$this->datafim = $datafim;
		$this->relatorio = $relatorio;
		$this->user = $user;
		$this->status = $status;
		$this->forma_pagamento = $forma_pagamento;
		$this->entregador = $entregador;
		$this->exportacao = $exportacao;
		$this->forma_entrega = $forma_entrega;

		$this->statusvenda = [
			'andamento',
			'agendado',
			'finalizado',
			'estornado',
			'processando'
		];

		$this->statusvendaitem = [
			'vendido',
			'devolvido',
			'trocado',
			'estornado',
			'devolvido carga'
		];

		$this->statusentrega = [
			'cancelado',
			'agendado',
			'entregue'
		];
	}

	public function view(): View
	{
		if ($this->relatorio == 1) {
			if ($this->user == 0) {
				$vendas = Venda::where('status', '=', 0)->get();
			} else {
				$vendas = Venda::where('status', '=', 0)->where('user_id', '=', $this->user)->get();
			}
			return view('venda.relatorio.tablevendaaberto', compact('vendas'));
		}
		if ($this->relatorio == 2) {
			if ($this->user == 0) {
				$vendas = Venda::select("vendas.id as ven_id, ocorrencia_entregas.id as oco_id")
				->join('entregas', 'vendas.id', '=', 'entregas.venda_id')
				->join('ocorrencia_entregas', 'entregas.id', '=', 'ocorrencia_entregas.entrega_id')
				->where('ocorrencia_entregas.status', 1)
				->where('vendas.status', 1)
				->where('ocorrencia_entregas.dataagendada', '<=', date('Y-m-d H:i'))
				->orderBy('ocorrencia_entregas.dataagendada', 'asc')
				->get();
			} else {
				$vendas = Venda::select("vendas.id as ven_id, ocorrencia_entregas.id as oco_id")
				->join('entregas', 'vendas.id', '=', 'entregas.venda_id')
				->join('ocorrencia_entregas', 'entregas.id', '=', 'ocorrencia_entregas.entrega_id')
				->where('ocorrencia_entregas.status', 1)
				->where('vendas.status', 1)
				->where('ocorrencia_entregas.dataagendada', '<=', date('Y-m-d H:i'))
				->where('vendas.user_id', '=', $this->user)
				->orderBy('ocorrencia_entregas.dataagendada', 'asc')
				->get();
				$count = count($vendas);
			}

			return view('venda.relatorio.tableentrega', compact('vendas'));
		}
		if ($this->relatorio == 3) {
			if ($this->user == 0) {
				$vendas = Venda::select("vendas.id as ven_id, ocorrencia_entregas.id as oco_id")
				->join('entregas', 'vendas.id', '=', 'entregas.venda_id')
				->join('ocorrencia_entregas', 'entregas.id', '=', 'ocorrencia_entregas.entrega_id')
				->where('ocorrencia_entregas.status', 1)
				->where('vendas.status', 1)
				->orderBy('ocorrencia_entregas.dataagendada', 'asc')
				->get();
			} else {
				$vendas = Venda::select("vendas.id as ven_id, ocorrencia_entregas.id as oco_id")
				->join('entregas', 'vendas.id', '=', 'entregas.venda_id')
				->join('ocorrencia_entregas', 'entregas.id', '=', 'ocorrencia_entregas.entrega_id')
				->where('ocorrencia_entregas.status', 1)
				->where('vendas.status', 1)
				->where('vendas.user_id', '=', $this->user)
				->orderBy('ocorrencia_entregas.dataagendada', 'asc')
				->get();
			}
			return view('venda.relatorio.tableentrega', compact('vendas'));
		}
		if ($this->relatorio == 4) {
			$datafim = $this->datafim." 23:59";

			$vendas = Venda::select('vendas.*')
			->leftJoin('entregas', 'vendas.id', '=', 'entregas.venda_id')
			->leftJoin('ocorrencia_entregas', 'entregas.id', '=', 'ocorrencia_entregas.entrega_id')
			->whereBetween('vendas.created_at', [$this->datainicio, $datafim])
			->where('vendas.status', '!=', 4);

			if ($this->status != 0) {
				if ($this->forma_entrega != 0) {
					$vendas->where('entregas.forma_entrega', '=', $this->forma_entrega);
				}
				if ($this->forma_entrega == 2) {
					if ($this->entregador != 0) {
						$vendas->where('ocorrencia_entregas.user_id', '=', $this->entregador);
					}
				}
				if ($this->forma_pagamento != 0) {
					$vendas->where('vendas.forma_pagamento_id', '=', $this->forma_pagamento);
				}
			}

			if ($this->status != 10) {
				$vendas->where('vendas.status', '=', $this->status);
			}
			if ($this->user != 0) {
				$vendas->where('vendas.user_id', '=', $this->user);
			}

			$vendas = $vendas->groupBy('vendas.id')->orderBy('vendas.id')->get();

			// dd($vendas);

			$statusvenda = $this->statusvenda;
			$statusvendaitem = $this->statusvendaitem;
			$statusentrega = $this->statusentrega;
			
			return view('venda.relatorio.tablevenda', compact('vendas', 'statusvenda', 'statusvendaitem', 'statusentrega'));
		}
	}
}

