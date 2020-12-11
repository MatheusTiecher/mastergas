<?php

namespace App\Exports;

use App\Models\Estoque;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EstoqueExport implements FromView
{

	public function __construct($status)
	{
		$this->status = $status;
	}

	public function view(): View
	{
		if ($this->status == 1) {
			return view('estoque.relatorio.table', [
				'estoques' => Estoque::where('restante', '>', 0)->orderBy('created_at', 'asc')->get()
			]);		
		}
	}
}
