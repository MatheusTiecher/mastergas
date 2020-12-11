<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use Illuminate\Http\Request;
use App\Http\Requests\UnidadeRequest;

use DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Gate;

class UnidadeController extends Controller
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
        if(Gate::denies('unidade-menu')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'data' => route('unidade.data'),
            'show' => route('unidade.showData'),
            'destroy' => route('unidade.destroyTemp'),
            'forceDelete' => route('unidade.forceDelete'),
            'restore' => route('unidade.restore'),
        );

        return view('unidade.index', compact("page"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('unidade-create')){
            abort(403,"Não autorizado!");
        }

        return view('unidade.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnidadeRequest $request)
    {
        if(Gate::denies('unidade-create')){
            abort(403,"Não autorizado!");
        }

        if (isset($request->inteiro)) 
        {
            $request->request->add(['inteiro' => 1]);
        } else {
            $request->request->add(['inteiro' => 0]);
        }

        $unidade = unidade::create($request->all());

        $exnotify[] = notifySuccess('Unidade '.$unidade->descricao.', foi adicionada com sucesso!');
        return redirect()->route('unidade.index')->with('notify', $exnotify);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unidade  $unidade
     * @return \Illuminate\Http\Response
     */
    public function show(Unidade $unidade)
    {
        abort(404,"Not Found");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unidade  $unidade
     * @return \Illuminate\Http\Response
     */
    public function edit(Unidade $unidade)
    {
        if(Gate::denies('unidade-edit')){
            abort(403,"Não autorizado!");
        }

        return view('unidade.edit', compact("unidade"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unidade  $unidade
     * @return \Illuminate\Http\Response
     */
    public function update(UnidadeRequest $request, Unidade $unidade)
    { 
        if(Gate::denies('unidade-edit')){
            abort(403,"Não autorizado!");
        }

        if (isset($request->inteiro)) 
        {
            $request->request->add(['inteiro' => 1]);
        } else {
            $request->request->add(['inteiro' => 0]);
        }

        if ($unidade->produto()->withTrashed()->first() != null) {
            if ($unidade->inteiro != $request->inteiro) {
                $exnotify[] = notifyWarning('Tipo da unidade não pode ser alterado! Por que ja existem registros vinculados');
                return back()->with('notify', $exnotify);
            }
        }
        
        $unidade->update($request->all());

        $exnotify[] = notifySuccess('Unidade '.$unidade->descricao.', foi editado com sucesso!');
        return redirect()->route('unidade.index')->with('notify', $exnotify);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unidade  $unidade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unidade $unidade)
    {
        //
    }

    // SHOW DE UM REGISTRO DO DATATABLES
    public function showData(Request $request)
    {
        if(Gate::denies('unidade-show')){
            abort(403,"Não autorizado!");
        }

        $values = Unidade::withTrashed()->find($request->id);

        if (empty($values)) {
            $data = '<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="showModel">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Unidade não encontrada</h5>
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
        <h5 class="modal-title" id="exampleModalLabel">Unidade '.$values->descricao.'</h5>
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
        <th>Descrição</th>
        <td>'.$values->descricao.'</td>
        </tr>
        <tr>
        <th>Sigla</th>
        <td>'.$values->sigla.'</td>
        </tr>
        <tr>
        <th>Inteiro</th>';
        if ($values->inteiro == 1) {
            $data .= '<td>Inteiro</td>';
        } else{
            $data .= '<td>Decimal</td>';
        }
        $data .= '</tr>
        </table>
        </div>
        </div>
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
        if(Gate::denies('unidade-delete')){
            return notifyAjax403();
        }

        $values = Unidade::find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        $values->delete();

        return notifyDestroyTemp(); 
    }  

    // DESTROY PERMANENTEMENTE UM REGISTRO DO DATATABLES
    public function forceDelete(Request $request)
    {
        if(Gate::denies('unidade-del-perm')){
            return notifyAjax403();
        }

        $values = Unidade::withTrashed()->find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        // FAZER VALIDAÇÔES SE TEM DEPENDENCIAS E ADICIONAR MENSAGEM DE ERRO CASO TENHA
        if ($values->produto()->withTrashed()->first() != null) {
            return response()->json([
                'status' => 'Sucesso',
                'icon' => 'fas fa-bell',
                'title' => '<strong>Alerta:</strong>',
                'message' => 'Unidade não pode ser exlcuida, contem dependências!',
                'type' => 'info'
            ]);
        }

        $values->forceDelete();

        return notifyForceDelete();
    }

    // RESTORA UM REGISTRO DO DATATABLES
    public function restore(Request $request)
    {
        if(Gate::denies('unidade-restore')){
            return notifyAjax403();
        }

        $values = Unidade::withTrashed()->find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }
        
        $values->restore();

        return notifyRestore();   
    }

    // DATA PARA O DATATABLES
    public function data()
    {
        $values = Unidade::withTrashed()->get();

        return Datatables::of($values)
        ->addColumn('descricao', function($values){
            return $values->descricao;
        })
        ->addColumn('sigla', function($values){
            return $values->sigla;
        })        
        ->addColumn('tipo', function($values){
            if ($values->inteiro == 1) {
                return "Inteiro";
            }
            return "Decimal";
        })
        ->addColumn('action', function($values){
            $concatbutton = '';
            if(!(Gate::denies('unidade-show'))){
                $concatbutton .= '
                <a  class="btn btn-outline-dark btn-sm btnshow mt-1" data-toggle="tooltip" title="Mostrar" href="#" id="'.$values->id.'"><i class="fas fa-eye"></i></a>';
            }
            if(!(Gate::denies('unidade-edit'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a  class="btn btn-info btn-sm edit mt-1" data-toggle="tooltip" title="Editar" href="'.route('unidade.edit',$values).'"><i class="fas fa-pencil-alt"></i></a>';
                }
            }
            if(!(Gate::denies('unidade-delete'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-danger btn-sm delete mt-1" data-toggle="tooltip" title="Excluir" href="#" id="'.$values->id.'"><i class="fas fa-trash-alt"></i></a>';
                }
            }
            if(!(Gate::denies('unidade-restore'))){
                if (!$values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-outline-success btn-sm restore mt-1" data-toggle="tooltip" title="Restaurar" href="#" id="'.$values->id.'" ><i class="fas fa-recycle"></i></a>';
                }
            }
            if(!(Gate::denies('unidade-del-perm'))){
                $concatbutton .= '
                <a class="btn btn-outline-danger btn-sm del-perm mt-1" data-toggle="tooltip" title="Exclusão Permanente" href="#" id="'.$values->id.'" ><i class="fas fa-fire"></i></a>';
            }
            return $concatbutton;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
