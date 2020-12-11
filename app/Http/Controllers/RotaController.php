<?php

namespace App\Http\Controllers;

use App\Models\Rota;
use App\Models\Carga;
use App\Models\CargaItem;
use Illuminate\Http\Request;
use App\Http\Requests\RotaRequest;

use Yajra\Datatables\Datatables;
use DB;
use Illuminate\Support\Facades\Gate;

use PDF;
use Excel;
use App\Exports\RotaExport;

class RotaController extends Controller
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
        if(Gate::denies('rota-menu')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'data' => route('rota.data'),
            'show' => route('rota.showData'),
            'destroy' => route('rota.destroyTemp'),
            'forceDelete' => route('rota.forceDelete'),
            'restore' => route('rota.restore'),
        );

        return view('rota.index', compact("page"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('rota-create')){
            abort(403,"Não autorizado!");
        }

        return view('rota.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RotaRequest $request)
    {
        if(Gate::denies('rota-create')){
            abort(403,"Não autorizado!");
        }

        $rota = Rota::create($request->all());

        $exnotify[] = notifySuccess('Rota '.$rota->nomerazao.', foi adicionada com sucesso!');
        return redirect()->route('rota.index')->with('notify', $exnotify);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rota  $rota
     * @return \Illuminate\Http\Response
     */
    public function show(Rota $rota)
    {
        abort(404,"Not Found");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rota  $rota
     * @return \Illuminate\Http\Response
     */
    public function edit(Rota $rota)
    {
        if(Gate::denies('rota-edit')){
            abort(403,"Não autorizado!");
        }

        if ($rota->cidade_id != null) {
            $data = array(
                'id' => $rota->cidade_id,
                'text' => $rota->cidade->nome,
                'selected' => true,
            );
            $data = json_encode($data);
            $data = "[".$data."]";
        }else{
            $data = "[]";
        }

        return view('rota.edit', compact("rota", "data"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rota  $rota
     * @return \Illuminate\Http\Response
     */
    public function update(RotaRequest $request, Rota $rota)
    {
        if(Gate::denies('rota-edit')){
            abort(403,"Não autorizado!");
        }

        if ($rota->deleted_at != null) {
            $exnotify[] = notifyInfo('Favor restaurar o registro para editar!');
            return redirect()->route('rota.index')->with('notify', $exnotify);
        }
        
        $rota->update($request->all());

        $exnotify[] = notifySuccess('Rota '.$rota->nomerazao.', foi editada com sucesso!');

        return redirect()->route('rota.index')->with('notify', $exnotify);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rota  $rota
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rota $rota)
    {
        //
    }

    // SHOW DE UM REGISTRO DO DATATABLES
    public function showData(Request $request)
    {
        if(Gate::denies('rota-show')){
            abort(403,"Não autorizado!");
        }

        $values = Rota::withTrashed()->find($request->id);

        if (empty($values)) {
            $data = '<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="showModel">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Rota não encontrada</h5>
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
        <h5 class="modal-title" id="exampleModalLabel">Rota '.$values->nome.'</h5>
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
        <th>Observação</th>
        <td>'.$values->descricao.'</td>
        </tr>
        <tr>
        <th>Cidade</th>
        <td>'.$values->cidade->nome.' - '.$values->cidade->estado->sigla.'</td>
        </tr>
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
        if(Gate::denies('rota-delete')){
            return notifyAjax403();
        }

        $values = Rota::find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        $values->delete();

        return notifyDestroyTemp();
    }  

    // DESTROY PERMANENTEMENTE UM REGISTRO DO DATATABLES
    public function forceDelete(Request $request)
    {
        if(Gate::denies('rota-del-perm')){
            return notifyAjax403();
        }

        $values = Rota::withTrashed()->find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        // FAZER VALIDAÇÔES SE TEM DEPENDENCIAS E ADICIONAR MENSAGEM DE ERRO CASO TENHA
        if ($values->venda->first() != null) {
            return response()->json([
                'status' => 'Info',
                'icon' => 'fas fa-bell',
                'title' => '<strong>Alerta:</strong>',
                'message' => 'Rota não pode ser excluido, contem pendências!',
                'type' => 'info'
            ]);
        }

        $values->forceDelete();

        return notifyForceDelete();
    } 

    // RESTORA UM REGISTRO DO DATATABLES
    public function restore(Request $request)
    {
        if(Gate::denies('rota-restore')){
            return notifyAjax403();
        }

        $values = Rota::withTrashed()->find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        $values->restore();

        return notifyRestore(); 
    }

    // SELECT2 DO CIDADE NA TELA DE CADASTRO DE ROTA - infocidade
    public function infocidade(Request $request)
    {
        $values = DB::select("SELECT cidades.id as id, CONCAT( cidades.nome, ' - ', estados.sigla) as text FROM cidades left join estados on cidades.estado_id = estados.id  where cidades.nome like '%{$request->q}%' LIMIT 20;");
        return $values;
    }

    // DATA PARA O DATATABLES
    public function data()
    {
        $values = Rota::withTrashed()->get();

        return Datatables::of($values)
        ->addColumn('nome', function($values){
            return $values->nome;
        })
        ->addColumn('cidade', function($values){
            return $values->cidade->nome.'/'.$values->cidade->estado->sigla;
        })
        ->addColumn('action', function($values){
            $concatbutton = '';
            if(!(Gate::denies('rota-show'))){
                $concatbutton .= '
                <a  class="btn btn-outline-dark btn-sm btnshow mt-1" data-toggle="tooltip" title="Mostrar" href="#" id="'.$values->id.'"><i class="fas fa-eye"></i></a>';
            }
            if(!(Gate::denies('rota-edit'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a  class="btn btn-info btn-sm edit mt-1" data-toggle="tooltip" title="Editar" href="'.route('rota.edit',$values).'"><i class="fas fa-pencil-alt"></i></a>';
                }
            }
            if(!(Gate::denies('rota-delete'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-danger btn-sm delete mt-1" data-toggle="tooltip" title="Excluir" href="#" id="'.$values->id.'"><i class="fas fa-trash-alt"></i></a>';
                }
            }
            if(!(Gate::denies('rota-restore'))){
                if (!$values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-outline-success btn-sm restore mt-1" data-toggle="tooltip" title="Restaurar" href="#" id="'.$values->id.'" ><i class="fas fa-recycle"></i></a>';
                }
            }
            if(!(Gate::denies('rota-del-perm'))){
                $concatbutton .= '
                <a class="btn btn-outline-danger btn-sm del-perm mt-1" data-toggle="tooltip" title="Exclusão Permanente" href="#" id="'.$values->id.'" ><i class="fas fa-fire"></i></a>';
            }
            return $concatbutton;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    // RELATORIOS
    public function relatorio()
    {
        return view('rota.relatorio.relatorio');
    }

    // GERA RELATORIO
    public function relatoriostore(Request $request)
    {
        $datetime = date('YmdHis'); 
        $data = date('d/m/Y H:i');

        // GERAR PDF
        if ($request->exportacao == 2) {
            if ($request->status == 1) {
                $title = "RELATÓRIO DE ROTAS ATIVOS";
                $rotas = Rota::get();
                $count = count($rotas);
            } elseif ($request->status == 2){
                $title = "RELATÓRIO DE ROTAS INATIVOS";
                $rotas = Rota::onlyTrashed()->get();
                $count = count($rotas);
            } else {
                $title = "RELATÓRIO DE TODOS OS ROTAS";
                $rotas = Rota::withTrashed()->get();
                $count = count($rotas);
            }

            $pdf = PDF::loadview('rota.relatorio.rota', compact('rotas', 'title', 'data', 'count'));
            return $pdf->download($datetime.'.pdf');
        }

        // GERAR CSV
        $status = $request->status;
        if ($request->exportacao == 1) {
            return Excel::download(new RotaExport($status), $datetime.'.csv');
        }
    }
}
