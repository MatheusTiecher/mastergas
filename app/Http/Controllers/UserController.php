<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Cargo;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserSenhaRequest;

use Auth;
use DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	// INDEX
	public function index()
	{
		if(Gate::denies('user-menu')){
			abort(403,"Não autorizado!");
		}

		$page = array(
			'data' => route('user.data'),
			'show' => route('user.showData'),
			'destroy' => route('user.destroyTemp'),
			'forceDelete' => route('user.forceDelete'),
			'restore' => route('user.restore'),
		);

		return view('user.index', compact("page"));
	}

    // CREATE
	public function create()
	{
		if(Gate::denies('user-create')){
			abort(403,"Não autorizado!");
		}

		return view('user.create');
	}

    // STORE
	public function store(UserRequest $request)
	{
		if(Gate::denies('user-create')){
			abort(403,"Não autorizado!");
		}

		if (isset($request->entregador)) 
		{
			$request->request->add(['entregador' => 1]);
		} else {
			$request->request->add(['entregador' => 0]);
		}

		$user = User::create($request->all());

		$exnotify[] = notifySuccess('Usuário '.$user->name.', adicionado com sucesso!');
		return redirect()->route('user.index')->with('notify', $exnotify);
	}

	// EDIT
	public function edit(User $user)
	{
		if(Gate::denies('user-edit')){
			abort(403,"Não autorizado!");
		}

		if ($user->id == 1) {
			$exnotify[] = notifyInfo('Esse registro não pode ser alterado!');
			return redirect()->route('user.index')->with('notify', $exnotify);
		}

		return view('user.edit', compact("user"));
	}

    // UPDATE
	public function update(UserEditRequest $request, User $user)
	{
		if(Gate::denies('user-edit')){
			abort(403,"Não autorizado!");
		}

		if ($user->id == 1) {
			$exnotify[] = notifyInfo('Esse registro não pode ser alterado!');
			return redirect()->route('user.index')->with('notify', $exnotify);
		}

		if (isset($request->entregador)) 
		{
			$request->request->add(['entregador' => 1]);
		} else {
			$request->request->add(['entregador' => 0]);
		}

		$user->update($request->all());

		$exnotify[] = notifySuccess('Usuário '.$user->nome.' editado com sucesso!');

		return redirect()->route('user.index')->with('notify', $exnotify);
	}

	// MUDAR SENHA
	public function mudarsenha()
	{
		return view('user.mudarsenha');
	}

    // MUDAR SENHA UPDATE AUTH USER
	public function mudarsenhastore(UserSenhaRequest $request)
	{	
		$user = User::findOrFail(Auth::user()->id);
		$user->remember_token = null;
		$user->update($request->all());

		$exnotify[] = notifySuccess('Senha alterada com sucesso!');

		return redirect()->route('home')->with('notify', $exnotify);
	}    

	// MUDAR SENHA EDIT UPDATE
	public function senhaupdate(UserSenhaRequest $request, User $user)
	{
		if(Gate::denies('user-edit')){
			abort(403,"Não autorizado!");
		}

		$user->remember_token = null;
		$user->update($request->all());

		$exnotify[] = notifySuccess('Senha alterada com sucesso!');

		return redirect()->route('user.index')->with('notify', $exnotify);
	}

    // USER CARGO GERENCIAR OS CARGOS DO USER
	public function cargo(User $user)
	{
		if(Gate::denies('user-cargo')){
			abort(403,"Não autorizado!");
		}

		if ($user->id == 1) {
			$exnotify[] = notifyInfo('Esse registro não pode ser alterado!');
			return redirect()->route('user.index')->with('notify', $exnotify);
		}

		$cargos = Cargo::orderBy('nome', 'asc')->get();

		return view('user.cargo', compact("user", "cargos"));
	}

    // STORE USER CARGO
	public function cargostore(Request $request, User $user)
	{
		if(Gate::denies('user-cargo')){
			abort(403,"Não autorizado!");
		}

		if ($user->id == 1) {
			$exnotify[] = notifyInfo('Esse registro não pode ser alterado!');
			return redirect()->route('user.index')->with('notify', $exnotify);
		}

		$user->userCargos()->sync($request->cargos);

		$exnotify[] = notifySuccess('Cargos adicionadas ao usuário '.$user->name.' com sucesso!');

		return redirect()->route('user.index')->with('notify', $exnotify);
	}

    // SHOW DE UM REGISTRO DO DATATABLES
	public function showData(Request $request)
	{
		if(Gate::denies('user-show')){
			abort(403,"Não autorizado!");
		}

		$values = User::withTrashed()->find($request->id);

		if (empty($values)) {
			$data = '<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="showModel">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
			<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Usuário não encontrada</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>
			<div class="modal-body">
			<h1>Registro não existe mais! Recarregue sua página</h1>
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal"  data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i>  Voltar</button>
			</div>
			</div>
			</div>
			</div>';

			return $data;
		}

		$data = '<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="showModel">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
		<div class="modal-header">
		<h5 class="modal-title" id="exampleModalLabel">Usuário '.$values->name.'</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
		</button>
		</div>
		<div class="modal-body">
		<div class="card-body table-responsive">
		<table class="table table-sm table-striped table-bordered table-striped">
		<tr>
		<th>ID</th>
		<td>'.$values->id.'</td>
		</tr>
		<th>Nome</th>
		<td>'.$values->name.'</td>
		</tr>
		<tr>
		<th>Email</th>
		<td>'.$values->email.'</td>
		</tr>
		<tr>
		<th>Telefone</th>
		<td>'.$values->telefone.'</td>
		</tr>
		<tr>
		<th>Celular</th>
		<td>'.$values->celular.'</td>
		</tr>
		<tr>
		<th>Entregador</th>';
		if ($values->entregador == 1) {
			$data .= '<td>Sim</td>';
		} else{
			$data .= '<td>Não</td>';
		}
		$data .= '</tr>
		</table>
		</div>';
		if(count($values->userCargos) > 0) {   
			$data .= '<div class="card-body table-responsive">
			<h5>Cargos</h5>
			<table class="table table-sm align-items-center"> 
			<thead class="thead-light">
			<tr>
			<th>Nome</th>
			<th>Descrição</th>
			</tr>
			</thead>
			<tbody class="list">';
			foreach ($values->userCargos as $key => $cargo) {
				$data .= '<tr>
				<td>'.$cargo->nome.'</td>
				<td>'.$cargo->descricao.'</td>
				</tr>';
			}
			$data .= '</tbody>
			</table>
			</div>';
		}
		$data .= '</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"  data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i>  Voltar</button>
		</div>
		</div>
		</div>
		</div>';

		return $data;
	}

    // DESTROY TEMPORARIAMENTE UM REGISTRO DO DATATABLES
	public function destroyTemp(Request $request)
	{
		if(Gate::denies('user-delete')){
			return notifyAjax403();
		}

		$values = User::find($request->id);

		if (empty($values)) {
			return notifyAjaxNotFound();
		}

		if ($values->id == 1) {
			return notifyAjaxInfo();
		}

		$values->delete();

		return notifyDestroyTemp();
	}  

    // DESTROY PERMANENTEMENTE UM REGISTRO DO DATATABLES
	public function forceDelete(Request $request)
	{
		if(Gate::denies('user-del-perm')){
			return notifyAjax403();
		}

		$values = User::withTrashed()->find($request->id);

		if (empty($values)) {
			return notifyAjaxNotFound();
		}

		if ($values->id == 1) {
			return notifyAjaxInfo();
		}

		if ($values->userCargos->first() != null || $values->lancamento->first() != null || $values->caixa->first() != null || $values->venda->first() != null || $values->carga->first() != null || $values->ocorrenciaentrega->first() != null) {
			return response()->json([
				'status' => 'Info',
				'icon' => 'fas fa-bell',
				'title' => '<strong>Alerta:</strong>',
				'message' => 'Usuário não pode ser excluido, contem dependências!',
				'type' => 'info'
			]);
		}

		$values->forceDelete();

		return notifyForceDelete();
	} 

    // RESTORA UM REGISTRO DO DATATABLES
	public function restore(Request $request)
	{
		if(Gate::denies('user-restore')){
			return notifyAjax403();
		}

		$values = User::withTrashed()->find($request->id);

		if (empty($values)) {
			return notifyAjaxNotFound();
		}

		if ($values->id == 1) {
			return notifyAjaxInfo();
		}

		$values->restore();

		return notifyRestore(); 
	}

    // DATA PARA O DATATABLES
	public function data()
	{
		$values = User::withTrashed()->get();

		return Datatables::of($values)
		->addColumn('name', function($values){
			return $values->name;
		})
		->addColumn('email', function($values){
			return $values->email;
		})        
		->addColumn('action', function($values){
			$concatbutton = '';
			if(!(Gate::denies('user-show'))){
				$concatbutton .= '
				<a  class="btn btn-outline-dark btn-sm btnshow mt-1" data-toggle="tooltip" title="Mostrar" href="#" id="'.$values->id.'"><i class="fas fa-eye"></i></a>';
			}
			if(!(Gate::denies('user-cargo'))){
				$concatbutton .= '
				<a class="btn btn-primary btn-sm cargos mt-1" data-toggle="tooltip" title="Cargos" href="'.route('user.cargo',$values).'"><i class="fas fa-folder-open"></i></a>';
			}
			if(!(Gate::denies('user-edit'))){
				if ($values->deleted_at == null) {
					$concatbutton .= '
					<a class="btn btn-info btn-sm edit mt-1" data-toggle="tooltip" title="Editar" href="'.route('user.edit',$values).'"><i class="fas fa-pencil-alt"></i></a>';
				}
			}
			if(!(Gate::denies('user-delete'))){
				if ($values->deleted_at == null) {
					$concatbutton .= '
					<a class="btn btn-danger btn-sm delete mt-1" data-toggle="tooltip" title="Excluir" href="#" id="'.$values->id.'"><i class="fas fa-trash-alt"></i></a>';
				}
			}
			if(!(Gate::denies('user-restore'))){
				if (!$values->deleted_at == null) {
					$concatbutton .= '
					<a class="btn btn-outline-success btn-sm restore mt-1" data-toggle="tooltip" title="Restaurar" href="#" id="'.$values->id.'" ><i class="fas fa-recycle"></i></a>';
				}
			}
			if(!(Gate::denies('user-del-perm'))){
				$concatbutton .= '
				<a class="btn btn-outline-danger btn-sm del-perm mt-1" data-toggle="tooltip" title="Exclusão Permanente" href="#" id="'.$values->id.'"><i class="fas fa-fire"></i></a>';
			}
			return $concatbutton;
		})
		->rawColumns(['action'])
		->make(true);
	}

}
