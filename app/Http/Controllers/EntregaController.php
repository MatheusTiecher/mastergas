<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\Cliente;
use App\Models\Caixa;
use App\Models\Endereco;
use App\Models\OcorrenciaEntrega;
use App\Models\VendaItem;
use App\Models\FormaPagamento;
use App\User;
use App\Http\Requests\EntregaRequest;
use App\Http\Requests\EnderecoRequest;
use App\Http\Requests\EntregaOcorrenciaRequest;
use Auth;
use DB;
use Illuminate\Support\Facades\Gate;


// STATUS DE ENTREGA/OCORRENCIA ENTREGA
//      -cancelado  - 0
//      -agendado   - 1
//      -entregue   - 2

class EntregaController extends Controller
{
    private $statusentrega;
    private $date;    

    public function __construct(){

        $this->middleware('auth');
        $this->date = date('Y-m-d');

        $this->statusentrega = [
            'cancelado',
            'agendado',
            'entregue'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404,"Not Found");
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(404,"Not Found");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Entrega  $entrega
     * @return \Illuminate\Http\Response
     */
    public function show(Entrega $entrega)
    {
        abort(404,"Not Found");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Entrega  $entrega
     * @return \Illuminate\Http\Response
     */
    public function edit(Carga $carga)
    {
        abort(404,"Not Found");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Entrega  $entrega
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entrega $entrega)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Entrega  $entrega
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entrega $entrega)
    {
        //
    }

    // GERENCIAR ENTREGA
    public function gerenciar(Entrega $entrega)
    {
        if(Gate::denies('venda-entrega-gerenciar')){
            abort(403,"Não autorizado!");
        }

        $ocorrenciaentrega = $entrega->ocorrenciaentrega()->orderby('id', 'desc')->get();

        $formapagamentos = FormaPagamento::all();

        $status = $this->statusentrega;

        return view('venda.entrega.gerenciar', compact("entrega", "ocorrenciaentrega", "formapagamentos", "status"));
    }

    // CREATE NEW OCORRENCIA - REAGENDAR
    public function createocorrencia(Entrega $entrega)
    {
        if(Gate::denies('venda-entrega-reagendar')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();

        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        $ocorrenciaentrega = $entrega->ocorrenciaentrega()->where('status', '=', 1)->orderby('id', 'desc')->first();

        if (empty($ocorrenciaentrega)) {
            $exnotify[] = notifyWarning('Entrega não pode ser reagendada! <br> Ocorrencia status: '.$this->statusentrega[$entrega->status]);
            return back()->with('notify', $exnotify);
        }

        if ($ocorrenciaentrega->status != 1) {
            $exnotify[] = notifyWarning('Entrega não pode ser reagendada! <br> Ocorrencia status: '.$this->statusentrega[$entrega->status]);
            return back()->with('notify', $exnotify);
        }

        return view('venda.entrega.create', compact("entrega"));
    }

    public function storeocorrencia(EntregaOcorrenciaRequest $request, Entrega $entrega)
    {
        if(Gate::denies('venda-entrega-reagendar')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        $ocorrenciaentrega = $entrega->ocorrenciaentrega()->where('status', '=', 1)->orderby('id', 'desc')->first();

        if (empty($ocorrenciaentrega)) {
            $exnotify[] = notifyWarning('Entrega não pode ser reagendada! <br> Ocorrencia status: '.$this->statusentrega[$entrega->status]);
            return back()->with('notify', $exnotify);
        }

        if ($ocorrenciaentrega->status != 1) {
            $exnotify[] = notifyWarning('Entrega não pode ser reagendada! <br> Ocorrencia status: '.$this->statusentrega[$entrega->status]);
            return back()->with('notify', $exnotify);
        }
        
        $ocorrenciaentrega->update([
            'status' => 0,
            'ocorrencia' => $request->ocorrencia,
        ]);

        $entrega->ocorrenciaentrega()->create([
            'status' => 1,
            'anotacao' => $request->anotacao,
            'endereco_id' => $request->endereco_id,
            'dataagendada' => $request->dataagendada,
            'user_id' => $request->user_id,
        ]);

        $exnotify[] = notifySuccess('Entrega reagendada com sucesso!');
        return redirect()->route('entrega.gerenciar', $entrega)->with('notify', $exnotify);
    }

    public function confirmaentrega(Request $request, Entrega $entrega)
    {
        if(Gate::denies('venda-entrega-confirmar')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }
        
        $ocorrenciaentrega = $entrega->ocorrenciaentrega()->where('status', '=', 1)->orderby('id', 'desc')->first();

        if (empty($ocorrenciaentrega)) {
            $exnotify[] = notifyWarning('Entrega não pode ser reagendada! <br> Ocorrencia status: '.$this->statusentrega[$entrega->status]);
            return back()->with('notify', $exnotify);
        }

        if ($ocorrenciaentrega->status != 1) {
            $exnotify[] = notifyWarning('Entrega não pode ser reagendada! <br> Ocorrencia status: '.$this->statusentrega[$entrega->status]);
            return back()->with('notify', $exnotify);
        }
        
        // VERIFICA SE DADOS DO COMPONENTES DE SELECT AINDA EXISTEM 
        $formapagamento = FormaPagamento::find($request->forma_pagamento_id);
        if ($formapagamento == null) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return back()->with('notify', $exnotify);
        }

        $ocorrenciaentrega->update([
            'status' => 2,
        ]);

        $entrega->update([
            'status' => 2,
            'dataentrega' => date("Y-m-d H:i:s"),
        ]);

        $entrega->venda->update([
            'status' => 2,
            'finalizavenda' => date("Y-m-d H:i:s"),
            'forma_pagamento_id' => $request->forma_pagamento_id,
        ]);

        $valor = 0.00;
        foreach ($entrega->venda->vendaitem as $value) {
            $valor += $value->valorvenda * $value->quantidade;
        }
        $valor += ($entrega->venda->frete - $entrega->venda->desconto);

        // ADICIONA NO CAIXA
        if ($request->forma_pagamento_id == 1 || $request->forma_pagamento_id == 2) {
            $lancamento = $caixa->lancamento()->create([
                'tipo_lancamento' => 1,
                'user_id' => Auth::user()->id,
                'venda_id' => $entrega->venda->id,
                'descricao' => 'Entrega realizada, pagamento '.FormaPagamento::find($request->forma_pagamento_id)->descricao,
                'valor' => number_format($valor, 2, ',', '.'),
            ]);
            $caixa->entrada += $valor;
            $caixa->update();
            $exnotify[] = notifySuccess("Entrega realizada, venda finalizada com suceso!");
            $exnotify[] = notifySuccess('Valor adicionado ao caixa sucesso!');
        } else{
            $exnotify[] = notifySuccess("Entrega realizada, venda finalizada com suceso!");
        }

        return redirect()->route('venda.detalhar', $entrega->venda)->with('notify', $exnotify);
    }


    // Para poder adicionar um novo endereço para o cliente na hora da venda - createEnderecoVenda
    public function createEnderecoVenda(Venda $venda)
    {
        if(Gate::denies('cliente-endereco-create')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'form' => route('entrega.storeEnderecoVenda', $venda),
            'cancelar' => route('venda.createentrega', $venda),
            'title' => $venda->cliente->nomerazao,
        );

        return view('venda.entrega.enderecocreate', compact("page"));
    }

    // STORE ENDERECO VENDA
    public function storeEnderecoVenda(EnderecoRequest $request, Venda $venda)
    {
        if(Gate::denies('cliente-endereco-create')){
            abort(403,"Não autorizado!");
        }

        $venda->cliente->endereco()->create($request->all());

        $exnotify[] = notifySuccess('Endereco adicionado com sucesso!');
        return redirect()->route('venda.createentrega', $venda)->with('notify', $exnotify);
    }

    // Para poder adicionar um novo endereço para o cliente na hora da ocorrencia entrega - createEndereco
    public function createEndereco(Entrega $entrega)
    {
        if(Gate::denies('cliente-endereco-create')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'form' => route('entrega.storeEndereco', $entrega),
            'cancelar' => route('entrega.gerenciar', $entrega),
            'title' => $entrega->venda->cliente->nomerazao,
        );

        return view('venda.entrega.enderecocreate', compact("page"));
    }

    // STORE ENDERECO OCORRENCIA ENTREGA
    public function storeEndereco(EnderecoRequest $request, Entrega $entrega)
    {
        if(Gate::denies('cliente-endereco-create')){
            abort(403,"Não autorizado!");
        }

        $entrega->venda->cliente->endereco()->create($request->all());

        $exnotify[] = notifySuccess('Endereco adicionado com sucesso!');
        return redirect()->route('entrega.gerenciar', $entrega)->with('notify', $exnotify);
    }
}