<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\VendaItem;
use App\Models\Fornecedor;
use App\Models\Produto;
use App\Models\Unidade;

use DB;
use Yajra\Datatables\Datatables;
use App\Http\Requests\EstoqueRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Exports\EstoqueExport;
use PDF;
use Excel;


class EstoqueController extends Controller
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
        if(Gate::denies('estoque-menu')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'data' => route('estoque.data'),
            'forceDelete' => route('estoque.forceDelete'),
        );

        return view('estoque.index', compact("page"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('estoque-create')){
            abort(403,"Não autorizado!");
        }

        return view('estoque.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EstoqueRequest $request)
    {
        if(Gate::denies('estoque-create')){
            abort(403,"Não autorizado!");
        }

        // VERIFICA SE A QUANTIDADE TOTAL É MAIOR QUE 0,00
        if (str_replace (',', '.', str_replace ('.', '', $request->total)) <= 0.00) {
            $exnotify[] = notifyWarning('Quantidade informada não é válida!');
            return back()->with('notify', $exnotify);
        }
        if (str_replace (',', '.', str_replace ('.', '', $request->valorcusto)) < 0.00) {
            $exnotify[] = notifyWarning('Valor de custo informado não é válido!');
            return back()->with('notify', $exnotify);
        }

        // VERIFICA SE DADOS DO COMPONENTES DE SELECT AINDA EXISTEM 
        $produto = Produto::find($request->produto_id);
        $fornecedor = Fornecedor::find($request->fornecedor_id);
        if ($produto == null || $fornecedor == null) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return back()->with('notify', $exnotify);
        }

        $produto = Produto::withTrashed()->find($request->produto_id);
        $unidade = Unidade::withTrashed()->find($produto->unidade_id);

        // VERIFICA SE A UNIDADE É INTEIRA
        if ($unidade->inteiro == 1) {
            $double = str_replace (',', '.', str_replace ('.', '', $request->total));
            $total = floatval($double);

            $inteiro = intval($double);
            $inteiro = floatval($inteiro);

            // VERIFICA SE A QUANTIDADE MINIMA INFORMADA É INTEIRA
            if ($total != $inteiro) {
                $exnotify[] = notifyWarning('Quantidade informada não é válida!');
                return back()->withInput()->with('notify', $exnotify);
            }
        }

        $request->request->add(['restante' => $request->total]);

        $estoque = Estoque::create($request->all());

        $exnotify[] = notifySuccess('Estoque '.$produto->descricao.' adicionado com sucesso!');
        return redirect()->route('estoque.index')->with('notify', $exnotify);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estoque  $estoque
     * @return \Illuminate\Http\Response
     */
    public function show(Estoque $estoque)
    {
        abort(404,"Not Found");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estoque  $estoque
     * @return \Illuminate\Http\Response
     */
    public function edit(Estoque $estoque)
    {
        if(Gate::denies('estoque-edit')){
            abort(403,"Não autorizado!");
        }

        if ($estoque->produto_id != null) {
            $produto = array(
                'id' => $estoque->produto_id,
                'text' => Produto::withTrashed()->find($estoque->produto_id)->descricao,
                'selected' => true,
            );
            $produto = json_encode($produto);
            $produto = "[".$produto."]";
        }else{
            $produto = "[]";
        }

        if ($estoque->fornecedor_id != null) {
            $fornecedor = array(
                'id' => $estoque->fornecedor_id,
                'text' => Fornecedor::withTrashed()->find($estoque->fornecedor_id)->nomerazao,
                'selected' => true,
            );
            $fornecedor = json_encode($fornecedor);
            $fornecedor = "[".$fornecedor."]";
        }else{
            $fornecedor = "[]";
        }

        return view('estoque.edit', compact("estoque", "produto", "fornecedor"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estoque  $estoque
     * @return \Illuminate\Http\Response
     */
    public function update(EstoqueRequest $request, Estoque $estoque)
    {
        if(Gate::denies('estoque-edit')){
            abort(403,"Não autorizado!");
        }

        // VERIFICA SE A QUANTIDADE TOTAL E VALOR DE CUSTO É MAIOR QUE 0,00
        if (str_replace (',', '.', str_replace ('.', '', $request->total)) <= 0.00) {
            $exnotify[] = notifyWarning('Quantidade informada não é válida!');
            return back()->with('notify', $exnotify);
        }
        if (str_replace (',', '.', str_replace ('.', '', $request->valorcusto)) < 0.00) {
            $exnotify[] = notifyWarning('Valor de custo informado não é válido!');
            return back()->with('notify', $exnotify);
        }

        // VERIFICA SE TEM VINCULO E VERIFICA PARA NÃO DEIXAR ALTERAR O PRODUTO DO ESTOQUE
        // CASO FOR EDITAR UM PRODUTO E O PRODUTO ESTIVER DELETADO TEMPORARIAMENTE NAO VAI DAR PARA ALTERTA
        if ($estoque->vendaitem->first() != null || $estoque->cargaitem->first() != null) {
            if ($request->produto_id != $estoque->produto_id) {
                $exnotify[] = notifyInfo('Não é possível alterar o produto do estoque! Nada foi alterado');
                return back()->withInput()->with('notify', $exnotify);
            }
        }

        // VERIFICA SE DADOS DO COMPONENTES DE SELECT AINDA EXISTEM 
        if (!($request->produto_id == $estoque->produto_id && $request->fornecedor_id == $estoque->fornecedor_id)) {
            $produto = Produto::find($request->produto_id);
            $fornecedor = Fornecedor::find($request->fornecedor_id);
            if ($produto == null || $fornecedor == null) {
                $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
                return back()->with('notify', $exnotify);
            }
        }

        $produto = Produto::withTrashed()->find($request->produto_id);
        $unidade = Unidade::withTrashed()->find($produto->unidade_id);

        // VERIFICA SE A UNIDADE É INTEIRA
        if ($unidade->inteiro == 1) {
            $double = str_replace (',', '.', str_replace ('.', '', $request->total));
            $total = floatval($double);

            $inteiro = intval($double);
            $inteiro = floatval($inteiro);

            // VERIFICA SE A QUANTIDADE MINIMA INFORMADA É INTEIRA
            if ($total != $inteiro) {
                $exnotify[] = notifyWarning('Quantidade informada não é válida!');
                return back()->withInput()->with('notify', $exnotify);
            }
        }

        // DESCOBRIR O RESTANTE
        $restante = ((str_replace (',', '.', str_replace ('.', '', $request->total)) - $estoque->total) + $estoque->restante);
        
        // VERIFICACAO ANTES DO UPDATE DE QUANTIDADE NO ESTOQUE
        if ($restante < 0.0) {
            $exnotify[] = notifyWarning('Quantidade informada menor que estoque consumido! Estoque consumido: '.number_format($estoque->total - $estoque->restante, 2, ',', '.').' '.$unidade->sigla);
            return redirect()->route('estoque.edit', $estoque)->with('notify', $exnotify);
        }

        $restante = number_format($restante, 2, ',', '.');
        $request->request->add(['restante' => $restante]);
        $estoque->update($request->all());

        $exnotify[] = notifySuccess('Estoque '.$produto->descricao.' editado com sucesso!');
        return redirect()->route('estoque.index')->with('notify', $exnotify);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estoque  $estoque
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estoque $estoque)
    {
        //
    }

    // DESTROY PERMANENTEMENTE UM REGISTRO DO DATATABLES
    public function forceDelete(Request $request)
    {
        if(Gate::denies('estoque-del-perm')){
            return notifyAjax403();
        }

        $values = Estoque::find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }
        
        // FAZER VALIDAÇÔES SE TEM DEPENDENCIAS E ADICIONAR MENSAGEM DE ERRO CASO TENHA
        if ($values->vendaitem->first() != null || $values->cargaitem->first() != null) {
            return response()->json([
                'status' => 'Info',
                'icon' => 'fas fa-bell',
                'title' => '<strong>Alerta:</strong>',
                'message' => 'Estoque não pode ser excluido, contem dependências!',
                'type' => 'info'
            ]);
        }

        $values->forceDelete();

        return notifyForceDelete();
    }

    // SELECT2 PRODUTO - infoproduto
    public function infoproduto(Request $request)
    {
        $values = Produto::select('id as id', 'descricao as text')->where('descricao','like', '%'.$request->q.'%')->limit(20)->get();
        return $values;
    }

    // SELECT2 FORNECEDOR - infofornecedor
    public function infofornecedor(Request $request)
    {
        $values = Fornecedor::select('id as id', 'nomerazao as text')->where('nomerazao','like', '%'.$request->q.'%')->limit(20)->get();
        return $values;
    }

    // DATA PARA O DATATABLES
    public function data()
    {
        $values = Estoque::all();

        return Datatables::of($values)
        ->addColumn('produto', function($values){
            return Produto::withTrashed()->find($values->produto_id)->descricao;
        })
        ->addColumn('fornecedor', function($values){
            return Fornecedor::withTrashed()->find($values->fornecedor_id)->nomerazao;
        })
        ->addColumn('restante', function($values){
            $produto = Produto::withTrashed()->find($values->produto_id);
            return number_format($values->restante, 2, ',', '.').' '.Unidade::withTrashed()->find($produto->unidade_id)->sigla; 
        })
        ->addColumn('valorcusto', function($values){
            return 'R$ '.number_format($values->valorcusto, 2, ',', '.');
        })
        ->addColumn('action', function($values){
            $concatbutton = '';
            if(!(Gate::denies('estoque-edit'))){
                $concatbutton .= '
                <a  class="btn btn-info btn-sm edit mt-1" data-toggle="tooltip" title="Editar" href="'.route('estoque.edit',$values).'"><i class="fas fa-pencil-alt"></i></a>';
            }
            if(!(Gate::denies('estoque-del-perm'))){
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
        return view('estoque.relatorio.relatorio');
    }

    // GERA RELATORIO
    public function relatoriostore(Request $request)
    {
        $datetime = date('YmdHis'); 
        $data = date('d/m/Y H:i');

        // GERAR PDF
        if ($request->exportacao == 2) {
            if ($request->status == 1) {
                $title = "RELATÓRIO DE ESTOQUE ANTIGO";
                $estoques = Estoque::where('restante', '>', 0)->orderBy('created_at', 'asc')->get();
                $count = count($estoques);
            }

            $pdf = PDF::loadview('estoque.relatorio.estoque', compact('estoques', 'title', 'data', 'count'));
            return $pdf->download($datetime.'.pdf');
        }

        // GERAR CSV
        $status = $request->status;
        if ($request->exportacao == 1) {
            return Excel::download(new EstoqueExport($status), $datetime.'.csv');
        }
    }
}
