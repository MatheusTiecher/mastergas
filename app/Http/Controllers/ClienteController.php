<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cliente;
use App\Models\Endereco;
use App\Models\Cidade;
use App\Models\Estado;

use PDF;
use Excel;
use App\Exports\ClienteExport;

use DB;
use Yajra\Datatables\Datatables;
use App\Http\Requests\ClienteRequest;
use App\Http\Requests\EnderecoRequest;
use Illuminate\Support\Facades\Gate;

class ClienteController extends Controller
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
        if(Gate::denies('cliente-menu')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'data' => route('cliente.data'),
            'show' => route('cliente.showData'),
            'destroy' => route('cliente.destroyTemp'),
            'forceDelete' => route('cliente.forceDelete'),
            'restore' => route('cliente.restore'),
        );

        return view('cliente.index', compact("page"));
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
        if(Gate::denies('cliente-create')){
            abort(403,"Não autorizado!");
        }

        $pf = 1;
        return view('cliente.createpf', compact("pf"));
    }

    // CREATE PESSOA FISICA
    public function createpj()
    {
        if(Gate::denies('cliente-create')){
            abort(403,"Não autorizado!");
        }

        $pf = 0;
        return view('cliente.createpj', compact("pf"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // STORE PF PJ
    public function store(ClienteRequest $request) 
    {
        if(Gate::denies('cliente-create')){
            abort(403,"Não autorizado!");
        }

        if (strlen($request->cpfcnpj) > 14) {
            $request->request->add(['tipo' => 'Jurídica']);
        } else{
            $request->request->add(['tipo' => 'Física']);
        }

        $cliente = Cliente::create($request->all());

        $exnotify[] = notifySuccess('Cliente '.$cliente->nomerazao.', adicionado com sucesso!');
        return redirect()->route('cliente.index')->with('notify', $exnotify);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        abort(404,"Not Found");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        if(Gate::denies('cliente-edit')){
            abort(403,"Não autorizado!");
        }

        if (strlen($cliente->cpfcnpj) > 14) {
            $pf = 0;
            return view('cliente.editpj', compact("pf", "cliente"));
        } else{
            $pf = 1;
            return view('cliente.editpf', compact("pf", "cliente"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteRequest $request, Cliente $cliente)
    {
        if(Gate::denies('cliente-edit')){
            abort(403,"Não autorizado!");
        }
        
        $cliente->update($request->all());

        $exnotify[] = notifySuccess('Cliente '.$cliente->nomerazao.' editado com sucesso!');

        return redirect()->route('cliente.index')->with('notify', $exnotify);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        //
    }      

    // SHOW DE UM REGISTRO DO DATATABLES
    public function showData(Request $request)
    {
        if(Gate::denies('cliente-show')){
            abort(403,"Não autorizado!");
        }

        $values = Cliente::withTrashed()->find($request->id);

        if (empty($values)) {
            $data = '<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="showModel">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cliente não encontrado</h5>
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
        <h5 class="modal-title" id="exampleModalLabel">Cliente '.$values->nomerazao.'</h5>
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
        </div>';
        if(count($values->endereco) > 0) {   
            $data .= '<div class="card-body table-responsive">
            <h5>Endereços</h5>
            <table class="table table-sm align-items-center"> 
            <thead class="thead-light">
            <tr>
            <th>Cidade</th>
            <th>Rua</th>
            <th>Bairro</th>
            <th>N°</th>
            </tr>
            </thead>
            <tbody class="list">';
            foreach ($values->endereco as $key => $endereco) {
                $data .= '<tr>
                <td>'.$endereco->cidade->nome.'/'.$endereco->cidade->estado->sigla.'</td>
                <td>'.$endereco->rua.'</td>
                <td>'.$endereco->bairro.'</td>
                <td>'.$endereco->numero.'</td>
                </tr>';
            }
            $data .= '</tbody>
            </table>
            </div>';
        }
        $data .= '
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
        if(Gate::denies('cliente-delete')){
            return notifyAjax403();
        }

        $values = Cliente::find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        $values->delete();

        return notifyDestroyTemp();
    }  

    // DESTROY PERMANENTEMENTE UM REGISTRO DO DATATABLES
    public function forceDelete(Request $request)
    {
        if(Gate::denies('cliente-del-perm')){
            return notifyAjax403();
        }

        $values = Cliente::withTrashed()->find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        if (($values->venda->first() != null) || ($values->endereco->first() != null)) {
            return response()->json([
                'status' => 'Info',
                'icon' => 'fas fa-bell',
                'title' => '<strong>Alerta:</strong>',
                'message' => 'Cliente não pode ser excluido, contem dependências!',
                'type' => 'info'
            ]);
        }

        $values->forceDelete();
        
        return notifyForceDelete();
    } 

    // RESTORA UM REGISTRO DO DATATABLES
    public function restore(Request $request)
    {
        if(Gate::denies('cliente-restore')){
            return notifyAjax403();
        }

        $values = Cliente::withTrashed()->find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        $values->restore();

        return notifyRestore(); 
    }   

    // CONTROLLER ENDERECO DO CLIENTE
    // CREATE ENDERECO
    public function createEndereco(Cliente $cliente)
    {
        if(Gate::denies('cliente-endereco-create')){
            abort(403,"Não autorizado!");
        }

        return view('cliente.endereco.create', compact("cliente"));        
    }

    // STORE ENDERECO
    public function storeEndereco(EnderecoRequest $request, Cliente $cliente)
    {
        if(Gate::denies('cliente-endereco-create')){
            abort(403,"Não autorizado!");
        }

        $cliente->endereco()->create($request->all());

        $exnotify[] = notifySuccess('Endereço adicionado com sucesso!');
        return redirect()->route('cliente.edit', $cliente)->with('notify', $exnotify);
    }

    // EDIT ENDERECO
    public function editEndereco(Endereco $endereco)
    {
        if(Gate::denies('cliente-endereco-edit')){
            abort(403,"Não autorizado!");
        }

        if ($endereco->cliente == null) {
            $exnotify[] = notifyInfo('Favor restaurar o cliente para editar!');
            return redirect()->route('cliente.index')->with('notify', $exnotify);
        }

        if ($endereco->cidade_id != null) {
            $data = array(
                'id' => $endereco->cidade_id,
                'text' => $endereco->cidade->nome,
                'selected' => true,
            );
            $data = json_encode($data);
            $data = "[".$data."]";
        }else{
            $data = "[]";
        }

        return view('cliente.endereco.edit', compact("endereco", "data"));        
    }

    // UPDATE ENDERECO
    public function updateEndereco(EnderecoRequest $request, Endereco $endereco)
    {
        if(Gate::denies('cliente-endereco-edit')){
            abort(403,"Não autorizado!");
        }

        if ($endereco->cliente == null) {
            $exnotify[] = notifyInfo('Favor restaurar o cliente para editar!');
            return redirect()->route('cliente.index')->with('notify', $exnotify);
        }

        $endereco->update($request->all());

        $exnotify[] = notifySuccess('Endereço editado com sucesso!');
        return redirect()->route('cliente.edit', $endereco->cliente)->with('notify', $exnotify);
    }

    // DESTROY ENDERECO
    public function destroyEndereco(Endereco $endereco)
    {
        if(Gate::denies('cliente-endereco-del-perm')){
            abort(403,"Não autorizado!");
        }

        if ($endereco->cliente == null) {
            $exnotify[] = notifyInfo('Favor restaurar o cliente para editar!');
            return redirect()->route('cliente.index')->with('notify', $exnotify);
        }

        if (empty($endereco->ocorrenciaentrega->first())) {
            $endereco->forceDelete();
            $exnotify[] = notifySuccess('Endereço excluido com sucesso!');
        } else {
            $exnotify[] = notifyWarning('Endereço não pode ser excluido, contem dependências!');
        }

        return redirect()->route('cliente.edit', $endereco->cliente)->with('notify', $exnotify);
    }

    // SELECT2 DO CIDADE NA TELA DE ENDERECO CLIENTE
    public function info(Request $request)
    {
        $values = DB::select("SELECT cidades.id as id, CONCAT( cidades.nome, ' - ', estados.sigla) as text FROM cidades left join estados on cidades.estado_id = estados.id  where cidades.nome like '%{$request->q}%' LIMIT 20;");
        return $values;
    }

    // CONSULTA CNPJ
    public function consultacnpj(Request $request)
    {
        // $data = preg_replace('/[^0-9]/', '', $request->data);

        // if (strlen($data) == 14) {
        //     $values = file_get_contents('https://www.receitaws.com.br/v1/cnpj/'.$data);
        //     return $values;
        // }
    }

    // DATA PARA O DATATABLES
    public function data()
    {
        $values = Cliente::withTrashed()->get();

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
            if(!(Gate::denies('cliente-show'))){
                $concatbutton .= '
                <a  class="btn btn-outline-dark btn-sm btnshow mt-1" data-toggle="tooltip" title="Mostrar" href="#" id="'.$values->id.'"><i class="fas fa-eye"></i></a>';
            }
            if(!(Gate::denies('cliente-edit'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a  class="btn btn-info btn-sm edit mt-1" data-toggle="tooltip" title="Editar" href="'.route('cliente.edit',$values).'"><i class="fas fa-pencil-alt"></i></a>';
                }
            }
            if(!(Gate::denies('cliente-delete'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-danger btn-sm delete mt-1" data-toggle="tooltip" title="Excluir" href="#" id="'.$values->id.'"><i class="fas fa-trash-alt"></i></a>';
                }
            }
            if(!(Gate::denies('cliente-restore'))){
                if (!$values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-outline-success btn-sm restore mt-1" data-toggle="tooltip" title="Restaurar" href="#" id="'.$values->id.'" ><i class="fas fa-recycle"></i></a>';
                }
            }
            if(!(Gate::denies('cliente-del-perm'))){
                $concatbutton .= '
                <a class="btn btn-outline-danger btn-sm del-perm mt-1" data-toggle="tooltip" title="Exclusão Permanente" href="#" id="'.$values->id.'"><i class="fas fa-fire"></i></a>';
            }
            return $concatbutton;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    // RELATORIOS
    public function relatorio()
    {
        if(Gate::denies('cliente-relatorio')){
            abort(403,"Não autorizado!");
        }
        
        return view('cliente.relatorio.relatorio');
    }

    // GERA RELATORIO
    public function relatoriostore(Request $request)
    {
        if(Gate::denies('cliente-relatorio')){
            abort(403,"Não autorizado!");
        }
        
        $datetime = date('YmdHis'); 
        $data = date('d/m/Y H:i');

        set_time_limit(300);

        // GERAR PDF
        if ($request->exportacao == 2) {
            if ($request->status == 1) {
                $title = "RELATÓRIO DE CLIENTES ATIVOS";
                $clientes = Cliente::get();
                $count = count($clientes);
            } elseif ($request->status == 2){
                $title = "RELATÓRIO DE CLIENTES INATIVOS";
                $clientes = Cliente::onlyTrashed()->get();
                $count = count($clientes);
            } else {
                $title = "RELATÓRIO DE TODOS OS CLIENTES";
                $clientes = Cliente::withTrashed()->get();
                $count = count($clientes);
            }

            $pdf = PDF::loadview('cliente.relatorio.cliente', compact('clientes', 'title', 'data', 'count'));
            return $pdf->download($datetime.'.pdf');
        }

        // GERAR CSV
        $status = $request->status;
        if ($request->exportacao == 1) {
            return Excel::download(new ClienteExport($status), $datetime.'.csv');
        }
    }
}