<?php

namespace App\Http\Controllers;

use App\Models\Carga;
use App\Models\CargaItem;
use App\Models\Caixa;
use App\Models\Lancamento;
use App\Models\Rota;
use App\Models\Produto;
use App\Models\Unidade;
use App\Models\Estoque;
use App\Models\Venda;
use App\Models\VendaItem;
use App\User;

use Illuminate\Http\Request;
use App\Http\Requests\CargaAtualizaRequest;
use App\Http\Requests\CargaItemRequest;
use App\Http\Requests\CargaVendaRequest;

use PDF;
use Excel;
use App\Exports\CargaExport;

use Auth;
use DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Gate;

// REGRAS 
// CARGA - CARGA ITEM STATUS - 
//     -andamento    - 0
//     -processando  - 1
//     -vendido      - 2
//     -finalizado   - 3
//     -estornado    - 4
//     -devolvido    - 5

// VENDA ITEM STATUS -
//     -vendido     - 0
//     -devolvido   - 1
//     -trocado     - 2
//     -devolvido p/ carga  - 3

class CargaController extends Controller
{
    private $statuscarga;
    private $statusvendaitem;
    private $date;    

    public function __construct(){

        $this->middleware('auth');
        $this->date = date('Y-m-d');

        $this->statuscarga = [
            'andamento',
            'processando',
            'vendido',
            'finalizado',
            'estornado',
            'devolvido'
        ];

        $this->statusvendaitem = [
            'vendido',
            'devolvido',
            'trocado',
            'estornado',
            'devolvido carga'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('carga-menu')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'data' => route('carga.data'),
        );

        return view('carga.index', compact("page"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::denies('carga-create')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA SE DADOS DO COMPONENTES DE SELECT AINDA EXISTEM 
        $user = User::find($request->user_id);
        if ($user == null) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return back()->with('notify', $exnotify);
        }

        $request->request->add(['status' => 0]);
        $carga = Carga::create($request->all());

        $exnotify[] = notifySuccess('Carga criada com sucesso!');
        return redirect()->route('carga.cargaitem', $carga)->with('notify', $exnotify);
    }

    public function edit(Carga $carga)
    {
        abort(404,"Not Found");
    }

    public function show(Carga $carga)
    {
        abort(404,"Not Found");
    }

    // TELA ADICIONA ITEM
    public function cargaitem(Carga $carga)
    {
        if(Gate::denies('carga-create')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA EM ANDAMENTO 0
        if ($carga->status != 0) {
            $exnotify[] = notifyWarning('Item não pode ser adicionado, carga não está em andamento <br> Carga status: '.$this->statuscarga[$carga->status]);
            return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
        }

        return view('carga.cargaitem', compact('carga'));
    }

    // ADICIONA ITEM / BAIXA NO ESTOQUE
    public function cargaitemstore(CargaItemRequest $request, Carga $carga)
    {
        if(Gate::denies('carga-create')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA EM ANDAMENTO 0
        if ($carga->status != 0) {
            $exnotify[] = notifyWarning('Item não pode ser adicionado, carga não está em andamento <br> Carga status: '.$this->statuscarga[$carga->status]);
            return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
        }

        // VERIFICA SE DADOS DO COMPONENTES DE SELECT AINDA EXISTEM 
        $produto = Produto::find($request->produto_id);
        if ($produto == null) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return back()->with('notify', $exnotify);
        }

        $produto = Produto::withTrashed()->find($request->produto_id);
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

            // VERIFICA SE A QUANTIDADE INFORMADA É INTEIRA
            if ($quantidade != $inteiro) {
                $exnotify[] = notifyWarning('Quantidade informada não é válida!');
                return back()->with('notify', $exnotify);
            }
        }

        $estoque = Estoque::where('produto_id', '=', $request->produto_id)->where('restante', '>', '0')->orderBy('id')->get();

        // contador para saber a sequencia do cargaitem
        $cargaitem = $carga->cargaitem;
        $sequencia = count($cargaitem);
        $sequencia ++;

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

                    $request->request->add(['carga_id' => $carga->id]);
                    $request->request->add(['estoque_id' => $e->id]);
                    $request->request->add(['sequencia' => $sequencia]);
                    $request->request->add(['descricao' => 'Aguardando Venda']);
                    $request->request->add(['status' => 0]);

                    CargaItem::create($request->all());

                    $exnotify[] = notifySuccess('Produto '.$e->produto->descricao.' adicionado à carga!');
                    return redirect()->route('carga.cargaitem', $carga)->with('notify', $exnotify);
                } else {
                    $quantirestante = (str_replace (',', '.', str_replace ('.', '', $request->quantidade)) - $e->restante);
                    $request->request->add(['quantidade' => number_format($e->restante, 2, ',', '.')]);
                    $e->restante = "0,00";
                    $e->save();

                    $request->request->add(['carga_id' => $carga->id]);
                    $request->request->add(['estoque_id' => $e->id]);
                    $request->request->add(['sequencia' => $sequencia]);
                    $request->request->add(['descricao' => 'Aguardando Venda']);
                    $request->request->add(['status' => 0]);
                    CargaItem::create($request->all());

                    $quantirestante = number_format($quantirestante, 2, ',', '.');
                    $request->request->add(['quantidade' => $quantirestante]);
                }
            }
        } else {
            $exnotify[] = notifyWarning('Quantidade informada maior que estoque restante!');
            return redirect()->route('carga.cargaitem', $carga)->with('notify', $exnotify);
        }
    }

    // REMOVE ITEM DA CARGA
    public function destroyitem(CargaItem $cargaitem)
    {
        if(Gate::denies('carga-create')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        $carga = $cargaitem->carga;

        // VERIFICA STATUS SE ESTA EM ANDAMENTO 0
        if ($carga->status != 0) {
            $exnotify[] = notifyWarning('Item não pode ser removido, carga não está em andamento <br> Carga status: '.$this->statuscarga[$carga->status]);
            return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
        }

        $restante = floatval($cargaitem->quantidade + $cargaitem->estoque->restante);
        $restante = number_format($restante, 2, ',', '.');
        $cargaitem->estoque->restante = $restante;
        $cargaitem->estoque->save();
        $cargaitem->delete();

        $exnotify[] = notifySuccess('Produto '.$cargaitem->produto->descricao.' removido da carga!');
        return redirect()->route('carga.cargaitem', $cargaitem->carga)->with('notify', $exnotify);
    }

    // UPDATE/SAVE EM CARGA DIRECIONA PARA DETALHAR CARGA - atualizacarga
    public function atualizacarga(CargaAtualizaRequest $request, Carga $carga)
    {
        if(Gate::denies('carga-create')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICA STATUS SE ESTA EM ANDAMENTO
        if ($carga->status != 0) {
            $exnotify[] = notifyWarning('Está carga não pode ser atualizada, carga não está em andamento <br> Carga status: '.$this->statuscarga[$carga->status]);
            return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
        }

        // VERIFICA SE JA TEM ITEM ADICIONADO
        if (empty($carga->cargaitem->first())) {
            $exnotify[] = notifyWarning('Favor adicionar um item antes de concluir a carga');
            return redirect()->route('carga.cargaitem', $carga)->with('notify', $exnotify);
        }

        $carga->update($request->all());

        $exnotify[] = notifyInfo('Carga em andamento!');
        return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
    }

    // DETALHAR CARGA - detalhar
    public function detalhar(Carga $carga)
    {
        if(Gate::denies('carga-detalhar')){
            abort(403,"Não autorizado!");
        }

        $itens = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(ci.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN carga_items ci ON ci.produto_id = p.id WHERE ci.carga_id = ".$carga->id." GROUP BY p.id");

        $andamento = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(ci.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN carga_items ci ON ci.produto_id = p.id WHERE ci.carga_id = ".$carga->id." and ci.status = 0 GROUP BY p.id");

        $vendido = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(ci.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN carga_items ci ON ci.produto_id = p.id WHERE ci.carga_id = ".$carga->id." and ci.status = 2 GROUP BY p.id");

        $status = $this->statuscarga;

        return view('carga.detalhar', compact("carga", "itens", "andamento", "vendido", "status"));
    }

    // ESTORNAR CARGA - estornar
    public function estornar(Carga $carga)
    {
        if(Gate::denies('carga-estornar')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        // VERIFICAR SE A CARGA ESTÁ EM ANDAMENTO
        if ($carga->status != 0) 
        {
            $exnotify[] = notifyWarning('Carga não pode ser estornada, carga não está em andamento <br> Carga status: '.$this->statuscarga[$carga->status]);
            return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
        }

        foreach ($carga->cargaitem as $key => $cargaitem) 
        {
            $restante = floatval($cargaitem->quantidade + $cargaitem->estoque->restante);
            $restante = number_format($restante, 2, ',', '.');
            $cargaitem->estoque->restante = $restante;
            $cargaitem->status = 3;
            $cargaitem->update();
            $cargaitem->estoque->update();
        }

        $carga->update(['status' => 4]);

        $exnotify[] = notifySuccess('Carga estornada com sucesso!');
        return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
    }

    // FINALIZAR CARGA, DEVOLVER ESTOQUE E GERAR VENDA - finalizacarga
    public function finalizacarga(Carga $carga)
    {
        if(Gate::denies('carga-finalizar')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        if ($carga->status != 1) {
            $exnotify[] = notifyWarning('Não é possivel finalizar a carga <br> Carga status: '.$this->statuscarga[$carga->status]);
            return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
        }

        // VERIFICA SE TEM ALGUMA VENDA COM VALOR NEGATIVO
        foreach ($carga->venda as $key => $venda) {
            $subtotal = 0.00;
            foreach ($venda->vendaitem as $key => $item) 
            {
                if ($item->status != 4) {
                    $subtotal += ($item->valorvenda*$item->quantidade);
                }
            }
            $total = $subtotal - $venda->desconto;
            if ($total < 0.0) {
                $exnotify[] = notifyWarning('Venda com valor nagativo! <br> <a href="'.route('venda.detalhar', $venda).'" target="_blank">Acessar venda!</a>');
                return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
            }
        }   

        // DEVOLVE OS ITEM QUE NÃO FORAM VENDIDOS PARA ESTOQUE
        foreach ($carga->cargaitem as $key => $cargaitem) 
        {
            if ($cargaitem->status == 0) {
                $restante = floatval($cargaitem->quantidade + $cargaitem->estoque->restante);
                $restante = number_format($restante, 2, ',', '.');
                $cargaitem->estoque->restante = $restante;
                $cargaitem->estoque->update();
                $cargaitem->status = 5;
                $cargaitem->descricao = 'Produto devolvido';
                $cargaitem->update();
            }
        }

        foreach ($carga->venda as $key => $venda) {
            // VERIFICAR SE TODOS OS ITEM DA VENDA FORAM DEVOLVIDOS PARA CARGA
            $item = $venda->vendaitem->where('status', '!=', 4)->first();
            if ($item == null) {
                $venda->desconto = "0,00";
                $venda->finalizavenda = date("Y-m-d H:i:s");
                $venda->status = 3;
                $venda->update();
            } else {   
                $venda->finalizavenda = date("Y-m-d H:i:s");
                $venda->status = 2;
                $venda->update();

                // VALOR PARA ADICIONAR AO CAIXA
                $subtotal = 0.00;
                foreach ($venda->vendaitem as $key => $item) 
                {
                    if ($item->status != 4) {
                        $subtotal += ($item->valorvenda*$item->quantidade);
                    }
                }
                $total = $subtotal - $venda->desconto;
                // ADICIONA NO CAIXA
                $lancamento = $caixa->lancamento()->create([
                    'tipo_lancamento' => 1,
                    'user_id' => Auth::user()->id,
                    'venda_id' => $venda->id,
                    'descricao' => 'Venda por carga',
                    'valor' => number_format($total, 2, ',', '.'),
                ]);
                $caixa->entrada += $total;
                $caixa->update();
            }
        }

        $carga->update(['status' => 3]);

        $exnotify[] = notifySuccess('Carga finalizada com sucesso!');
        $exnotify[] = notifySuccess('Valor adicionado ao caixa sucesso!');
        return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
    }

    // GERA VENDA A PARTIR DA CARGA - venda
    public function venda(Carga $carga)
    {
        if(Gate::denies('carga-gerar-venda')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }

        if ($carga->status != 0 && $carga->status != 1) {
            $exnotify[] = notifyWarning('Não é possível gerar uma venda <br> Carga status: '.$this->statuscarga[$carga->status]);
            return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
        }

        $itens = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(ci.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN carga_items ci ON ci.produto_id = p.id WHERE ci.carga_id = ".$carga->id." and ci.status = 0 GROUP BY p.id");

        if (empty($itens)) {
            $exnotify[] = notifyWarning('Não é possível gerar uma venda, nenhum item disponível!');
            return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
        }

        return view('carga.gerarvenda', compact('carga', 'itens'));
    }

    // STORE GERA VENDA E ASSOCIA A UMA ROTA - storevenda
    public function storevenda(CargaVendaRequest $request, Carga $carga)
    {
        if(Gate::denies('carga-gerar-venda')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if (empty($caixa)) {
            $exnotify[] = notifyInfo('Caixa está fechado!');
            return redirect()->back()->with('notify', $exnotify);
        }
        
        if ($carga->status != 0 && $carga->status != 1) {
            $exnotify[] = notifyWarning('Não foi possivel gerar uma venda <br> Carga status: '.$this->statuscarga[$carga->status]);
            return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
        }

        // VERIFICA SE O DESCONTO É MAIOR IGUAL 0,00
        if (str_replace (',', '.', str_replace ('.', '', $request->desconto)) < 0.00) {
            $exnotify[] = notifyWarning('Quantidade informada não é válida!');
            return back()->with('notify', $exnotify);
        }

        // VERIFICA SE DADOS DO COMPONENTES DE SELECT AINDA EXISTEM 
        $rota = Rota::find($request->rota_id);
        if ($rota == null) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return back()->with('notify', $exnotify);
        }

        $vendido1 = $request->vendido;
        $vendido = $request->vendido;

        $itens = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(ci.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN carga_items ci ON ci.produto_id = p.id WHERE ci.carga_id = ".$carga->id." and ci.status = 0 GROUP BY p.id");

        if (empty($itens)) {
            $exnotify[] = notifyWarning('Não é possível gerar uma venda, nenhum item disponível!');
            return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
        }

        // VALIDAÇÕES DOS CAMPOS DO FORMULÁRIO
        // VERIFICA SE O ARRAY DA TELA CORRESPONDE AO ARRAY DE ITENS DO BANCO
        $i = 0;
        foreach ($request->idproduto as $key => $value) {
            if (intval($value) != $itens[$i]->id) {
                $exnotify[] = notifyWarning('Opss... Ocorreu um erro inesperado');
                return redirect()->route('carga.venda', $carga)->with('notify', $exnotify);
            }
            $i++;
        }

        $i = 0;
        foreach ($itens as $key => $value) {
            if ($value->id != intval($request->idproduto[$i])) {
                $exnotify[] = notifyWarning('Opss... Ocorreu um erro inesperado');
                return redirect()->route('carga.venda', $carga)->with('notify', $exnotify);
            }
            $i++;
        }

        // VERIFICA SE QUANTIDADE CORRESPODNE A UNIDADE DE MEDIDA DO PRODUTO
        $i = 0;
        foreach ($itens as $key => $value) {
            $produto = Produto::withTrashed()->find($value->id);
            $unidade = Unidade::withTrashed()->find($produto->unidade_id);

            // VERIFICA SE A QUANTIDADE É MAIOR QUE 0,00
            if (str_replace (',', '.', str_replace ('.', '', $request->vendido[$i])) < 0.00) {
                $exnotify[] = notifyWarning('Quantidade informada não é válida!');
                return back()->with('notify', $exnotify);
            }
            if (str_replace (',', '.', str_replace ('.', '', $request->valorvenda[$i])) < 0.00) {
                $exnotify[] = notifyWarning('Valor informado não é válida!');
                return back()->with('notify', $exnotify);
            }

            // VERIFICA SE A UNIDADE É INTEIRA
            if ($unidade->inteiro == 1) {

                $double = str_replace (',', '.', str_replace ('.', '', $request->vendido[$i]));
                $quantidade = floatval($double);

                $inteiro = intval($double);
                $inteiro = floatval($inteiro);

                // VERIFICA SE A QUANTIDADE INFORMADA É INTEIRA
                if ($quantidade != $inteiro) {
                    $exnotify[] = notifyWarning('Quantidade informada não é válida!');
                    return back()->with('notify', $exnotify);
                }
            }

            if ($value->quantidade < str_replace (',', '.', str_replace ('.', '', $request->vendido[$i]))) {
                $exnotify[] = notifyWarning('Quantidade informada maior que quantidade total');
                return redirect()->route('carga.venda', $carga)->with('notify', $exnotify);
            }
            $i++;
        }

        // APOS DEVOLVER OS INTENS INICIA A VENDA E ADICIONA OS ITENS QUE FORAM DEVOLVIDOS PARA O ESTOQUE
        $venda = Venda::where('rota_id', '=', $request->rota_id)->where('carga_id', '=', $carga->id)->first();

        if (empty($venda)) {
            $venda = $carga->venda()->create([
                'observacao' => $carga->observacao,
                'desconto' => $request->desconto,
                'frete' => 0.00,
                'status' => 4,
                'cliente_id' => 1,
                'user_id' => Auth::user()->id,
                'forma_pagamento_id' => 1,
                'rota_id' => $request->rota_id,
                'carga_id' => $carga->id,
            ]);

            $carga->update([
                'status' => 1,
            ]);
        } else {
            $desconto = str_replace (',', '.', str_replace ('.', '', $request->desconto));
            $desconto = $venda->desconto + $desconto;
            $venda->desconto = number_format($desconto, 2, ',', '.');
            $venda->update();
        }

        // ATUALIZA OS STAUTS DOS CARGA ITEM E GERA OS VENDA ITEM
        $i = 0;
        foreach ($itens as $key => $value) {

            $produto = Produto::withTrashed()->find($value->id);
            $estoque = Estoque::where('produto_id', '=', $produto->id)->where('restante', '>', '0')->orderBy('id')->get();
            $itemcarga = $carga->cargaitem()->where('produto_id', '=', $value->id)->where('status', '=', 0)->orderBy('quantidade', 'desc')->get();

            // contador para saber a sequencia do itemvenda
            $cargaitem = $carga->cargaitem;
            $sequencia = count($cargaitem);
            $sequencia ++;

            // contador para saber a sequencia do itemvenda
            $vendaitem = $venda->vendaitem;
            $sequencia1 = count($vendaitem);
            $sequencia1 ++;

            foreach ($itemcarga as $key => $item) {
                if (!(str_replace (',', '.', str_replace ('.', '', $vendido1[$i])) == 0.00)) {
                    if ($item->quantidade >= str_replace (',', '.', str_replace ('.', '', $vendido1[$i]))) {

                        $restante = ($item->quantidade - str_replace (',', '.', str_replace ('.', '', $vendido1[$i])));

                        if ($restante == 0.0) {
                            $item->update([
                                'descricao' => 'Produto vendido',
                                'status' => 2,
                            ]);

                            VendaItem::create([
                                'sequencia' => $sequencia1,
                                'quantidade' => $vendido1[$i],
                                'status' => 0,
                                'valorvenda' => $request->valorvenda[$i],
                                'descricao' => 'Venda por rota',
                                'venda_id' => $venda->id,
                                'estoque_id' => $item->estoque->id,
                                'produto_id' => $produto->id,
                            ]);
                        } else {
                            $restante = number_format($restante, 2, ',', '.');
                            $item->update([
                                'quantidade' => $restante,
                            ]);

                            CargaItem::create([
                                'sequencia' => $sequencia,
                                'quantidade' => $vendido1[$i],
                                'status' => 2,
                                'descricao' => 'Produto vendido',
                                'carga_id' => $carga->id,
                                'estoque_id' => $item->estoque->id,
                                'produto_id' => $item->produto->id,
                            ]);

                            VendaItem::create([
                                'sequencia' => $sequencia1,
                                'quantidade' => $vendido1[$i],
                                'status' => 0,
                                'valorvenda' => $request->valorvenda[$i],
                                'descricao' => 'Venda por rota',
                                'venda_id' => $venda->id,
                                'estoque_id' => $item->estoque->id,
                                'produto_id' => $produto->id,
                            ]);
                        }
                        $vendido1[$i] = '0,00';
                    } else {
                        $quantirestante = (str_replace (',', '.', str_replace ('.', '', $vendido1[$i])) - $item->quantidade);

                        $item->update([
                            'descricao' => 'Produto vendido',
                            'status' => 2,
                        ]);

                        VendaItem::create([
                            'sequencia' => $sequencia1,
                            'quantidade' => number_format($item->quantidade, 2, ',', '.'),
                            'status' => 0,
                            'valorvenda' => $request->valorvenda[$i],
                            'descricao' => 'Venda por rota',
                            'venda_id' => $venda->id,
                            'estoque_id' => $item->estoque->id,
                            'produto_id' => $produto->id,
                        ]);

                        $quantirestante = number_format($quantirestante, 2, ',', '.');
                        $vendido1[$i] = $quantirestante;
                    }
                }
            }
            $i++;
        }
        $exnotify[] = notifySuccess('Venda gerada com sucesso!');
        return redirect()->route('venda.detalhar', $venda)->with('notify', $exnotify);
    }

    // DETALHAR VENDAS ORIGINADAS DA CARGA - detalharvenda
    public function detalharvenda(Carga $carga)
    {
        if(Gate::denies('carga-detalhar-venda')){
            abort(403,"Não autorizado!");
        }

        $statusvendaitem = $this->statusvendaitem;
        $total = 0.00;
        return view('carga.detalharvenda', compact("carga", "statusvendaitem", "total"));
    }

    // PDF CARGA - 
    public function cargapdf(Carga $carga)
    {
        if ($carga->status == 4) {
            $exnotify[] = notifyInfo('Download não pode ser emitido!');
            return redirect()->route('carga.detalhar', $carga)->with('notify', $exnotify);
        }

        $datetime = date('YmdHis');

        $itens = DB::select("SELECT p.id, p.descricao, u.sigla, SUM(ci.quantidade) AS quantidade from produtos p left JOIN unidades u ON u.id = p.unidade_id JOIN carga_items ci ON ci.produto_id = p.id WHERE ci.carga_id = ".$carga->id." GROUP BY p.id");

        // return view('carga.cargapdf', compact("carga", "itens"));        

        $pdf = PDF::loadview('carga.cargapdf', compact('carga', 'itens'));
        return $pdf->stream($datetime.'.pdf');

    }
    
    // SELECT2 DA ROTA NA TELA DE CARGA - inforota
    public function inforota(Request $request)
    {
        $values = Rota::select('id as id', 'nome as text')->where('nome','like', '%'.$request->q.'%')->limit(20)->get();
        return $values;
    }

    //SELECT2 DE PRODUTO - infoproduto
    public function infoproduto(Request $request)
    {
        $values = Produto::select('id as id', 'descricao as text')->where('descricao','like', '%'.$request->q.'%')->limit(20)->get();
        return $values;
    }

    //Ajax para mostrar quantidade em estoque e média de custo
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
                'customedio' => number_format($customedio, 2, ',', '.'),
                'quantidadestoque' => number_format($restante, 2, ',', '.')
            );

            return $data;
        }

        $data = array(
            'customedio' => '0,00',
            'quantidadestoque' => $restante
        );

        return $data;
    }

    // DATA PARA O DATATABLES
    public function data()
    {
        $values = Carga::get();

        return Datatables::of($values)
        ->addColumn('user', function($values){
            return User::withTrashed()->find($values->user_id)->name;
        })     
        ->addColumn('status', function($values){
            return strtoupper($this->statuscarga[$values->status]);
        })        
        ->addColumn('created_at', function($values){
            return $values->created_at;
        })
        ->addColumn('action', function($values){
            $concatbutton = '';
            if(!Gate::denies('carga-detalhar')){
                $concatbutton .= '
                <a  class="btn btn-secondary btn-sm mt-1" data-toggle="tooltip" title="Detalhar carga" href="'.route('carga.detalhar',$values).'"><i class="fas fa-info-circle"></i> Detalhar Carga</a>';
            }
            return $concatbutton;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    // RELATORIOS
    public function relatorio()
    {
        $rotas = Rota::all();
        $entregadores = User::where('entregador', '=', 1)->get();
        return view('carga.relatorio.relatorio', compact("rotas", "entregadores"));
    }

    // GERA RELATORIO
    public function relatoriostore(Request $request)
    {
        set_time_limit(300);

        $datetime = date('YmdHis'); 

        $datainicio = $request->datainicio;
        $datainicio = (str_replace( '/', '-', $datainicio));
        $datainicio = date('Y-m-d', strtotime($datainicio));
        $datafim = $request->datafim;
        $datafim = (str_replace( '/', '-', $datafim));
        $datafim = date('Y-m-d', strtotime($datafim));

        // GERAR CSV
        $relatorio = $request->relatorio;
        $status = $request->status;
        $entregador = $request->entregador;
        $rota = $request->rota;
        // dd($request->request);
        if ($request->exportacao == 1) {
            return Excel::download(new CargaExport($datainicio, $datafim, $relatorio, $status, $entregador, $rota), $datetime.'.csv');
        }
    }
}