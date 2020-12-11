<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estado;
use App\Models\Cidade;
use Illuminate\Support\Facades\Gate;

class ConfigController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function index()
	{
		if(Gate::denies('admin-admin')){
            abort(403,"Não autorizado!");
        }

		return view('config.index');
	}

	public function estado()
	{
		if(Gate::denies('admin-admin')){
            abort(403,"Não autorizado!");
        }

		$values = file_get_contents('estados.json');

		$i = 0;
		foreach (json_decode($values) as $value) {
			$estado = Estado::where('nome','=',$value->nome)->first(); 
			
			if (empty($estado)) {
				Estado::create([
					'ibge_id' => $value->id,
					'sigla' => $value->sigla,
					'nome' => $value->nome,
				]);
				$i ++;
			}
		}

		$exnotify[] = notifySuccess($i.', Estados adicionados com sucesso!');
		return redirect()->route('config.index')->with('notify', $exnotify);
	}

	public function cidade()
	{
		if(Gate::denies('admin-admin')){
            abort(403,"Não autorizado!");
        }

		$values = file_get_contents('municipios.json');
		// dd (json_encode($values));

		$i = 0;
		foreach (json_decode($values) as $value) {
			$estd = ($value->microrregiao->mesorregiao->UF->id);

			$estado = Estado::where('ibge_id','=',$estd)->first(); 

			if (empty($estado)) {
				$exnotify[] = notifyWarning('Primeiro adicione os estados!');
				return redirect()->route('config.index')->with('notify', $exnotify);
			}

			$cidade = Cidade::where('nome','=',$value->nome)->where('estado_id', '=',$estado->id)->first(); 
			
			if (empty($cidade)) {

				Cidade::create([
					'ibge_id' => $value->id,
					'nome' => $value->nome,
					'estado_id' => $estado->id,
				]);
				$i ++;
			}
		}

		$exnotify[] = notifySuccess($i.', Cidades adicionadas com sucesso!');
		return redirect()->route('config.index')->with('notify', $exnotify);
	}

	public function cidades(Request $request)
	{
		if(Gate::denies('admin-admin')){
            abort(403,"Não autorizado!");
        }

		if (!isset($request->pesquisa)) {
			$cidades = Cidade::paginate(10);
		} else{ 
			$cidades = Cidade::where('nome', 'like', '%' . $request->pesquisa . '%')->paginate(10);
			$cidades->appends(['pesquisa' => $request->pesquisa]);
		}

		return view('config.cidades', compact("cidades"));
	}
}
