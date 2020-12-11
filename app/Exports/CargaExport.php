<?php

namespace App\Exports;

use App\Models\Venda;
use App\Models\Carga;
use App\Models\Produto;
use App\Models\Rota;
use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use DateTime;
use date;
use DB;
use Illuminate\Database\Eloquent\Builder;

class CargaExport implements FromView
{

	public function __construct($datainicio, $datafim, $relatorio, $status, $entregador, $rota)
	{
		$this->datainicio = $datainicio;
		$this->datafim = $datafim;
		$this->relatorio = $relatorio;
		$this->status = $status;
		$this->entregador = $entregador;
		$this->rota = $rota;

		$this->statusvenda = [
			'andamento',
			'agendado',
			'finalizado',
			'estornado',
			'processando'
		];

		$this->statuscarga = [
			'andamento',
			'processando',
			'vendido',
			'finalizado',
			'estornado',
			'devolvido'
		];
	}

	public function view(): View
	{
		if ($this->relatorio == 1) {
			$datafim = $this->datafim." 23:59";

			$cargas = Carga::select('cargas.*')
			->whereBetween('cargas.created_at', [$this->datainicio, $datafim]);

			if ($this->status != 10) {
				$cargas->where('cargas.status', '=', $this->status);
			}
			if ($this->entregador != 0) {
				$cargas->where('cargas.user_id', '=', $this->entregador);
			}

			$cargas = $cargas->groupBy('cargas.id')->orderBy('cargas.id')->get();

			// dd($cargas);

			$statusvenda = $this->statusvenda;
			$statuscarga = $this->statuscarga;
			
			return view('carga.relatorio.tablecarga', compact('cargas', 'statusvenda', 'statuscarga'));
		}
		if ($this->relatorio == 2) {
			$datafim = $this->datafim." 23:59";

			$cargas = Carga::select('cargas.*')
			->leftJoin('vendas', 'cargas.id', '=', 'vendas.carga_id')
			->whereBetween('cargas.created_at', [$this->datainicio, $datafim]);
			
			if ($this->status != 0) {
				if ($this->rota != 0) {
					$cargas->where('vendas.rota_id', '=', $this->rota);
					$cargas->where('vendas.status', '!=', 3);
				}
			}

			if ($this->status != 10) {
				$cargas->where('cargas.status', '=', $this->status);
			}
			if ($this->entregador != 0) {
				$cargas->where('cargas.user_id', '=', $this->entregador);
			}

			$cargas = $cargas->groupBy('cargas.id')->orderBy('cargas.id')->get();

			// dd($cargas);

			$statusvenda = $this->statusvenda;
			$statuscarga = $this->statuscarga;
			
			return view('carga.relatorio.tablecargageral', compact('cargas', 'statusvenda', 'statuscarga'));
		}
	}
}