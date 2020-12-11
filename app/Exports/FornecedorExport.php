<?php

namespace App\Exports;

use App\Models\Fornecedor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FornecedorExport implements FromView
{
	public function __construct($status)
	{
		$this->status = $status;
	}

	public function view(): View
	{
		if ($this->status == 1) {
			return view('fornecedor.relatorio.table', [
				'fornecedores' => Fornecedor::all()
			]);		
		} elseif ($this->status == 2){
			return view('fornecedor.relatorio.table', [
				'fornecedores' => Fornecedor::onlyTrashed()->get()
			]);		
		} else {
			return view('fornecedor.relatorio.table', [
				'fornecedores' => Fornecedor::withTrashed()->get()
			]);
		}
	}
}