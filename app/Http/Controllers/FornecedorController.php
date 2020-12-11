<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;

use DB;
use Yajra\Datatables\Datatables;
use App\Http\Requests\FornecedorRequest;

use PDF;
use Excel;
use App\Exports\FornecedorExport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FornecedorController extends Controller
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
        if(Gate::denies('fornecedor-menu')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'data' => route('fornecedor.data'),
            'show' => route('fornecedor.showData'),
            'destroy' => route('fornecedor.destroyTemp'),
            'forceDelete' => route('fornecedor.forceDelete'),
            'restore' => route('fornecedor.restore'),
        );

        return view('fornecedor.index', compact("page"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404,"Not Found");
    }

    // CREATE PESSOA FISICA
    public function createpf()
    {
        if(Gate::denies('fornecedor-create')){
            abort(403,"Não autorizado!");
        }

        $pf = 1;
        return view('fornecedor.createpf', compact("pf"));
    }

    // CREATE PESSOA FISICA
    public function createpj()
    {
        if(Gate::denies('fornecedor-create')){
            abort(403,"Não autorizado!");
        }

        $pf = 0;
        return view('fornecedor.createpj', compact("pf"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FornecedorRequest $request)
    {
        if(Gate::denies('fornecedor-create')){
            abort(403,"Não autorizado!");
        }

        if (strlen($request->cpfcnpj) > 14) {
            $request->request->add(['tipo' => 'Jurídica']);
        } else{
            $request->request->add(['tipo' => 'Física']);
        }

        $fornecedor = Fornecedor::create($request->all());

        $exnotify[] = notifySuccess('Fornecedor '.$fornecedor->nomerazao.' adicionado com sucesso!');
        return redirect()->route('fornecedor.index')->with('notify', $exnotify);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fornecedor  $fornecedor
     * @return \Illuminate\Http\Response
     */
    public function show(Fornecedor $fornecedor)
    {
        abort(404,"Not Found");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fornecedor  $fornecedor
     * @return \Illuminate\Http\Response
     */
    public function edit(Fornecedor $fornecedor)
    {
        if(Gate::denies('fornecedor-edit')){
            abort(403,"Não autorizado!");
        }

        if ($fornecedor->deleted_at != null) {
            $exnotify[] = notifyInfo('Favor restaurar o registro para editar!');
            return redirect()->route('fornecedor.index')->with('notify', $exnotify);
        }
        
        if (strlen($fornecedor->cpfcnpj) > 14) {
            $pf = 0;
            return view('fornecedor.editpj', compact("pf", "fornecedor"));
        } else{
            $pf = 1;
            return view('fornecedor.editpf', compact("pf", "fornecedor"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fornecedor  $fornecedor
     * @return \Illuminate\Http\Response
     */
    public function update(FornecedorRequest $request, Fornecedor $fornecedor)
    {
        if(Gate::denies('fornecedor-edit')){
            abort(403,"Não autorizado!");
        }

        if ($fornecedor->deleted_at != null) {
            $exnotify[] = notifyInfo('Favor restaurar o registro para editar!');
            return redirect()->route('fornecedor.index')->with('notify', $exnotify);
        }
        
        $fornecedor->update($request->all());

        $exnotify[] = notifySuccess('Fornecedor '.$fornecedor->nomerazao.' editado com sucesso!');
        return redirect()->route('fornecedor.index')->with('notify', $exnotify);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fornecedor  $fornecedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fornecedor $fornecedor)
    {
        //
    }

    // SHOW DE UM REGISTRO DO DATATABLES
    public function showData(Request $request)
    {
        if(Gate::denies('fornecedor-show')){
            abort(403,"Não autorizado!");
        }

        $values = Fornecedor::withTrashed()->find($request->id);

        if (empty($values)) {
            $data = '<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="showModel">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Fornecedor não encontrado</h5>
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
        <h5 class="modal-title" id="exampleModalLabel">Fornecedor '.$values->nomerazao.'</h5>
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
        </tr>';
        if ($values->tipo == 'FíSICA') {
            $data .= '<tr>
            <th>Nome</th>
            <td>'.$values->nomerazao.'</td>
            </tr>
            <tr>
            <th>CPF</th>
            <td>'.$values->cpfcnpj.'</td>
            </tr>
            <tr>
            <th>RG</th>
            <td>'.$values->rgie.'</td>
            </tr>';
        } else{
            $data .= '<tr>
            <th>Razão Social</th>
            <td>'.$values->nomerazao.'</td>
            </tr>
            <tr>
            <th>Nome Fantasia</th>
            <td>'.$values->fantasia.'</td>
            </tr>
            <tr>
            <th>CNPJ</th>
            <td>'.$values->cpfcnpj.'</td>
            </tr>
            <tr>
            <th>IE</th>
            <td>'.$values->rgie.'</td>
            </tr>';
        }                        
        $data .= '<tr>
        <th>Email</th>
        <td>'.$values->email.'</td>
        </tr>
        <tr>
        <th>Telefone Celular</th>
        <td>'.$values->celular.'</td>
        </tr>
        <tr>
        <th>Telefone</th>
        <td>'.$values->telefone.'</td>
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
        if(Gate::denies('fornecedor-delete')){
            return notifyAjax403();
        }

        $values = Fornecedor::find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        $values->delete();

        return notifyDestroyTemp();
    }  

    // DESTROY PERMANENTEMENTE UM REGISTRO DO DATATABLES
    public function forceDelete(Request $request)
    {
        if(Gate::denies('fornecedor-del-perm')){
            return notifyAjax403();
        }

        $values = Fornecedor::withTrashed()->find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        // FAZER VALIDAÇÔES SE TEM DEPENDENCIAS E ADICIONAR MENSAGEM DE ERRO CASO TENHA
        if ($values->estoque->first() != null) {
            return response()->json([
                'status' => 'Info',
                'icon' => 'fas fa-bell',
                'title' => '<strong>Alerta:</strong>',
                'message' => 'Fornecedor não pode ser excluido, contem dependências!',
                'type' => 'info'
            ]);
        }

        $values->forceDelete();

        return notifyForceDelete();
    } 

    // RESTORA UM REGISTRO DO DATATABLES
    public function restore(Request $request)
    {
        if(Gate::denies('fornecedor-restore')){
            return notifyAjax403();
        }

        $values = Fornecedor::withTrashed()->find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        $values->restore();

        return notifyRestore();
    }

    // CONSULTA CNPJ
    public function consultacnpj(Request $request)
    {
        $data = preg_replace('/[^0-9]/', '', $request->data);

        if (strlen($data) == 14) {
            $values = file_get_contents('https://www.receitaws.com.br/v1/cnpj/'.$data);
            return $values;
        }
    }

    // DATA PARA O DATATABLES
    public function data()
    {
        $values = Fornecedor::withTrashed()->get();

        return Datatables::of($values)
        ->addColumn('nomerazao', function($values){
            return $values->nomerazao;
        })
        ->addColumn('cpfcnpj', function($values){
            return $values->cpfcnpj;
        })
        ->addColumn('tipo', function($values){
            return $values->tipo;
        })
        ->addColumn('action', function($values){
            $concatbutton = '';
            if(!(Gate::denies('fornecedor-show'))){
                $concatbutton .= '
                <a  class="btn btn-outline-dark btn-sm btnshow mt-1" data-toggle="tooltip" title="Mostrar" href="#" id="'.$values->id.'"><i class="fas fa-eye"></i></a>';
            }
            if(!(Gate::denies('fornecedor-edit'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a  class="btn btn-info btn-sm edit mt-1" data-toggle="tooltip" title="Editar" href="'.route('fornecedor.edit',$values).'"><i class="fas fa-pencil-alt"></i></a>';
                }
            }
            if(!(Gate::denies('fornecedor-delete'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-danger btn-sm delete mt-1" data-toggle="tooltip" title="Excluir" href="#" id="'.$values->id.'"><i class="fas fa-trash-alt"></i></a>';
                }
            }
            if(!(Gate::denies('fornecedor-restore'))){
                if (!$values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-outline-success btn-sm restore mt-1" data-toggle="tooltip" title="Restaurar" href="#" id="'.$values->id.'" ><i class="fas fa-recycle"></i></a>';
                }
            }
            if(!(Gate::denies('fornecedor-del-perm'))){
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
        return view('fornecedor.relatorio.relatorio');
    }

    // GERA RELATORIO
    public function relatoriostore(Request $request)
    {
        $datetime = date('YmdHis'); 
        $data = date('d/m/Y H:i');

        // GERAR PDF
        if ($request->exportacao == 2) {
            if ($request->status == 1) {
                $title = "RELATÓRIO DE FORNECEDORES ATIVOS";
                $fornecedores = Fornecedor::get();
                $count = count($fornecedores);
            } elseif ($request->status == 2){
                $title = "RELATÓRIO DE FORNECEDORES INATIVOS";
                $fornecedores = Fornecedor::onlyTrashed()->get();
                $count = count($fornecedores);
            } else {
                $title = "RELATÓRIO DE TODOS OS FORNECEDORES";
                $fornecedores = Fornecedor::withTrashed()->get();
                $count = count($fornecedores);
            }

            $pdf = PDF::loadview('fornecedor.relatorio.fornecedor', compact('fornecedores', 'title', 'data', 'count'));
            return $pdf->download($datetime.'.pdf');
        }

        // GERAR CSV
        $status = $request->status;
        if ($request->exportacao == 1) {
            return Excel::download(new FornecedorExport($status), $datetime.'.csv');
        }
    }
}
