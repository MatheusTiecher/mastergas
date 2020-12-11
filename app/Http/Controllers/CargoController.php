<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Permissao;
use App\User;
use Auth;
use DB;
use Yajra\Datatables\Datatables;
use App\Http\Requests\CargoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CargoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('cargo-menu')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'data' => route('cargo.data'),
            'show' => route('cargo.showData'),
            'destroy' => route('cargo.destroyTemp'),
            'forceDelete' => route('cargo.forceDelete'),
            'restore' => route('cargo.restore'),
        );

        return view('cargo.index', compact("page"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->isAdmin() != true){
            abort(403,"Não autorizado!");
        }

        return view('cargo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CargoRequest $request)
    {
        if(Auth::user()->isAdmin() != true){
            abort(403,"Não autorizado!");
        }

        $cargo = Cargo::create($request->all());

        $exnotify[] = notifySuccess('Cargo '.$cargo->nome.', adicionado com sucesso!');
        return redirect()->route('cargo.index')->with('notify', $exnotify);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function show(Cargo $cargo)
    {
        abort(404,"Not Found");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function edit(Cargo $cargo)
    {
        if(Auth::user()->isAdmin() != true){
            abort(403,"Não autorizado!");
        }

        if ($cargo->id == 1) {
            $exnotify[] = notifyInfo('Esse registro não pode ser alterado!');
            return redirect()->route('cargo.index')->with('notify', $exnotify);
        }

        return view('cargo.edit', compact("cargo"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function update(CargoRequest $request, Cargo $cargo)
    {
        if(Auth::user()->isAdmin() != true){
            abort(403,"Não autorizado!");
        }

        if ($cargo->id == 1) {
            $exnotify[] = notifyInfo('Esse registro não pode ser alterado!');
            return redirect()->route('cargo.index')->with('notify', $exnotify);
        }

        $cargo->update($request->all());

        $exnotify[] = notifySuccess('Cargo '.$cargo->nome.' editado com sucesso!');

        return redirect()->route('cargo.index')->with('notify', $exnotify);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cargo $cargo)
    {
        //
    }

    // CARGO PERMISSAO GERENCIAR AS PERMISSOES DO CARGO
    public function permissao(Cargo $cargo)
    {
        if(Auth::user()->isAdmin() != true){
            abort(403,"Não autorizado!");
        }

        if ($cargo->id == 1) {
            $exnotify[] = notifyInfo('Esse registro não pode ser alterado!');
            return redirect()->route('cargo.index')->with('notify', $exnotify);
        }

        $permissoes = Permissao::get();

        $modulos = [
            'cliente',
            'fornecedor',
            'estoque',
            'produto',
            'unidade',
            'rota',
            // 'mensagem',
            'cargo',
            'user',
            'caixa',
            'carga',
            'venda'
        ];

        return view('cargo.permissao', compact("cargo", "permissoes", "modulos"));
    }

    // STORE CARGO PERMISSAO
    public function permissaostore(Request $request, Cargo $cargo)
    {
        if(Auth::user()->isAdmin() != true){
            abort(403,"Não autorizado!");
        }

        if ($cargo->id == 1) {
            $exnotify[] = notifyInfo('Esse registro não pode ser alterado!');
            return redirect()->route('cargo.index')->with('notify', $exnotify);
        }

        $cargo->cargoPermissoes()->sync($request->permissoes);

        $exnotify[] = notifySuccess('Permissões adicionadas ao cargo '.$cargo->nome.' com sucesso!');

        return redirect()->route('cargo.index')->with('notify', $exnotify);
    }

    // SHOW DE UM REGISTRO DO DATATABLES
    public function showData(Request $request)
    {        
        if(Gate::denies('cargo-show')){
            abort(403,"Não autorizado!");
        }

        $values = Cargo::withTrashed()->find($request->id);

        if (empty($values)) {
            $data = '<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="showModel">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cargo não encontrada</h5>
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
        <h5 class="modal-title" id="exampleModalLabel">Cargo '.$values->nome.'</h5>
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
        <td>'.$values->nome.'</td>
        </tr>
        <tr>
        <th>Descrição</th>
        <td>'.$values->descricao.'</td>
        </tr>
        </table>
        </div>';
        if(count($values->cargoPermissoes) > 0) {   
            $data .= '<div class="card-body table-responsive">
            <h5>Permissões</h5>
            <table class="table table-sm align-items-center"> 
            <thead class="thead-light">
            <tr>
            <th>Nome</th>
            <th>Descrição</th>
            </tr>
            </thead>
            <tbody class="list">';
            foreach ($values->cargoPermissoes as $key => $permissao) {
                $data .= '<tr>
                <td>'.$permissao->nome.'</td>
                <td>'.$permissao->descricao.'</td>
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
        if(Gate::denies('cargo-delete')){
            return notifyAjax403();
        }

        $values = Cargo::find($request->id);
        
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
        if(Auth::user()->isAdmin() != true){
            return notifyAjax403();
        }

        $values = Cargo::withTrashed()->find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        if ($values->id == 1) {
            return notifyAjaxInfo();
        }

        if ($values->cargoPermissoes->first() != null || $values->users->first() != null) {
            return response()->json([
                'status' => 'Info',
                'icon' => 'fas fa-bell',
                'title' => '<strong>Alerta:</strong>',
                'message' => 'Cargo não pode ser excluido, contem dependências!',
                'type' => 'info'
            ]);
        }

        $values->forceDelete();
        
        return notifyForceDelete();
    } 

    // RESTORA UM REGISTRO DO DATATABLES
    public function restore(Request $request)
    {
        if(Gate::denies('cargo-restore')){
            return notifyAjax403();
        }
        
        $values = Cargo::withTrashed()->find($request->id);

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
        $values = Cargo::withTrashed()->get();

        return Datatables::of($values)
        ->addColumn('nome', function($values){
            return $values->nome;
        })
        ->addColumn('descricao', function($values){
            return $values->descricao;
        })        
        ->addColumn('action', function($values){
            $concatbutton = '';
            if(!(Gate::denies('cargo-show'))){
                $concatbutton .= '
                <a  class="btn btn-outline-dark btn-sm btnshow mt-1" data-toggle="tooltip" title="Mostrar" href="#" id="'.$values->id.'"><i class="fas fa-eye"></i></a>';
            }
            if(!(Gate::denies('admin-admin'))){
                $concatbutton .= '
                <a class="btn btn-primary btn-sm permissoes mt-1" data-toggle="tooltip" title="Permissões" href="'.route('cargo.permissao',$values).'"><i class="fas fa-folder-open"></i></a>';
            }
            if(!(Gate::denies('admin-admin'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-info btn-sm edit mt-1" data-toggle="tooltip" title="Editar" href="'.route('cargo.edit',$values).'"><i class="fas fa-pencil-alt"></i></a>';
                }
            }
            if(!(Gate::denies('cargo-delete'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-danger btn-sm delete mt-1" data-toggle="tooltip" title="Excluir" href="#" id="'.$values->id.'"><i class="fas fa-trash-alt"></i></a>';
                }
            }
            if(!(Gate::denies('cargo-restore'))){
                if (!$values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-outline-success btn-sm restore mt-1" data-toggle="tooltip" title="Restaurar" href="#" id="'.$values->id.'" ><i class="fas fa-recycle"></i></a>';
                }
            }
            if(!(Gate::denies('admin-admin'))){
                $concatbutton .= '
                <a class="btn btn-outline-danger btn-sm del-perm mt-1" data-toggle="tooltip" title="Exclusão Permanente" href="#" id="'.$values->id.'"><i class="fas fa-fire"></i></a>';
            }
            return $concatbutton;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
