<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Estoque;

use DB;
use Yajra\Datatables\Datatables;
use App\Http\Requests\ProdutoRequest;
use App\Models\Unidade;

use PDF;
use Excel;
use App\Exports\ProdutoExport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProdutoController extends Controller
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
        if(Gate::denies('produto-menu')){
            abort(403,"Não autorizado!");
        }

        $page = array(
            'data' => route('produto.data'),
            'show' => route('produto.showData'),
            'destroy' => route('produto.destroyTemp'),
            'forceDelete' => route('produto.forceDelete'),
            'restore' => route('produto.restore'),
        );

        return view('produto.index', compact("page"));
    }

    /**
     * Show the form for creating a new resource.
     *f
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('produto-create')){
            abort(403,"Não autorizado!");
        }

        return view('produto.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdutoRequest $request)
    {
        if(Gate::denies('produto-create')){
            abort(403,"Não autorizado!");
        }

        $unidade = Unidade::find($request->unidade_id);

        // VERIFICA SE DADOS DO COMPONENTES DE SELECT AINDA EXISTEM 
        if ($unidade == null) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return back()->with('notify', $exnotify);
        }

        // VERIFICA SE A QUANTIDADE TOTAL É MAIOR QUE 0,00
        if (str_replace (',', '.', str_replace ('.', '', $request->valorvenda)) < 0.00) {
            $exnotify[] = notifyWarning('Valor de venda informado não é válida!');
            return back()->with('notify', $exnotify);
        }
        if (str_replace (',', '.', str_replace ('.', '', $request->minimo)) < 0.00) {
            $exnotify[] = notifyWarning('Estoque mínimo informado não é válido!');
            return back()->with('notify', $exnotify);
        }

        // VERIFICA SE A UNIDADE É INTEIRA
        if ($unidade->inteiro == 1) {
            $double = str_replace (',', '.', str_replace ('.', '', $request->minimo));
            $minimo = floatval($double);

            $inteiro = intval($double);
            $inteiro = floatval($inteiro);

            // VERIFICA SE A QUANTIDADE MINIMA INFORMADA É INTEIRA
            if ($minimo != $inteiro) {
                $exnotify[] = notifyWarning('Quantidade informada não é válida!');
                return back()->withInput()->with('notify', $exnotify);
            }
        }

        $produto = Produto::create($request->all());

        $exnotify[] = notifySuccess('Produto '.$produto->descricao.', foi adicionado com sucesso!');
        return redirect()->route('produto.index')->with('notify', $exnotify);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function show(Produto $produto)
    {
        abort(404,"Not Found");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function edit(Produto $produto)
    {
        if(Gate::denies('produto-edit')){
            abort(403,"Não autorizado!");
        }

        if ($produto->unidade_id != null) {
            $data = array(
                'id' => $produto->unidade_id,
                'text' => Unidade::withTrashed()->find($produto->unidade_id)->descricao,
                'selected' => true,
            );
            $data = json_encode($data);
            $data = "[".$data."]";
        }else{
            $data = "[]";
        }

        return view('produto.edit', compact("produto", "data"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function update(ProdutoRequest $request, Produto $produto)
    {
        if(Gate::denies('produto-edit')){
            abort(403,"Não autorizado!");
        }

        // VERIFICA SE A QUANTIDADE TOTAL É MAIOR QUE 0,00
        if (str_replace (',', '.', str_replace ('.', '', $request->valorvenda)) < 0.00) {
            $exnotify[] = notifyWarning('Valor de venda informado não é válida!');
            return back()->with('notify', $exnotify);
        }
        if (str_replace (',', '.', str_replace ('.', '', $request->minimo)) < 0.00) {
            $exnotify[] = notifyWarning('Estoque mínimo informado não é válido!');
            return back()->with('notify', $exnotify);
        }

        // VERIFICA SE TEM VINCULO E VERIFICA PARA NÃO DEIXAR ALTERAR A UNIDADE DO PRODUTO
        if ($produto->estoque->first() != null || $produto->vendaitem->first() != null || $produto->cargaitem->first() != null) {
            if ($request->unidade_id != $produto->unidade_id) {
                $exnotify[] = notifyInfo('Não é possível alterar a unidade do produto! Nada foi alterado');
                return back()->withInput()->with('notify', $exnotify);
            }
        }

        // VERIFICA SE DADOS DO COMPONENTES DE SELECT AINDA EXISTEM 
        if (!($request->unidade_id == $produto->unidade_id)) {
            $unidade = Unidade::find($request->unidade_id);
            if ($unidade == null) {
                $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
                return back()->with('notify', $exnotify);
            }
        }

        $unidade = Unidade::withTrashed()->find($request->unidade_id);

        // VERIFICA SE A UNIDADE É INTEIRA
        if ($unidade->inteiro == 1) {
            $double = str_replace (',', '.', str_replace ('.', '', $request->minimo));
            $minimo = floatval($double);

            $inteiro = intval($double);
            $inteiro = floatval($inteiro);

            // VERIFICA SE A QUANTIDADE MINIMA INFORMADA É INTEIRA
            if ($minimo != $inteiro) {
                $exnotify[] = notifyWarning('Quantidade informada não é válida!');
                return back()->withInput()->with('notify', $exnotify);
            }
        }

        $produto->update($request->all());

        $exnotify[] = notifySuccess('Produto '.$produto->descricao.', foi editado com sucesso!');
        return redirect()->route('produto.index')->with('notify', $exnotify);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produto $produto)
    {
        //
    }

    // SHOW DE UM REGISTRO DO DATATABLES
    public function showData(Request $request)
    {
        if(Gate::denies('produto-show')){
            abort(403,"Não autorizado!");
        }

        $values = Produto::withTrashed()->find($request->id);

        if (empty($values)) {
            $data = '<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="showModel">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Produto não encontrada</h5>
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

        $restante = 0;
        $custo = 0.00;
        $customedio = 0.00;
        foreach ($values->estoque as $value) {
            $restante += $value->restante;
            $custo += $value->valorcusto*$value->restante;
        }
        
        if ($restante > 0) {
            $customedio = $custo/$restante; 
        }

        $data = '<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="showModel">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Produto '.$values->descricao.'</h5>
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
        <th>Valor de venda</th>
        <td>R$ '.number_format($values->valorvenda, 2, ',', '.').'</td>
        </tr>
        <th>Valor de custo médio</th>
        <td>R$ '.number_format($customedio, 2, ',', '.').'</td>
        </tr>
        <th>Quantidade mínima</th>
        <td>'.number_format($values->minimo, 2, ',', '.').' '.Unidade::withTrashed()->find($values->unidade_id)->sigla.'</td>
        </tr>
        <th>Quantidade restante</th>
        <td>'.number_format($restante, 2, ',', '.').' '.Unidade::withTrashed()->find($values->unidade_id)->sigla.'</td>
        </tr>
        <th>Unidade</th>
        <td>'.Unidade::withTrashed()->find($values->unidade_id)->descricao.' - '.Unidade::withTrashed()->find($values->unidade_id)->sigla.'</td>
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
        if(Gate::denies('produto-delete')){
            return notifyAjax403();
        }

        $values = Produto::find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        $values->delete();

        return notifyDestroyTemp();   
    }  

    // DESTROY PERMANENTEMENTE UM REGISTRO DO DATATABLES
    public function forceDelete(Request $request)
    {
        if(Gate::denies('produto-del-perm')){
            return notifyAjax403();
        }

        $values = Produto::withTrashed()->find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        // FAZER VALIDAÇÔES SE TEM DEPENDENCIAS E ADICIONAR MENSAGEM DE ERRO CASO TENHA
        if ($values->estoque->first() != null || $values->vendaitem->first() != null || $values->cargaitem->first() != null) {
            return response()->json([
                'status' => 'Sucesso',
                'icon' => 'fas fa-bell',
                'title' => '<strong>Alerta:</strong>',
                'message' => 'Produto não pode ser excluido, contem dependências!',
                'type' => 'info'
            ]);
        }

        $values->forceDelete();

        return notifyForceDelete();
    } 

    // RESTORA UM REGISTRO DO DATATABLES
    public function restore(Request $request)
    {
        if(Gate::denies('produto-restore')){
            return notifyAjax403();
        }

        $values = Produto::withTrashed()->find($request->id);

        if (empty($values)) {
            return notifyAjaxNotFound();
        }

        $values->restore();

        return notifyRestore();
    }

    // TELA DE RELATORIO DE ESTOQUE MINIMO 
    public function estoqueminimo()
    { 
        if(Gate::denies('produto-minimo')){
            abort(403,"Não autorizado!");
        }

        $produtos = Produto::minimo();
        return view('produto.estoqueminimo', compact("produtos"));
    }

    // SELECT2 UNIDADE - infounidade
    public function infounidade(Request $request)
    {
        $values = Unidade::select('id as id', 'descricao as text')->where('descricao','like', '%'.$request->q.'%')->limit(20)->get();
        return $values;
    }

    // DATA PARA O DATATABLES
    public function data()
    {
        $values = Produto::withTrashed()->get();

        return Datatables::of($values)
        ->addColumn('descricao', function($values){
            return $values->descricao;
        })
        ->addColumn('valorvenda', function($values){
            return 'R$ '.number_format($values->valorvenda, 2, ',', '.');
        })
        ->addColumn('minimo', function($values){
            return number_format($values->minimo, 2, ',', '.').' '.Unidade::withTrashed()->find($values->unidade_id)->sigla;
        })
        ->addColumn('restante', function($values){
            $restante = 0;
            foreach ($values->estoque as $value) {
                $restante += $value->restante;
            }
            return number_format($restante, 2, ',', '.').' '.Unidade::withTrashed()->find($values->unidade_id)->sigla;
        })
        ->addColumn('action', function($values){
            $concatbutton = '';
            if(!(Gate::denies('produto-show'))){
                $concatbutton .= '
                <a  class="btn btn-outline-dark btn-sm btnshow mt-1" data-toggle="tooltip" title="Mostrar" href="#" id="'.$values->id.'"><i class="fas fa-eye"></i></a>';
            }
            if(!(Gate::denies('produto-edit'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a  class="btn btn-info btn-sm edit mt-1" data-toggle="tooltip" title="Editar" href="'.route('produto.edit',$values).'"><i class="fas fa-pencil-alt"></i></a>';
                }
            }
            if(!(Gate::denies('produto-delete'))){
                if ($values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-danger btn-sm delete mt-1" data-toggle="tooltip" title="Excluir" href="#" id="'.$values->id.'"><i class="fas fa-trash-alt"></i></a>';
                }
            }
            if(!(Gate::denies('produto-restore'))){
                if (!$values->deleted_at == null) {
                    $concatbutton .= '
                    <a class="btn btn-outline-success btn-sm restore mt-1" data-toggle="tooltip" title="Restaurar" href="#" id="'.$values->id.'" ><i class="fas fa-recycle"></i></a>';
                }
            }
            if(!(Gate::denies('produto-del-perm'))){
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
        return view('produto.relatorio.relatorio');
    }

    // GERA RELATORIO
    public function relatoriostore(Request $request)
    {
        $datetime = date('YmdHis'); 
        $data = date('d/m/Y H:i');

        // GERAR PDF
        if ($request->exportacao == 2) {
            if ($request->relatorio == 1) {
                if ($request->status == 1) {
                    $title = "RELATÓRIO DE PRODUTOS ATIVOS";
                    $produtos = DB::select("SELECT p.id AS pro_id, p.descricao AS pro_des, p.minimo AS pro_min, p.valorvenda AS pro_ven, u.sigla AS uni_sig, COALESCE(SUM(e.restante), 0.0) AS res, COALESCE(SUM(e.restante * e.valorcusto), 0.0) AS cus FROM produtos AS p JOIN unidades AS u ON u.id = p.unidade_id LEFT JOIN estoques AS e ON p.id = e.produto_id WHERE p.deleted_at IS NULL GROUP BY p.id");
                    $count = count($produtos);
                } elseif ($request->status == 2){
                    $title = "RELATÓRIO DE PRODUTOS INATIVOS";
                    $produtos = DB::select("SELECT p.id AS pro_id, p.descricao AS pro_des, p.minimo AS pro_min, p.valorvenda AS pro_ven, u.sigla AS uni_sig, COALESCE(SUM(e.restante), 0.0) AS res, COALESCE(SUM(e.restante * e.valorcusto), 0.0) AS cus FROM produtos AS p JOIN unidades AS u ON u.id = p.unidade_id LEFT JOIN estoques AS e ON p.id = e.produto_id WHERE p.deleted_at IS NOT NULL GROUP BY p.id");
                    $count = count($produtos);
                } else {
                    $title = "RELATÓRIO DE TODOS OS PRODUTOS";
                    $produtos = DB::select("SELECT p.id AS pro_id, p.descricao AS pro_des, p.minimo AS pro_min, p.valorvenda AS pro_ven, u.sigla AS uni_sig, COALESCE(SUM(e.restante), 0.0) AS res, COALESCE(SUM(e.restante * e.valorcusto), 0.0) AS cus FROM produtos AS p JOIN unidades AS u ON u.id = p.unidade_id LEFT JOIN estoques AS e ON p.id = e.produto_id GROUP BY p.id");
                    $count = count($produtos);
                }
                $pdf = PDF::loadview('produto.relatorio.produto', compact('produtos', 'title', 'data', 'count'));
                return $pdf->download($datetime.'.pdf');
            } else{
                if ($request->status == 1) {
                    $title = "RELATÓRIO ESTOQUE MÍNIMO DE PRODUTOS ATIVOS";
                    $produtos = Produto::minimo();
                    $count = count($produtos);
                } elseif ($request->status == 2){
                    $title = "RELATÓRIO ESTOQUE MÍNIMO DE PRODUTOS INATIVOS";
                    $produtos = DB::select("SELECT produtos.id, produtos.descricao, produtos.minimo, unidades.sigla, COALESCE(SUM(estoques.restante), 0) AS res FROM produtos JOIN unidades ON unidades.id = produtos.unidade_id LEFT JOIN estoques ON produtos.id = estoques.produto_id WHERE produtos.deleted_at IS NOT NULL GROUP BY produtos.id HAVING produtos.minimo >= res");
                    $count = count($produtos);
                } else {
                    $title = "RELATÓRIO ESTOQUE MÍNIMO DE PRODUTOS PRODUTOS";
                    $produtos = DB::select("SELECT produtos.id, produtos.descricao, produtos.minimo, unidades.sigla, COALESCE(SUM(estoques.restante), 0) AS res FROM produtos JOIN unidades ON unidades.id = produtos.unidade_id LEFT JOIN estoques ON produtos.id = estoques.produto_id GROUP BY produtos.id HAVING produtos.minimo >= res");
                    $count = count($produtos);
                }
                $pdf = PDF::loadview('produto.relatorio.estoqueminimo', compact('produtos', 'title', 'data', 'count'));
                return $pdf->download($datetime.'.pdf');               
            }
        }

        // GERAR CSV
        $status = $request->status;
        $relatorio = $request->relatorio;
        if ($request->exportacao == 1) {
            return Excel::download(new ProdutoExport($status, $relatorio), $datetime.'.csv');
        }
    }

}
