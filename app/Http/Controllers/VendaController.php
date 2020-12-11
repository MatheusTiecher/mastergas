<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VendaItemRequest;
use App\Http\Requests\VendaProximoRequest;
use App\Http\Requests\VendaFinalRequest;
use App\Http\Requests\EntregaRequest;
use App\Http\Requests\TrocarDevolverRequest;
use App\Http\Requests\DevolverCargaRequest;
use App\Http\Requests\RequestDesconto;

use App\Models\Venda;
use App\Models\Cliente;
use App\Models\Caixa;
use App\Models\Lancamento;
use App\Models\Endereco;
use App\Models\Entrega;
use App\Models\OcorrenciaEntrega;
use App\Models\VendaItem;
use App\Models\Produto;
use App\Models\Unidade;
use App\Models\Estoque;
use App\Models\FormaPagamento;
use App\Models\Carga;
use App\Models\CargaItem;
use App\User;

use Auth;
use DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Gate;

use PDF;
use Excel;
use App\Exports\VendaExport;

// REGRAS 
// VENDA STATUS - 
//     -andamento   - 0
//     -agendado    - 1
//     -finalizado  - 2
//     -estornado   - 3
//     -processando - 4

// VENDA ITEM STATUS -
//     -vendido     - 0
//     -devolvido   - 1
//     -trocado     - 2
//     -estornado   - 3
//     -devolvido p/ carga  - 4

// STATUS DE ENTREGA/OCORRENCIA ENTREGA
//      -cancelado  - 0
//      -agendado   - 1
//      -entregue   - 2

class VendaController extends Controller
{
    private $date;
    private $statusvenda;
    private $statusvendaitem;
    private $statusentrega; 

    public function __construct(){

        $this->middleware('auth');

        $this->statusvenda = [
            'andamento',
            'agendado',
            'finalizado',
            'estornado',
            'processando'
        ];

        $this->statusvendaitem = [
            'vendido',
            'devolvido',
            'trocado',
            'estornado',
            'devolvido carga'
        ];

        $this->statusentrega = [
            'cancelado',
            'agendado',
            'entregue'
        ];

        $this->date = date('Y-m-d');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('venda-todas')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'data' => route('venda.data'),
        );

