<?php

namespace App\Exports;

use App\Models\Caixa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use DateTime;
use date;

class CaixaExport implements FromView
{

	public function __construct($datainicio, $datafim, $relatorio, $user)
	{
		$this->datainicio = $datainicio;
		$this->datafim = $datafim;
		$this->relatorio = $relatorio;
		$this->user = $user;
	}

	public function view(): View
	{
		if ($this->relatorio == 1) {
			$datafim = new DateTime($this->datafim.'23:59');
			$caixas = Caixa::whereBetween('created_at', [$this->datainicio, $datafim])->get();
			if ($this->user == 0) {
				return view('caixa.relatorio.tableperiodo', compact('caixas'));
			}
			$user = $this->user;
			return view('caixa.relatorio.tableperiodo', compact('user', 'caixas'));
		}
		if ($this->relatorio == 3) {
			$caixas = Caixa::whereBetween('created_at', [$this->datainicio, $this->datafim])->get();

			return view('caixa.relatorio.tablefechamento', compact('caixas'));
		}
	}
}
