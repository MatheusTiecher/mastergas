<?php

namespace App\Exports;

use App\Models\Cliente;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ClienteExport implements FromView
{

	public function __construct($status)
	{
		$this->status = $status;
	}

	public function view(): View
	{
		if ($this->status == 1) {
			return view('cliente.relatorio.table', [
				'clientes' => Cliente::all()
			]);		
		} elseif ($this->status == 2){
			return view('cliente.relatorio.table', [
				'clientes' => Cliente::onlyTrashed()->get()
			]);		
		} else {
			return view('cliente.relatorio.table', [
				'clientes' => Cliente::withTrashed()->get()
			]);
		}
	}
}
