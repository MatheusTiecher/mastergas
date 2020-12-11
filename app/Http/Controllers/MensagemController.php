<?php

namespace App\Http\Controllers;

use App\Models\Mensagem;
use App\Models\Produto;
use App\Models\Cliente;

use App\Http\Requests\MensagemRequest;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use Twilio\Rest\Client;

use Yajra\Datatables\Datatables;
use DB;
use DateTime;
use Illuminate\Support\Facades\Gate;

/**********************



FOI CANCELADO POR NÃO ESTAR NOS REQUISITOS DO PROJETO E POR NÃO ESTÁR FINALIZADO



REATIVAR 
    MIGRATE MENSAGEM, 
    SEED DE PERMISSÃO E ARRAY NO CONTROLLER DE CARGO
    MODEL MENSAGEM 
    LAYOUT 
    ROTAS


**********************/

    class MensagemController extends Controller
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
        if(Gate::denies('mensagem-menu')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'data' => route('mensagem.data'),
            'show' => route('mensagem.showData'),
            'destroy' => route('mensagem.destroyTemp'),
            'forceDelete' => route('mensagem.forceDelete'),
            'restore' => route('mensagem.restore'),
        );

        return view('mensagem.index', compact("page"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('cliente-menu')){
            abort(403,"Não autorizado!");
        }

        return view('mensagem.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MensagemRequest $request)
    {
        if(Gate::denies('mensagem-create')){
            abort(403,"Não autorizado!");
        }

        if (isset($request->ativo)) 
        {
            $request->request->add(['ativo' => 1]);
        } else {
            $request->request->add(['ativo' => 0]);
        }

        $mensagem = Mensagem::create($request->all());

        $exnotify[] = notifySuccess('Mensagem '.$mensagem->nome.', foi adicionada com sucesso!');

        return redirect()->route('mensagem.index')->with('notify', $exnotify);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mensagem  $mensagem
     * @return \Illuminate\Http\Response
     */
    public function show(Mensagem $mensagem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mensagem  $mensagem
     * @return \Illuminate\Http\Response
     */
    public function edit(Mensagem $mensagem)
    {
        if(Gate::denies('mensagem-edit')){
            abort(403,"Não autorizado!");
        }

        if ($mensagem->deleted_at != null) {
            $exnotify[] = notifyInfo('Favor restaurar o registro para editar!');
            return redirect()->route('mensagem.index')->with('notify', $exnotify);
        }

        if ($mensagem->produto_id != null) {
            $data = array(
                'id' => $mensagem->produto_id,
                'text' => $mensagem->produto->descricao,
                'selected' => true,
            );
            $data = json_encode($data);
            $data = "[".$data."]";
        }else{
            $data = "[]";
        }

        return view('mensagem.edit', compact("mensagem", "data"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mensagem  $mensagem
     * @return \Illuminate\Http\Response
     */
    public function update(MensagemRequest $request, Mensagem $mensagem)
    {
        if(Gate::denies('mensagem-edit')){
            abort(403,"Não autorizado!");
        }

        if ($mensagem->deleted_at != null) {
            $exnotify[] = notifyInfo('Favor restaurar o registro para editar!');
            return redirect()->route('mensagem.index')->with('notify', $exnotify);
        }

        if (isset($request->ativo)) 
        {
            $request->request->add(['ativo' => 1]);
        } else {
            $request->request->add(['ativo' => 0]);
        } 
        
        $mensagem->update($request->all());

        $exnotify[] = notifySuccess('Mensagem '.$mensagem->nome.', foi editado com sucesso!');
        return redirect()->route('mensagem.index')->with('notify', $exnotify);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mensagem  $mensagem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mensagem $mensagem)
    {
        //
    }

    // SHOW DE UM REGISTRO DO DATATABLES
    public function showData(Request $request)
    {
        if(Gate::denies('mensagem-show')){
            abort(403,"Não autorizado!");
        }

        $values = Mensagem::withTrashed()->find($request->id);

        if (empty($values)) {
            $data = '<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="showModel">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Mensagem não encontrado</h5>
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
        <h5 class="modal-title" id="exampleModalLabel">Mensagem '.$values->nome.'</h5>
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
        <tr>
        <th>Nome</th>
        <td>'.$values->nome.'</td>
        </tr>
        <tr>
        <th>Rotina</th>
        <td>'.$values->rotina.'</td>
        </tr>
        <tr>
        <th>Horário</th>
        <td>'.$values->hora.'</td>
        </tr>
        <tr>
        <th>Produto</th>
        <td>'.$values->produto->descricao.'</td>
        </tr>
        <tr>
        <th>Telefone Celular</th>
        <td>'.$values->ativo.'</td>
        </tr>
        <tr>
        <th>Mensagem</th>
        <td style="white-space: pre-wrap;">'.$values->msg.'</td>
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
        if(Gate::denies('mensagem-delete')){
            return notifyAjax403();
        }

        $values = Mensagem::find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        $values->delete();

        return notifyDestroyTemp();
    }  

    // DESTROY PERMANENTEMENTE UM REGISTRO DO DATATABLES
    public function forceDelete(Request $request)
    {
        if(Gate::denies('mensagem-del-perm')){
            return notifyAjax403();
        }

        $values = Mensagem::withTrashed()->find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        // FAZER VALIDAÇÔES SE TEM DEPENDENCIAS E ADICIONAR MENSAGEM DE ERRO CASO TENHA
        // if ($values->venda->first() != null) {
        //     return response()->json([
        //         'status' => 'Info',
        //         'icon' => 'fas fa-bell',
        //         'title' => '<strong>Alerta:</strong>',
        //         'message' => 'Mensagem não pode ser exlcuido, contem pendências!',
        //         'type' => 'info'
        //     ]);
        // }

        $values->forceDelete();

        return notifyForceDelete();
    } 

    // RESTORA UM REGISTRO DO DATATABLES
    public function restore(Request $request)
    {
        if(Gate::denies('mensagem-restore')){
            return notifyAjax403();
        }

        $values = Mensagem::withTrashed()->find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        $values->restore();

        return notifyRestore(); 
    }

    // MENSAGEM DE TESTE
    public function sendWhatsAppMessage(){

        $sid    = "AC480cff92a6920e74abad41f380b4087e";
        $token  = "cf0c81ef578a80e630abb2318fa1d4e5";
        $twilio = new Client($sid, $token);

        $body = "Olá #NOME DO CLIENTE#, tudo bem?\nEm consulta as suas ultima compras, identificamos que seu gás está próximo do fim, estou passando aqui para te avisar. Sabemos que é muito chato estar preparando sua refeição e ser interrompido por esse incidente. Gostaríamos de te avisar que estamos atendendo todos os dias das 08:00 as 19:00 horas.\nCaso precise é só nos chamar. Um grande abraço! MASTERGAS";

        $message = $twilio->messages->create("whatsapp:+554699328044", [
            "from" => "whatsapp:+14155238886",
            "body" => $body
        ]);

        return ($message->sid);
    }

    // TESTE ESTIMATIVA DE VENDA
    public function teste(){

        $array = [];

        $clientes = Cliente::all();

        $mensagem = Mensagem::find(1);

        // dd($mensagem);

        foreach ($clientes as $key => $value) {

            $vendas = DB::select(" SELECT v.* FROM clientes as c JOIN vendas as v ON v.cliente_id = c.id JOIN venda_items AS vi on vi.venda_id = v.id JOIN produtos AS p ON p.id = vi.produto_id WHERE c.id = ".$value->id." AND p.id = 1 AND v.`status` = 2 AND c.id <> 1 AND p.deleted_at IS NULL AND c.deleted_at IS NULL AND c.mensagem = 1 GROUP BY v.id ORDER BY v.finalizavenda desc ");


            if ($value->id == 1) {

            } else {



                if (!isset($vendas[0])) {
                    return null;        
                }

                if (!isset($vendas[1])) {
                    return null;        
                }

                $dt = new DateTime($vendas[0]->finalizavenda);
                $dt2 = new DateTime($vendas[1]->finalizavenda);

                // dd($dt);

                $diff = $dt->diff($dt2, true); 

                // dd($diff->days);

                $dt = $vendas[0]->finalizavenda;

                // dd($dt);

                $dt = date('d/m/Y', strtotime('+'.$diff->days.' days', strtotime($dt)));

                $array[] = $dt;
                // dd($dt);
            }
        }
        $array[] = $mensagem;
        $array[] = ['status' => 1];
        dd($array);
    }

    //SELECT2 DE PRODUTO - infoproduto
    public function infoproduto(Request $request)
    {
        $values = Produto::select('id as id', 'descricao as text')->where('descricao','like', '%'.$request->q.'%')->limit(20)->get();

        return $values;
    }

    // DATA PARA O DATATABLES
    public function data()
    {
        $values = Mensagem::withTrashed()->get();

        return Datatables::of($values)
        ->addColumn('nome', function($values){
            return $values->nome;
        })
        ->addColumn('produto', function($values){
            return $values->produto->descricao;
        })
        ->addColumn('ativo', function($values){
            return $values->ativo;
        })
        ->addColumn('action', function($values){
            $concatbutton = '';
            if(!(Gate::denies('mensagem-show'))){
                $concatbutton .= '
                <a  class="btn btn-outline-dark btn-sm btnshow mt-1" data-toggle="tooltip" title="Mostrar" href="#" id="'.$values->id.'"><i class="fas fa-eye"></i></a>';
            }
            if(!(Gate::denies('mensagem-edit'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a  class="btn btn-info btn-sm edit mt-1" data-toggle="tooltip" title="Editar" href="'.route('mensagem.edit',$values).'"><i class="fas fa-pencil-alt"></i></a>';
                }
            }
            if(!(Gate::denies('mensagem-delete'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-danger btn-sm delete mt-1" data-toggle="tooltip" title="Excluir" href="#" id="'.$values->id.'"><i class="fas fa-trash-alt"></i></a>';
                }
            }
            if(!(Gate::denies('mensagem-restore'))){
                if (!$values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-outline-success btn-sm restore mt-1" data-toggle="tooltip" title="Restaurar" href="#" id="'.$values->id.'" ><i class="fas fa-recycle"></i></a>';
                }
            }
            if(!(Gate::denies('mensagem-del-perm'))){
                $concatbutton .= '
                <a class="btn btn-outline-danger btn-sm del-perm mt-1" data-toggle="tooltip" title="Exclusão Permanente" href="#" id="'.$values->id.'" ><i class="fas fa-fire"></i></a>';
            }
            return $concatbutton;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
