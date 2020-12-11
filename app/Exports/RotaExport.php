<?php

namespace App\Exports;

use App\Models\Rota;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RotaExport implements FromView
{

	public function __construct($status)
	{
		$this->status = $status;
	}

	public function view(): View
	{
		if ($this->status == 1) {
			return view('rota.relatorio.table', [
				'rotas' => Rota::all()
			]);		
		} elseif ($this->status == 2){
			return view('rota.relatorio.table', [
				'rotas' => Rota::onlyTrashed()->get()
			]);		
		} else {
			return view('rota.relatorio.table', [
				'rotas' => Rota::withTrashed()->get()
			]);
		}
	}
}