        return view('venda.index', compact("page"));
    }

    // TELA INDEX DE VENDAS AGENDADAS ENTREGA - indexagendado
    public function indexagendado()
    {
        if(Gate::denies('venda-agendadas')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'data' => route('venda.dataagendado'),
        );

        return view('venda.indexagendado', compact("page"));
    }

    // TELA INDEX DE VENDAS EM ANDAMENTO - indexandamento
    public function indexandamento()
    {
        if(Gate::denies('venda-andamentos')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'data' => route('venda.dataandamento'),
        );

        return view('venda.indexandamento', compact("page"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::denies('venda-create')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA SE DADOS DO COMPONENTES DE SELECT AINDA EXISTEM 
        $cliente = Cliente::find($request->cliente_id);
        if ($cliente == null) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return back()->with('notify', $exnotify);
        }

        $request->request->add(['user_id' => Auth::user()->id]);
        $request->request->add(['frete' => 0.00]);
        $request->request->add(['desconto' => 0.00]);
        $request->request->add(['status' => 0]);

        $venda = Venda::create($request->all());

        return redirect()->route('venda.vendaitem', $venda);
    }
    
    public function edit(Venda $venda)
    {
        abort(404,"Not Found");
    }

    public function show(Venda $venda)
    {
        abort(404,"Not Found");
    }
    
    // TELA VENDA DE ITEM, COM TOTAL DOS ITENS ADICIONADOS
    public function vendaitem(Venda $venda)
    {
        if(Gate::denies('venda-create')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA EM ANDAMENTO
        if ($venda->status != 0) {
            $exnotify[] = notifyInfo('Venda não está em andamento! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        $totalitem = 0.00;
        foreach ($venda->vendaitem as $key => $item) 
        {
            $totalitem += ($item->valorvenda*$item->quantidade);
        }

        return view('venda.vendaitem', compact('venda', 'totalitem'));
    }

    // ADICIONA ITEM / BAIXA NO ESTOQUE
    public function vendaitemstore(VendaItemRequest $request, Venda $venda)
    {
        if(Gate::denies('venda-create')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA EM ANDAMENTO
        if ($venda->status != 0) {
            $exnotify[] = notifyWarning('Venda não está em andamento! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        // VERIFICA SE DADOS DO COMPONENTES DE SELECT AINDA EXISTEM 
        $produto = Produto::find($request->produto_id);
        if ($produto == null) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return back()->with('notify', $exnotify);
        }

        // VERIFICA SE A QUANTIDADE É MAIOR QUE 0,00
        if (str_replace (',', '.', str_replace ('.', '', $request->quantidade)) <= 0.00) {
            $exnotify[] = notifyWarning('Quantidade informada não é válida!');
            return back()->with('notify', $exnotify);
        }
        if (str_replace (',', '.', str_replace ('.', '', $request->valorvenda)) < 0.00) {
            $exnotify[] = notifyWarning('Valor de venda informado não é válido!');
            return back()->with('notify', $exnotify);
        }

        $produto = Produto::withTrashed()->find($request->produto_id);
        $unidade = Unidade::withTrashed()->find($produto->unidade_id);

        // VERIFICA SE A UNIDADE É INTEIRA
        if ($unidade->inteiro == 1) {
            $double = str_replace (',', '.', str_replace ('.', '', $request->quantidade));
            $quantidade = floatval($double);

            $inteiro = intval($double);
            $inteiro = floatval($inteiro);

            // VERIFICA SE A QUANTIDADE MINIMA INFORMADA É INTEIRA
            if ($quantidade != $inteiro) {
                $exnotify[] = notifyWarning('Quantidade informada não é válida!');
                return back()->with('notify', $exnotify);
            }
        }

        $estoque = Estoque::where('produto_id', '=', $request->produto_id)->where('restante', '>', '0')->orderBy('id')->get();

        // contador para saber a sequencia do itemvenda
        $vendaitem = $venda->vendaitem;
        $sequencia = count($vendaitem);
        $sequencia ++;
        // dd($sequencia);

        $restante = 0;
        foreach ($estoque as $key => $e) {
            $restante += ($e->restante);
        }
        
        if ($restante >= str_replace (',', '.', str_replace ('.', '', $request->quantidade))) {
            foreach ($estoque as $key => $e) {
                if ($e->restante >= str_replace (',', '.', str_replace ('.', '', $request->quantidade))) {
                    $restante = ($e->restante - str_replace (',', '.', str_replace ('.', '', $request->quantidade)));
                    $restante = number_format($restante, 2, ',', '.');
                    $e->restante = ($restante);
                    $e->save();

                    $request->request->add(['venda_id' => $venda->id]);
                    $request->request->add(['estoque_id' => $e->id]);
                    $request->request->add(['sequencia' => $sequencia]);
                    $request->request->add(['descricao' => 'Venda normal']);
                    $request->request->add(['status' => 0]);

                    VendaItem::create($request->all());

                    $exnotify[] = notifySuccess('Produto '.$e->produto->descricao.',  adicionado à venda!');
                    return redirect()->route('venda.vendaitem', $venda)->with('notify', $exnotify);
                } else {

                    $quantirestante = (str_replace (',', '.', str_replace ('.', '', $request->quantidade)) - $e->restante);
                    $request->request->add(['quantidade' => number_format($e->restante, 2, ',', '.')]);
                    
                    $e->restante = "0,00";
                    $e->save();

                    $request->request->add(['venda_id' => $venda->id]);
                    $request->request->add(['estoque_id' => $e->id]);
                    $request->request->add(['sequencia' => $sequencia]);
                    $request->request->add(['descricao' => 'Venda normal']);
                    $request->request->add(['status' => 0]);
                    VendaItem::create($request->all());

                    $quantirestante = number_format($quantirestante, 2, ',', '.');
                    $request->request->add(['quantidade' => $quantirestante]);
                }
            }
        } else {
            $exnotify[] = notifyWarning('Quantidade informada maior que estoque!');
            return redirect()->route('venda.vendaitem', $venda)->with('notify', $exnotify);
        }
    }

    // REMOVE ITEM DA VENDA
    public function destroyitem(VendaItem $vendaitem)
    {
        if(Gate::denies('venda-create')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA EM ANDAMENTO
        if ($vendaitem->venda->status != 0) {
            $exnotify[] = notifyWarning('Venda não está em andamento! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        $restante = floatval($vendaitem->quantidade + $vendaitem->estoque->restante);
        $restante = number_format($restante, 2, ',', '.');
        $vendaitem->estoque->restante = $restante;
        $vendaitem->estoque->save();
        $vendaitem->delete();

        $exnotify[] = notifySuccess('Produto '.$vendaitem->produto->descricao.',  removido da venda!');
        return redirect()->route('venda.vendaitem', $vendaitem->venda)->with('notify', $exnotify);
    }

    // UPDATE/SAVE EM VENDA - CREATE DE ENTREGA
    public function atualizavenda(VendaProximoRequest $request, Venda $venda)
    {
        if(Gate::denies('venda-create')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA EM ANDAMENTO
        if ($venda->status != 0) {
            $exnotify[] = notifyWarning('Venda não está em andamento! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        // VERIFICA SE JA TEM ITEM ADICIONADO
        if (empty($venda->vendaitem->first())) {
            $exnotify[] = notifyWarning('Favor adicionar um item primeiro!');
            return redirect()->route('venda.vendaitem', $venda)->with('notify', $exnotify);
        }

        $request->request->add(['frete' => 0.00]);
        $request->request->add(['desconto' => 0.00]);
        $request->request->add(['status' => 0]);

        $venda->update($request->all());

        // REMOVER TODAS AS ENTREGAS E OCORRENCIAS DE ENTREGAS DA VENDA
        if (!empty($venda->entrega)) {
            $venda->entrega->delete();
        }

        $exnotify[] = notifySuccess('Venda salva com sucesso!');
        return redirect()->route('venda.createentrega', $venda)->with('notify', $exnotify);
    }

    // UPDATE/SAVE EM VENDA - CREATE DE ENTREGA
    public function createentrega(Venda $venda)
    {
        if(Gate::denies('venda-create')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA EM ANDAMENTO
        if ($venda->status != 0) {
            $exnotify[] = notifyInfo('Venda não está em andamento! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        return view('venda.createentrega', compact("venda"));
    }

    // STORE DE ENTREGA AGENDADA 
    public function agendarentrega(EntregaRequest $request, Venda $venda)
    {
        if(Gate::denies('venda-agendar')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA EM ANDAMENTO
        if ($venda->status != 0) {
            $exnotify[] = notifyWarning('Venda não está em andamento! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        // VERIFICA SE JA TEM ITEM ADICIONADO OU ENTREGA SELECIONADA
        if (empty($venda->vendaitem->first())) {
            $exnotify[] = notifyWarning('Favor adicionar um item primeiro!');
            return redirect()->route('venda.vendaitem', $venda)->with('notify', $exnotify);
        }

        // VERIFICA SE TEM ALGUM REGISTRO DE ENTREGA E REMOVE
        if (!empty($venda->entrega)) {
            $venda->entrega->delete();
        }

        $request->request->add(['status' => 1]);
        $request->request->add(['forma_entrega' => 2]);
        $entrega = $venda->entrega()->create($request->all());
        $entrega->ocorrenciaentrega()->create($request->all());

        $exnotify[] = notifySuccess('Venda salva com sucesso!');
        return redirect()->route('venda.createfinal', $venda)->with('notify', $exnotify);
    }

    // STORE DE ENTREGA RETIRADA NA LOJA 
    public function retirarentrega(Venda $venda)
    {
        if(Gate::denies('venda-retirar')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA EM ANDAMENTO
        if ($venda->status != 0) {
            $exnotify[] = notifyWarning('Venda não está em andamento! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        // VERIFICA SE JA TEM ITEM ADICIONADO
        if (empty($venda->vendaitem->first())) {
            $exnotify[] = notifyWarning('Favor adicionar um item primeiro!');
            return redirect()->route('venda.vendaitem', $venda)->with('notify', $exnotify);
        }

        // VERIFICA SE TEM ALGUM REGISTRO DE ENTREGA E REMOVE
        if (!empty($venda->entrega)) {
            $venda->entrega->delete();
        }

        $entrega = $venda->entrega()->create([
            'status' => 2,
            'forma_entrega' => 1,
            'dataentrega' => date("Y-m-d H:i:s"),
        ]);

        $exnotify[] = notifySuccess('Venda salva com sucesso!');
        return redirect()->route('venda.createfinal', $venda)->with('notify', $exnotify);
    }

    // CREATE FINAL DA VENDA
    public function createfinal(Venda $venda)
    {
        if(Gate::denies('venda-finalizar')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA EM ANDAMENTO
        if ($venda->status != 0) {
            $exnotify[] = notifyInfo('Venda não está em andamento! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        // VERIFICA SE JA TEM ITEM ADICIONADO OU ENTREGA SELECIONADA
        if (empty($venda->vendaitem->first())) {
            $exnotify[] = notifyWarning('Favor adicionar um item primeiro!');
            return redirect()->route('venda.vendaitem', $venda)->with('notify', $exnotify);
        }
        if (empty($venda->entrega->first())) {
            $exnotify[] = notifyWarning('Favor selecionar a forma de entrega primeiro!');
            return redirect()->route('venda.createentrega', $venda)->with('notify', $exnotify);
        }

        $total = 0.00;
        foreach ($venda->vendaitem as $key => $item) 
        {
            $total += ($item->valorvenda*$item->quantidade);
        }

        $formapagamentos = FormaPagamento::all();

        return view('venda.final', compact("venda", "formapagamentos", "total"));
    }

    // STORE FINAL DA VENDA - storefinal
    public function storefinal(VendaFinalRequest $request, Venda $venda)
    {
        if(Gate::denies('venda-finalizar')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA EM ANDAMENTO
        if ($venda->status != 0) {
            $exnotify[] = notifyWarning('Venda não está em andamento! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }
        
        // VERIFICA SE JA TEM ITEM ADICIONADO OU ENTREGA SELECIONADA
        if (empty($venda->vendaitem->first())) {
            $exnotify[] = notifyWarning('Favor adicionar um item primeiro!');
            return redirect()->route('venda.vendaitem', $venda)->with('notify', $exnotify);
        }
        if (empty($venda->entrega->first())) {
            $exnotify[] = notifyWarning('Favor selecionar a forma de entrega primeiro!');
            return redirect()->route('venda.createentrega', $venda)->with('notify', $exnotify);
        }

        // VERIFICA SE DADOS DO COMPONENTES DE SELECT AINDA EXISTEM 
        $formapagamento = FormaPagamento::find($request->forma_pagamento_id);
        if ($formapagamento == null) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return back()->with('notify', $exnotify);
        }

        // VERIFICA SE O DESCONTO E FRETE É MAIOR IGUAL 0,00
        if (str_replace (',', '.', str_replace ('.', '', $request->desconto)) < 0.00) {
            $exnotify[] = notifyWarning('Desconto informado não é válido!');
            return back()->with('notify', $exnotify);
        }
        if (str_replace (',', '.', str_replace ('.', '', $request->frete)) < 0.00) {
            $exnotify[] = notifyWarning('Frete informado não é válido!');
            return back()->with('notify', $exnotify);
        }

        //VERIFICAR SE VALOR DA VENDA É MAIOR IGUAL 0
        $valor = 0.00;
        foreach ($venda->vendaitem as $value) {
            $valor += $value->valorvenda * $value->quantidade;
        }
        $valor += (str_replace (',', '.', str_replace ('.', '', $request->frete))) - (str_replace (',', '.', str_replace ('.', '', $request->desconto)));

        if ($valor < 0.0) {
            $exnotify[] = notifyWarning('Venda não pode ter valor total menor que zero!');
            return redirect()->back()->with('notify', $exnotify);
        }

        if ($venda->entrega->forma_entrega == 1) {
            $request->request->add(['status' => 2]);
            $request->request->add(['finalizavenda' => date("Y-m-d H:i:s")]);
            $exnotify[] = notifySuccess('Venda finalizada com sucesso!');
            
            // ADICIONA NO CAIXA
            if ($request->forma_pagamento_id == 1 || $request->forma_pagamento_id == 2) {
                $lancamento = $caixa->lancamento()->create([
                    'tipo_lancamento' => 1,
                    'user_id' => Auth::user()->id,
                    'venda_id' => $venda->id,
                    'descricao' => 'Venda '.FormaPagamento::find($request->forma_pagamento_id)->descricao,
                    'valor' => number_format($valor, 2, ',', '.'),
                ]);
                $caixa->entrada += $valor;
                $caixa->update();
                $exnotify[] = notifySuccess('Valor adicionado ao caixa sucesso!');
            }
        } else{
            $request->request->add(['status' => 1]); 
            $exnotify[] = notifySuccess('Entrega agendada com sucesso!');
        }

        $venda->update($request->all());

        return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
    }

    // DETALHAR VENDA - detalhar
    public function detalhar(Venda $venda)
    {
        if(Gate::denies('venda-detalhar')){
            abort(403,"Não autorizado!");
        }

        $total = 0.00;
        foreach ($venda->vendaitem as $key => $item) 
        {
            if ($item->status != 4) {
                $total += ($item->valorvenda*$item->quantidade);
            }
        }

        $ocorrencia = null;
        if (isset($venda->entrega->ocorrenciaentrega)) { 
            $ocorrencia = $venda->entrega->ocorrenciaentrega()->orderby('id', 'desc')->first();
        }

        $status = $this->statusvenda;
        $statusvendaitem = $this->statusvendaitem;

        return view('venda.detalhar', compact("venda", "ocorrencia", "total", "status", "statusvendaitem"));
    }

    // ESTORNAR VENDA
    public function estornar(Venda $venda)
    {   
        if(Gate::denies('venda-estornar')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA EM ANDAMENTO
        if (!($venda->status == 0 || $venda->status == 1)) {
            $exnotify[] = notifyWarning('Venda não está em andamento! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        if (isset($venda->entrega->ocorrenciaentrega)) {
            if(!empty($venda->entrega->ocorrenciaentrega->first())){
                $venda->entrega->update(['status' => 0]);
                $ocorrenciaentrega = $venda->entrega->ocorrenciaentrega()->where('status', '=', 1)->orderby('id', 'desc')->first();
                $ocorrenciaentrega->update(['status' => 0]);
            }
        }

        foreach ($venda->vendaitem as $key => $vendaitem) 
        {
            $restante = floatval($vendaitem->quantidade + $vendaitem->estoque->restante);
            $restante = number_format($restante, 2, ',', '.');
            $vendaitem->estoque->restante = $restante;
            $vendaitem->status = 3;
            $vendaitem->update(['descricao' => 'Venda estornada']);
            $vendaitem->estoque->update();
        }

        $venda->update(['status' => 3]);

        $exnotify[] = notifySuccess('Venda estornada com sucesso!');
        return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
    }

    // DEVOLVER ITEM VENDA - devolver
    public function devolver(Venda $venda)
    {
        if(Gate::denies('venda-devolver')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA FINALIZADA
        if ($venda->status != 2) {
            $exnotify[] = notifyInfo('Venda não está finalizada! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        $itens = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(vi.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN venda_items vi ON vi.produto_id = p.id WHERE vi.venda_id = ".$venda->id." and vi.status != '1' and vi.status != '4' GROUP BY p.id");

        return view('venda.devolver', compact("venda", "itens"));
    }

    // DEVOLVER ITEM VENDA - storedevolver
    public function storedevolver(TrocarDevolverRequest $request, Venda $venda)
    {
        if(Gate::denies('venda-devolver')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA FINALIZADA
        if ($venda->status != 2) {
            $exnotify[] = notifyWarning('Venda não está finalizada! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        // VERIFICA SE DADO QUE VEM DA TELA AINDA EXISTE DO ARRAY ITENS
        $itens = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(vi.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN venda_items vi ON vi.produto_id = p.id WHERE vi.venda_id = ".$venda->id." and vi.status != '1' and vi.status != '4' GROUP BY p.id");

        $contem = false;
        foreach ($itens as $key => $value) {
            if ($value->id == intval($request->idproduto)) {
                $contem = true;
            }
        }
        if ($contem == false) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return redirect()->route('venda.devolver', $venda)->with('notify', $exnotify);
        }

        $item = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(vi.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN venda_items vi ON vi.produto_id = p.id WHERE vi.venda_id = ".$venda->id." and vi.status != '1' and vi.status != '4' and vi.produto_id = ".$request->idproduto." GROUP BY p.id");

        $produto = Produto::withTrashed()->find($request->idproduto);
        $unidade = Unidade::withTrashed()->find($produto->unidade_id);

        // VERIFICA SE A QUANTIDADE É MAIOR QUE 0,00
        if (str_replace (',', '.', str_replace ('.', '', $request->quantidade)) <= 0.00) {
            $exnotify[] = notifyWarning('Quantidade informada não é válida!');
            return back()->with('notify', $exnotify);
        }

        // VERIFICA SE A UNIDADE É INTEIRA
        if ($unidade->inteiro == 1) {
            $double = str_replace (',', '.', str_replace ('.', '', $request->quantidade));
            $quantidade = floatval($double);

            $inteiro = intval($double);
            $inteiro = floatval($inteiro);

            // VERIFICA SE A QUANTIDADE MINIMA INFORMADA É INTEIRA
            if ($quantidade != $inteiro) {
                $exnotify[] = notifyWarning('Quantidade informada não é válida!');
                return back()->with('notify', $exnotify);
            }
        }

        // contador para saber a sequencia do itemvenda
        $vendaitem = $venda->vendaitem;
        $sequencia = count($vendaitem);
        $sequencia ++;

        $itens = $venda->vendaitem()->where('produto_id', '=', $item[0]->id)->where('status', '!=', 1)->where('status', '!=', 4)->orderBy('quantidade', 'desc')->get();

        if ($item[0]->quantidade >= str_replace (',', '.', str_replace ('.', '', $request->quantidade))) {
            foreach ($itens as $key => $i) {
                if ($i->quantidade >= str_replace (',', '.', str_replace ('.', '', $request->quantidade))) {
                    $restante = ($i->quantidade - str_replace (',', '.', str_replace ('.', '', $request->quantidade)));
                    $estoque = ($i->estoque->restante + str_replace (',', '.', str_replace ('.', '', $request->quantidade)));
                    $estoque = number_format($estoque, 2, ',', '.');
                    $i->estoque->restante = $estoque;
                    $i->estoque->save();

                    if ($restante == 0.0) {
                        $i->update([
                            'descricao' => $request->descricao,
                            'status' => 1,
                        ]);
                    } else {
                        $restante = number_format($restante, 2, ',', '.');
                        $i->update([
                            'quantidade' => $restante,
                        ]);

                        VendaItem::create([
                            'sequencia' => $sequencia,
                            'quantidade' => $request->quantidade,
                            'status' => 1,
                            'valorvenda' => number_format($i->valorvenda, 2, ',', '.'),
                            'descricao' => $request->descricao,
                            'venda_id' => $venda->id,
                            'estoque_id' => $i->estoque->id,
                            'produto_id' => $i->produto->id,
                        ]);
                    }

                    $exnotify[] = notifySuccess('Produto '.$i->produto->descricao.',  devolvido com sucesso!');
                    return back()->with('notify', $exnotify);
                } else {
                    $quantirestante = (str_replace (',', '.', str_replace ('.', '', $request->quantidade)) - $i->quantidade);
                    $estoque = ($i->estoque->restante + $i->quantidade);
                    $estoque = number_format($estoque, 2, ',', '.');
                    $i->estoque->restante = $estoque;
                    $i->estoque->save();

                    $i->update([
                        'descricao' => $request->descricao,
                        'status' => 1,
                    ]);

                    $quantirestante = number_format($quantirestante, 2, ',', '.');
                    $request->request->add(['quantidade' => $quantirestante]);
                }
            }
        } else {
            $exnotify[] = notifyWarning('Quantidade informada maior que total!');
            return back()->with('notify', $exnotify);
        }
    }

    // DEVOLVER ITEM DA VENDA PARA CARGA - devolvercarga
    public function devolvercarga(Venda $venda)
    {
        if(Gate::denies('carga-venda-devolver')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        if ($venda->carga->status != 1 || $venda->status != 4) {
            $exnotify[] = notifyInfo('Venda não está processando, não pode ser dovolvido produtos para carga! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        $itens = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(vi.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN venda_items vi ON vi.produto_id = p.id WHERE vi.venda_id = ".$venda->id." and vi.status != '1'  and vi.status != '4' GROUP BY p.id");

        return view('venda.devolvercarga', compact("venda", "itens"));
    }

    // DEVOLVER ITEM DA VENDA PARA CARGA - storedevolvercarga
    public function storedevolvercarga(DevolverCargaRequest $request, Venda $venda)
    {
        if(Gate::denies('carga-venda-devolver')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        if ($venda->carga->status != 1 || $venda->status != 4) {
            $exnotify[] = notifyWarning('Venda não está processando, não pode ser dovolvido produtos para carga! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        // VERIFICA SE DADO QUE VEM DA TELA AINDA EXISTE DO ARRAY ITENS
        $itens = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(vi.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN venda_items vi ON vi.produto_id = p.id WHERE vi.venda_id = ".$venda->id." and vi.status != '1' and vi.status != '4' GROUP BY p.id");

        $contem = false;
        foreach ($itens as $key => $value) {
            if ($value->id == intval($request->idproduto)) {
                $contem = true;
            }
        }
        if ($contem == false) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return redirect()->route('venda.devolvercarga', $venda)->with('notify', $exnotify);
        }

        $request2 = $request->quantidade;

        $item = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(vi.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN venda_items vi ON vi.produto_id = p.id WHERE vi.venda_id = ".$venda->id." and vi.status != '1' and vi.status != '4' and vi.produto_id = ".$request->idproduto." GROUP BY p.id");

        $produto = Produto::withTrashed()->find($request->idproduto);
        $unidade = Unidade::withTrashed()->find($produto->unidade_id);

        // VERIFICA SE A QUANTIDADE É MAIOR QUE 0,00
        if (str_replace (',', '.', str_replace ('.', '', $request->quantidade)) <= 0.00) {
            $exnotify[] = notifyWarning('Quantidade informada não é válida!');
            return back()->with('notify', $exnotify);
        }

        // VERIFICA SE A UNIDADE É INTEIRA
        if ($unidade->inteiro == 1) {
            $double = str_replace (',', '.', str_replace ('.', '', $request->quantidade));
            $quantidade = floatval($double);

            $inteiro = intval($double);
            $inteiro = floatval($inteiro);

            // VERIFICA SE A QUANTIDADE MINIMA INFORMADA É INTEIRA
            if ($quantidade != $inteiro) {
                $exnotify[] = notifyWarning('Quantidade informada não é válida!');
                return back()->with('notify', $exnotify);
            }
        }

        // contador para saber a sequencia do itemvenda
        $vendaitem = $venda->vendaitem;
        $sequencia = count($vendaitem);
        $sequencia ++;

        $itens = $venda->vendaitem()->where('produto_id', '=', $item[0]->id)->where('status', '!=', 1)->where('status', '!=', 4)->orderBy('quantidade', 'desc')->get();

        if ($item[0]->quantidade >= str_replace (',', '.', str_replace ('.', '', $request->quantidade))) {
            foreach ($itens as $key => $i) {
                if (!(str_replace (',', '.', str_replace ('.', '', $request->quantidade)) == 0.00)) {
                    if ($i->quantidade >= str_replace (',', '.', str_replace ('.', '', $request->quantidade))) {

                        $restante = ($i->quantidade - str_replace (',', '.', str_replace ('.', '', $request->quantidade)));

                        if ($restante == 0.0) {
                            $i->update([
                                'descricao' => $request->descricao,
                                'status' => 4,
                            ]);
                        } else {
                            $restante = number_format($restante, 2, ',', '.');
                            $i->update([
                                'quantidade' => $restante,
                            ]);

                            VendaItem::create([
                                'sequencia' => $sequencia,
                                'quantidade' => $request->quantidade,
                                'status' => 4,
                                'valorvenda' => number_format($i->valorvenda, 2, ',', '.'),
                                'descricao' => $request->descricao,
                                'venda_id' => $venda->id,
                                'estoque_id' => $i->estoque->id,
                                'produto_id' => $i->produto->id,
                            ]);
                        }

                        $request->request->add(['quantidade' => '0,00']);
                    } else {
                        $quantirestante = (str_replace (',', '.', str_replace ('.', '', $request->quantidade)) - $i->quantidade);

                        $i->update([
                            'descricao' => $request->descricao,
                            'status' => 4,
                        ]);

                        $quantirestante = number_format($quantirestante, 2, ',', '.');
                        $request->request->add(['quantidade' => $quantirestante]);
                    }
                }
            }
        } else {
            $exnotify[] = notifyWarning('Quantidade informada maior que total!');
            return back()->with('notify', $exnotify);
        }

        // DEVOLVER ITENS PARA CARGA
        $produto = Produto::withTrashed()->find($request->idproduto);
        $itemcargas = $venda->carga->cargaitem()->where('produto_id', '=', $produto->id)->where('status', '=', 2)->orderBy('quantidade', 'desc')->get();

        // contador para saber a sequencia do itemvenda
        $cargaitem = $venda->carga->cargaitem;
        $sequencia = count($cargaitem);
        $sequencia ++;

        foreach ($itemcargas as $key => $itemcarga) {
            if (!(str_replace (',', '.', str_replace ('.', '', $request2)) == 0.00)) {
                if ($itemcarga->quantidade >= str_replace (',', '.', str_replace ('.', '', $request2))) {

                    $restante = ($itemcarga->quantidade - str_replace (',', '.', str_replace ('.', '', $request2)));

                    if ($restante == 0.0) {
                        $itemcarga->update([
                            'descricao' => 'Aguardando Venda',
                            'status' => 0,
                        ]);
                    } else {
                        $restante = number_format($restante, 2, ',', '.');
                        $itemcarga->update([
                            'quantidade' => $restante,
                        ]);

                        CargaItem::create([
                            'sequencia' => $sequencia,
                            'quantidade' => $request2,
                            'status' => 0,
                            'descricao' => 'Aguardando Venda',
                            'carga_id' => $venda->carga->id,
                            'estoque_id' => $itemcarga->estoque->id,
                            'produto_id' => $itemcarga->produto->id,
                        ]);
                    }
                    $request2 = '0,00';
                } else {
                    $quantirestante = (str_replace (',', '.', str_replace ('.', '', $request2)) - $itemcarga->quantidade);

                    $itemcarga->update([
                        'descricao' => 'Produto venda',
                        'status' => 0,
                    ]);

                    $quantirestante = number_format($quantirestante, 2, ',', '.');
                    $request2 = $quantirestante;
                }
            }
        }
        $venda->update([
            'desconto' => $request->desconto,
        ]);

        $exnotify[] = notifySuccess('Produto devolvido para carga com sucesso!');
        return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
    }

    // TROCAR ITEM VENDA - trocar
    public function trocar(Venda $venda)
    {
        if(Gate::denies('venda-trocar')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA FINALIZADA
        if ($venda->status != 2) {
            $exnotify[] = notifyInfo('Venda não está finalizada! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        $itens = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(vi.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN venda_items vi ON vi.produto_id = p.id WHERE vi.venda_id = ".$venda->id." and vi.status != '1' and vi.status != '4' GROUP BY p.id");

        return view('venda.trocar', compact("venda", "itens"));
    }

    // TROCAR ITEM VENDA - storetrocar
    public function storetrocar(TrocarDevolverRequest $request, Venda $venda)
    {
        if(Gate::denies('venda-trocar')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA FINALIZADA
        if ($venda->status != 2) {
            $exnotify[] = notifyWarning('Venda não está finalizada! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        // VERIFICA SE DADO QUE VEM DA TELA AINDA EXISTE DO ARRAY ITENS
        $itens = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(vi.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN venda_items vi ON vi.produto_id = p.id WHERE vi.venda_id = ".$venda->id." and vi.status != '1' and vi.status != '4' GROUP BY p.id");

        $contem = false;
        foreach ($itens as $key => $value) {
            if ($value->id == intval($request->idproduto)) {
                $contem = true;
            }
        }
        if ($contem == false) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return redirect()->route('venda.trocar', $venda)->with('notify', $exnotify);
        }

        $item = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(vi.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN venda_items vi ON vi.produto_id = p.id WHERE vi.venda_id = ".$venda->id." and vi.status != '1' and vi.status != '4' and vi.produto_id = ".$request->idproduto." GROUP BY p.id");

        $produto = Produto::withTrashed()->find($request->idproduto);
        $unidade = Unidade::withTrashed()->find($produto->unidade_id);

        // VERIFICA SE A QUANTIDADE É MAIOR QUE 0,00
        if (str_replace(',', '.', str_replace ('.', '', $request->quantidade)) <= 0.00) {
            $exnotify[] = notifyWarning('Quantidade informada não é válida!');
            return back()->with('notify', $exnotify);
        }

        // VERIFICA SE A UNIDADE É INTEIRA
        if ($unidade->inteiro == 1) {

            $double = str_replace (',', '.', str_replace ('.', '', $request->quantidade));
            $quantidade = floatval($double);

            $inteiro = intval($double);
            $inteiro = floatval($inteiro);

            // VERIFICA SE A QUANTIDADE MINIMA INFORMADA É INTEIRA
            if ($quantidade != $inteiro) {
                $exnotify[] = notifyWarning('Quantidade informada não é válida!');
                return back()->with('notify', $exnotify);
            }
        }

        // contador para saber a sequencia do itemvenda
        $vendaitem = $venda->vendaitem;
        $sequencia = count($vendaitem);
        $sequencia ++;

        $itens = $venda->vendaitem()->where('produto_id', '=', $item[0]->id)->where('status', '!=', 1)->where('status', '!=', 4)->orderBy('quantidade', 'desc')->get();

        if ($item[0]->quantidade >= str_replace (',', '.', str_replace ('.', '', $request->quantidade))) {
            foreach ($itens as $key => $i) {
                if ($i->quantidade >= str_replace (',', '.', str_replace ('.', '', $request->quantidade))) {

                    $restante = ($i->quantidade - str_replace (',', '.', str_replace ('.', '', $request->quantidade)));

                    if ($restante == 0.0) {
                        $i->update([
                            'descricao' => $request->descricao,
                            'status' => 2,
                        ]);
                    } else {
                        $restante = number_format($restante, 2, ',', '.');
                        $i->update([
                            'quantidade' => $restante,
                        ]);

                        VendaItem::create([
                            'sequencia' => $sequencia,
                            'quantidade' => $request->quantidade,
                            'status' => 2,
                            'valorvenda' => number_format($i->valorvenda, 2, ',', '.'),
                            'descricao' => $request->descricao,
                            'venda_id' => $venda->id,
                            'estoque_id' => $i->estoque->id,
                            'produto_id' => $i->produto->id,
                        ]);
                    }
                    $exnotify[] = notifySuccess('Produto '.$i->produto->descricao.',  trocado com sucesso!');
                    return back()->with('notify', $exnotify);
                } else {
                    $quantirestante = (str_replace (',', '.', str_replace ('.', '', $request->quantidade)) - $i->quantidade);

                    $i->update([
                        'descricao' => $request->descricao,
                        'status' => 2,
                    ]);

                    $quantirestante = number_format($quantirestante, 2, ',', '.');
                    $request->request->add(['quantidade' => $quantirestante]);
                }
            }
        } else {
            $exnotify[] = notifyWarning('Quantidade informada maior que total!');
            return back()->with('notify', $exnotify);
        }
    }

    // ATIALIZA DESCONTO VENDA CARGA
    public function updatedesconto(RequestDesconto $request, Venda $venda)
    {   
        if(Gate::denies('carga-create')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        if ($venda->carga->status != 1 || $venda->status != 4) {
            $exnotify[] = notifyWarning('Venda não está processando, não pode ser dovolvido produtos para carga! <br> Venda status: '.$this->statusvenda[$venda->status]);
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        if (str_replace(',', '.', str_replace ('.', '', $request->desconto)) < 0.0) {
            $exnotify[] = notifyWarning('Desconto não pode ser um valor negativo!');
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        $venda->desconto = $request->desconto;
        $venda->update();

        $exnotify[] = notifySuccess('Desconto atualizado!');
        return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
    }

    // PDF RECIBO VENDA FINALIZADA COM RETIRADA NA LOJA - vendapdf
    public function vendapdf(Venda $venda)
    {
        if ($venda->carga != null) {
            $exnotify[] = notifyInfo('Recibo não pode ser emitido!');
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }        
        if ($venda->status != 2 && $venda->status != 1) {
            $exnotify[] = notifyInfo('Recibo não pode ser emitido!');
            return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
        }

        $datetime = date('YmdHis'); 

        $itens = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(vi.quantidade) AS quantidade, u.sigla, SUM(vi.quantidade*vi.valorvenda) AS valortotal from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN venda_items vi ON vi.produto_id = p.id WHERE vi.venda_id = ".$venda->id." and vi.status != '4' GROUP BY p.id");

        $total = 0.00;
        foreach ($itens as $key => $item) {
            $total += $item->valortotal;
        }

        // return view('venda.recibo.venda_retirada', compact('venda', 'itens', 'total'));

        if ($venda->entrega->forma_entrega == 2) {
            $ocorrencia = null;
            if (isset($venda->entrega->ocorrenciaentrega)) { 
                $ocorrencia = $venda->entrega->ocorrenciaentrega()->orderby('id', 'desc')->first();
            }
            $pdf = PDF::loadview('venda.recibo.venda_entrega', compact('venda', 'ocorrencia', 'itens', 'total'));
            return $pdf->stream($datetime.'.pdf');
        }

        $pdf = PDF::loadview('venda.recibo.venda_retirada', compact('venda', 'itens', 'total'));
        return $pdf->stream($datetime.'.pdf');
    }

    // SELECT2 CLIENTE - infocliente
    public function infocliente(Request $request)
    {
        $values = Cliente::select('id as id', 'nomerazao as text')->where('nomerazao','like', '%'.$request->q.'%')->limit(20)->get();
        return $values;
    }

    //SELECT2 DE PRODUTO - infoproduto
    public function infoproduto(Request $request)
    {
        $values = Produto::select('id as id', 'descricao as text')->where('descricao','like', '%'.$request->q.'%')->limit(20)->get();
        return $values;
    }

    //SELECT2 DE ENDERECO - infoendereco
    public function infoendereco(Request $request, Venda $venda)
    {
        $values = DB::select("SELECT ende.id as id, CONCAT('Rua: ', ende.rua, ' / Bairro: ', ende.bairro, ' / N° ', ende.numero, ' / ', cid.nome, ' - ', est.sigla) as text FROM enderecos AS ende left join cidades AS cid ON ende.cidade_id = cid.id left join estados AS est on cid.estado_id = est.id where ende.rua like '%{$request->q}%' AND ende.cliente_id = ".$venda->cliente_id." ");
        return $values;
    }

    //SELECT2 DE USAURIO ENTREGADOR - infousuario
    public function infousuario(Request $request)
    {
        $values = User::select('id as id', 'name as text')->where('name','like', '%'.$request->q.'%')->where('entregador', '=', 1)->limit(20)->get();
        return $values;
    }

    //Ajax JS para auto preencher o valor de venda para o produto
    public function proautocomplete(Request $request)
    {
        $produto = Produto::find($request->id);

        $custo = 0.00;
        $restante = 0;
        foreach ($produto->estoque as $key => $e) {
            $restante += $e->restante;
            $custo += $e->valorcusto*$e->restante;
        }

        if ($restante > 0) {
            $customedio = $custo/$restante; 
            $data = array(
                'valorvenda' => number_format($produto->valorvenda, 2, ',', '.'),
                'customedio' => 'R$ '.number_format($customedio, 2, ',', '.'),
                'quantidadestoque' => number_format($restante, 2, ',', '.').' '.$produto->unidade->sigla,
            );
            return $data;
        }

        $data = array(
            'valorvenda' => number_format($produto->valorvenda, 2, ',', '.'),
            'customedio' => '0,00',
            'quantidadestoque' => $restante,
        );

        return $data;
    }

    // DATA PARA O DATATABLES INDEX
    public function data()
    {
        $values = Venda::all();

        return Datatables::of($values)
        ->addColumn('cliente', function($values){
            return $values->cliente->nomerazao;
        })
        ->addColumn('valor', function($values){
            $valor = 0.00;
            foreach ($values->vendaitem as $value) {
                if ($value->status != 4) {
                    $valor += $value->valorvenda * $value->quantidade;
                }
            }
            $valor += ($values->frete - $values->desconto);
            return 'R$ '.number_format($valor, 2, ',', '.');
        })
        ->addColumn('datavenda', function($values){
            // return date('d/m/Y : H:i', strtotime($values->created_at));
            return $values->created_at;
        })
        ->addColumn('status', function($values){
            return strtoupper($this->statusvenda[$values->status]);
        })
        ->addColumn('action', function($values){
            $concatbutton = '';
            if(!Gate::denies('venda-detalhar')){
                $concatbutton .= '
                <a  class="btn btn-secondary btn-sm mt-1" data-toggle="tooltip" title="Detalhar venda" href="'.route('venda.detalhar',$values).'"><i class="fas fa-info-circle"></i> Detalhar Venda</a>';
            }
            return $concatbutton;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    // DATA PARA O DATATABLES INDEX ANDAMENTO
    public function dataandamento()
    {
        $values = Venda::where('status', 0)->get();

        return Datatables::of($values)
        ->addColumn('cliente', function($values){
            return $values->cliente->nomerazao;
        })
        ->addColumn('valor', function($values){
            $valor = 0.00;
            foreach ($values->vendaitem as $value) {
                $valor += $value->valorvenda * $value->quantidade;
            }
            $valor += ($values->frete - $values->desconto);
            return 'R$ '.number_format($valor, 2, ',', '.');
        })
        ->addColumn('datavenda', function($values){
            // return date('d/m/Y : H:i', strtotime($values->created_at));
            return $values->created_at;
        })
        ->addColumn('action', function($values){
            $concatbutton = '';
            if(!Gate::denies('venda-detalhar')){
                $concatbutton .= '
                <a  class="btn btn-secondary btn-sm mt-1" data-toggle="tooltip" title="Detalhar venda" href="'.route('venda.detalhar',$values).'"><i class="fas fa-info-circle"></i> Detalhar Venda</a>';
            }
            if(!Gate::denies('venda-create')){
                $concatbutton .= '
                <a  class="btn btn-secondary btn-sm mt-1" data-toggle="tooltip" title="Continuar Venda" href="'.route('venda.vendaitem',$values).'"><i class="fas fa-shopping-cart"></i> Continuar Venda</a>';
            }
            return $concatbutton;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    // DATA PARA O DATATABLES INDEX AGENDADO
    public function dataagendado()
    {
        // VERIFICAR ESSE SQL, PODE SER OTIMIZADO
        $values = Venda::join('entregas', 'vendas.id', '=', 'entregas.venda_id')
        ->join('ocorrencia_entregas', 'entregas.id', '=', 'ocorrencia_entregas.entrega_id')
        ->where('ocorrencia_entregas.status', 1)->where('vendas.status', 1)->get();

        return Datatables::of($values) 
        ->addColumn('cliente', function($values){
            $venda = Venda::where('id', '=', $values->venda_id)->first();
            return $venda->cliente->nomerazao;
        })
        ->addColumn('valor', function($values){
            $venda = Venda::where('id', '=', $values->venda_id)->first();
            $valor = 0.00;
            foreach ($venda->vendaitem as $value) {
                $valor += $value->valorvenda * $value->quantidade;
            }
            $valor += ($venda->frete - $venda->desconto);
            return 'R$ '.number_format($valor, 2, ',', '.');
        })
        ->addColumn('cidade', function($values){
            $venda = Venda::where('id', '=', $values->venda_id)->first();
            $ocorrenciaentrega = $venda->entrega->ocorrenciaentrega()->where('status', '=', 1)->orderby('id', 'desc')->first();
            return $ocorrenciaentrega->endereco->cidade->nome;
        })
        ->addColumn('rua', function($values){
            $venda = Venda::where('id', '=', $values->venda_id)->first();
            $ocorrenciaentrega = $venda->entrega->ocorrenciaentrega()->where('status', '=', 1)->orderby('id', 'desc')->first();
            return $ocorrenciaentrega->endereco->rua.'/'.$ocorrenciaentrega->endereco->numero;
        })
        ->addColumn('dataagendada', function($values){
            // return date('d.m.Y H:m', strtotime($values->dataagendada));
            return $values->dataagendada;
        })
        ->addColumn('entregador', function($values){
            $venda = Venda::where('id', '=', $values->venda_id)->first();
            $ocorrenciaentrega = $venda->entrega->ocorrenciaentrega()->where('status', '=', 1)->orderby('id', 'desc')->first();
            return $ocorrenciaentrega->user->name;
        })
        ->addColumn('status', function($values){
            if ($values->dataagendada >= date("Y-m-d H:i:s")) {
                return '<button type="button" class="btn btn-sm btn-success btn-icon-only rounded-circle data-toggle="tooltip" title="Entrega não está em atraso">
                </button>';
            }
            else{
                return '<button type="button" class="btn btn-sm btn-warning btn-icon-only rounded-circle data-toggle="tooltip" title="Entrega em atraso">
                </button>';
            }
        })
        ->addColumn('action', function($values){
            $concatbutton = '';
            if(!Gate::denies('venda-detalhar')){
                $concatbutton .= '
                <a  class="btn btn-secondary btn-sm" data-toggle="tooltip" title="Detalhar venda" href="'.route('venda.detalhar',$values->venda_id).'"><i class="fas fa-info-circle"></i> Detalhar Venda</a>';
            }
            if(!Gate::denies('venda-entrega-gerenciar')){
                $concatbutton .= '
                <a  class="btn btn-secondary btn-sm" data-toggle="tooltip" title="Detalhar venda" href="'.route('entrega.gerenciar',$values->entrega_id).'"><i class="far fa-calendar-alt"></i> Gerenciar Entrega</a>';
            }
            return $concatbutton;
        })
        ->rawColumns(['action', 'status'])
        ->make(true);
    }

    // RELATORIOS
    public function relatorio()
    {
        $users = User::all();
        $formapagamentos = FormaPagamento::all();
        $entregadores = User::where('entregador', '=', 1)->get();
        return view('venda.relatorio.relatorio', compact("users", "formapagamentos", "entregadores"));
    }

    // GERA RELATORIO
    public function relatoriostore(Request $request)
    {
        set_time_limit(3000);

        $datetime = date('YmdHis'); 
        $data = date('d/m/Y H:i');

        $datainicio = $request->datainicio;
        $datainicio = (str_replace( '/', '-', $datainicio));
        $datainicio = date('Y-m-d', strtotime($datainicio));
        $datafim = $request->datafim;
        $datafim = (str_replace( '/', '-', $datafim));
        $datafim = date('Y-m-d', strtotime($datafim));

        $user = $request->user;
        
        // GERAR PDF
        if ($request->exportacao == 2) {
            if ($request->relatorio == 1) {
                $title = "RELATÓRIO DE VENDA EM ABERTO";
                if ($user == 0) {
                    $vendas = Venda::where('status', '=', 0)->get();
                    $count = count($vendas);
                    $users = User::withTrashed()->get();
                    $pdf = PDF::loadview('venda.relatorio.vendaaberto', compact('vendas', 'title', 'data', 'count', 'users'));
                } else {
                    $vendas = Venda::where('status', '=', 0)->where('user_id', '=', $user)->get();
                    $count = count($vendas);
                    $users = User::withTrashed()->where('id', '=', $user)->get();
                    $pdf = PDF::loadview('venda.relatorio.vendaaberto', compact('vendas', 'title', 'data', 'count', 'users'));
                }

                return $pdf->download($datetime.'.pdf');
            }
            if ($request->relatorio == 2) {
                $title = "RELATÓRIO ENTREGA EM ATRASO";
                if ($user == 0) {
                    $vendas = Venda::select('vendas.id as ven_id', 'ocorrencia_entregas.id as oco_id')
                    ->join('entregas', 'vendas.id', '=', 'entregas.venda_id')
                    ->join('ocorrencia_entregas', 'entregas.id', '=', 'ocorrencia_entregas.entrega_id')
                    ->where('ocorrencia_entregas.status', 1)
                    ->where('vendas.status', 1)
                    ->where('ocorrencia_entregas.dataagendada', '<=', date('Y-m-d H:i'))
                    ->orderBy('ocorrencia_entregas.dataagendada', 'asc')
                    ->get();
                    $count = count($vendas);
                    $users = User::withTrashed()->get();
                    $pdf = PDF::loadview('venda.relatorio.entrega', compact('vendas', 'title', 'data', 'count', 'users'));
                } else {
                    $vendas = Venda::select('vendas.id as ven_id', 'ocorrencia_entregas.id as oco_id')
                    ->join('entregas', 'vendas.id', '=', 'entregas.venda_id')
                    ->join('ocorrencia_entregas', 'entregas.id', '=', 'ocorrencia_entregas.entrega_id')
                    ->where('ocorrencia_entregas.status', 1)
                    ->where('vendas.status', 1)
                    ->where('ocorrencia_entregas.dataagendada', '<=', date('Y-m-d H:i'))
                    ->where('vendas.user_id', '=', $user)
                    ->orderBy('ocorrencia_entregas.dataagendada', 'asc')
                    ->get();
                    $count = count($vendas);
                    $users = User::withTrashed()->where('id', '=', $user)->get();
                    $pdf = PDF::loadview('venda.relatorio.entrega', compact('vendas', 'title', 'data', 'count', 'users'));
                }
                return $pdf->download($datetime.'.pdf');
            }
            if ($request->relatorio == 3) {
                $title = "RELATÓRIO ENTREGA AGENDADA";
                if ($user == 0) {
                    $vendas = Venda::select('vendas.id as ven_id', 'ocorrencia_entregas.id as oco_id')
                    ->join('entregas', 'vendas.id', '=', 'entregas.venda_id')
                    ->join('ocorrencia_entregas', 'entregas.id', '=', 'ocorrencia_entregas.entrega_id')
                    ->where('ocorrencia_entregas.status', 1)
                    ->where('vendas.status', 1)
                    ->orderBy('ocorrencia_entregas.dataagendada', 'asc')
                    ->get();
                    $count = count($vendas);
                    $users = User::withTrashed()->get();
                    $pdf = PDF::loadview('venda.relatorio.entrega', compact('vendas', 'title', 'data', 'count', 'users'));
                } else {
                    $vendas = Venda::select('vendas.id as ven_id', 'ocorrencia_entregas.id as oco_id')
                    ->join('entregas', 'vendas.id', '=', 'entregas.venda_id')
                    ->join('ocorrencia_entregas', 'entregas.id', '=', 'ocorrencia_entregas.entrega_id')
                    ->where('ocorrencia_entregas.status', 1)
                    ->where('vendas.status', 1)
                    ->where('vendas.user_id', '=', $user)
                    ->orderBy('ocorrencia_entregas.dataagendada', 'asc')
                    ->get();
                    $count = count($vendas);
                    $users = User::withTrashed()->where('id', '=', $user)->get();
                    $pdf = PDF::loadview('venda.relatorio.entrega', compact('vendas', 'title', 'data', 'count', 'users'));
                }
                return $pdf->download($datetime.'.pdf');
            }
            if ($request->relatorio == 4) {
                $exnotify[] = notifyDanger('Exportação inválida');
                return redirect()->back()->with('notify', $exnotify);
            }
        }

        // GERAR CSV
        $relatorio = $request->relatorio;
        $status = $request->status;
        $forma_pagamento = $request->forma_pagamento;
        $entregador = $request->entregador;
        $exportacao = $request->exportacao;
        $forma_entrega = $request->forma_entrega;
        // dd($request->request);
        if ($request->exportacao == 1) {
            return Excel::download(new VendaExport($datainicio, $datafim, $relatorio, $user, $status, $forma_pagamento, $entregador, $exportacao, $forma_entrega), $datetime.'.csv');
        }
    }
}