<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\User;
use App\Models\Lancamento;

use App\Http\Requests\LancamentoRequest;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Gate;

use PDF;
use Excel;
use App\Exports\CaixaExport;

class CaixaController extends Controller
{
    private $date;

    public function __construct()
    {
        $this->middleware('auth');

        $this->date = date('Y-m-d');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // CAIXA GERAL
    public function index()
    {
        if(Gate::denies('caixa-menu')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();

        return view('caixa.index', compact('caixa'));
    }

    // SEU CAIXA
    public function indexuser()
    {
        if(Gate::denies('caixa-user-caixa')){
            abort(403,"Não autorizado!");
        }

        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();

        if(empty($caixa)){
            return view('caixa.indexuser', compact('caixa'));
        }

        $lancamento = $caixa->lancamento->Where('user_id', '=', Auth::user()->id);

        $entrada = 0.00;
        $saida = 0.00;
        foreach ($lancamento as $key => $value) {
            if ($value->tipo_lancamento == 1) {
                $entrada += $value->valor;
            }else{
                $saida += $value->valor;
            }
        }

        return view('caixa.indexuser', compact('entrada', 'saida', 'caixa', 'lancamento'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('caixa-abrir')){
            abort(403,"Não autorizado!");
        }

        // VERIFICA SE CAIXA ESTÁ ABERTO
        $aberto = Caixa::Where('created_at', '>=', $this->date)->first();

        if ($aberto != null) {
            $exnotify[] = notifyInfo('Caixa Ja está aberto');
            return redirect()->route('caixa.indexuser')->with('notify', $exnotify);
        }

        // CONSULTA PARA VER QUANDO FOI ABERTO A ULTIMA VEZ O CAIXA
        $caixa = Caixa::Where('created_at', '<', $this->date)->orderBy('created_at', 'desc')->first();

        // CRIA UM NOVO CAIXA
        $newcaixa = Caixa::create([
            'entrada' => 0.00,
            'saida' => 0.00,
            'user_id' => Auth::user()->id,
        ]);

        // ADICIONAR VALOR INICIAL CASO JA TENHA
        if ($caixa != null) {
            $newcaixa->inicial = ($caixa->entrada + $caixa->inicial) - $caixa->saida ;
        } else {
            $newcaixa->inicial = 0.00;
        }

        $newcaixa->update();

        $exnotify[] = notifySuccess('Caixa aberto com sucesso!');
        return redirect()->route('caixa.indexuser')->with('notify', $exnotify);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Caixa  $caixa
     * @return \Illuminate\Http\Response
     */
    public function show(Caixa $caixa)
    {
        abort(404,"Not Found");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Caixa  $caixa
     * @return \Illuminate\Http\Response
     */
    public function edit(Caixa $caixa)
    {
        abort(404,"Not Found");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Caixa  $caixa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  Caixa $caixa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Caixa  $caixa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Caixa $caixa)
    {
        //
    }

    // ENTRADA CAIXA
    public function entrada(LancamentoRequest $request, Caixa $caixa)
    {
        if(Gate::denies('caixa-entrada')){
            abort(403,"Não autorizado!");
        }

        $aberto = Caixa::Where('created_at', '>=', $this->date)->first();

        if ($aberto != $caixa) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return redirect()->route('caixa.indexuser')->with('notify', $exnotify);
        }

        $valor = floatval(str_replace (',', '.', str_replace ('.', '', $request->valor)));
        $request->valor = $valor;
        $request->request->add(['tipo_lancamento' => 1]);
        $request->request->add(['user_id' => Auth::user()->id]);
        $lancamento = $caixa->lancamento()->create($request->all());

        $caixa->entrada += $request->valor;
        $caixa->update();

        $exnotify[] = notifySuccess('Valor de entrada adicionado com sucesso');
        return redirect()->route('caixa.indexuser')->with('notify', $exnotify);
    }

    // SAIDA CAIXA GERAL
    public function saida(LancamentoRequest $request, Caixa $caixa)
    {
        if(Gate::denies('caixa-saida-geral')){
            abort(403,"Não autorizado!");
        }

        $aberto = Caixa::Where('created_at', '>=', $this->date)->first();

        if ($aberto != $caixa) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return redirect()->route('caixa.indexuser')->with('notify', $exnotify);
        }

        $request->valor = floatval(str_replace (',', '.', str_replace ('.', '', $request->valor)));

        if ((($caixa->entrada + $caixa->inicial) - $caixa->saida) < $request->valor) {
            $exnotify[] = notifyWarning('Valor em caixa insuficiente para retirada!');
            return redirect()->route('caixa.indexuser')->with('notify', $exnotify);
        }

        $request->request->add(['tipo_lancamento' => 2]);
        $request->request->add(['user_id' => Auth::user()->id]);
        $lancamento = $caixa->lancamento()->create($request->all());

        $caixa->saida += $request->valor;
        $caixa->update();

        $exnotify[] = notifySuccess('Valor de saída adicionado com sucesso');
        return redirect()->route('caixa.indexuser')->with('notify', $exnotify);
    }

    // SAIDA CAIXA USER
    public function saidauser(LancamentoRequest $request, Caixa $caixa)
    {
        if(Gate::denies('caixa-saida')){
            abort(403,"Não autorizado!");
        }

        $aberto = Caixa::Where('created_at', '>=', $this->date)->first();

        if ($aberto != $caixa) {
            $exnotify[] = notifyDanger('Opss... Ocorreu um erro inesperado');
            return redirect()->route('caixa.indexuser')->with('notify', $exnotify);
        }

        $request->valor = str_replace (',', '.', str_replace ('.', '', $request->valor));

        if ((($caixa->entrada + $caixa->inicial) - $caixa->saida) < $request->valor) {
            $exnotify[] = notifyWarning('Valor em caixa insuficiente para retirada!');
            return redirect()->route('caixa.indexuser')->with('notify', $exnotify);
        }

        $saidauser = $caixa->lancamento()->Where('user_id', '>=', Auth::user()->id)->get();

        $usersaldo = 0.00;
        foreach ($saidauser as $key => $value) {
            $usersaldo += $value->valor;
        }

        if ($usersaldo < $request->valor) {
            $exnotify[] = notifyWarning('Valor em caixa insuficiente para retirada!');
            return redirect()->route('caixa.indexuser')->with('notify', $exnotify);
        }

        $request->request->add(['tipo_lancamento' => 2]);
        $request->request->add(['user_id' => Auth::user()->id]);
        $lancamento = $caixa->lancamento()->create($request->all());

        $caixa->saida += $request->valor;
        $caixa->update();

        $exnotify[] = notifySuccess('Valor de saída adicionado com sucesso');
        return redirect()->route('caixa.indexuser')->with('notify', $exnotify);
    }

    // RELATORIOS
    public function relatorio()
    {
        $users = User::all();

        return view('caixa.relatorio.relatorio', compact("users"));
    }

    // GERA RELATORIO
    public function relatoriostore(Request $request)
    {
        set_time_limit(300);

        $datetime = date('YmdHis'); 
        $data = date('d/m/Y H:i');

        $datainicio = $request->datainicio;
        $datainicio = (str_replace( '/', '-', $datainicio));
        $datainicio = date('Y-m-d', strtotime($datainicio));
        $datafim = $request->datafim;
        $datafim = (str_replace( '/', '-', $datafim));
        $datafim = date('Y-m-d', strtotime($datafim));

        $user = $request->user;

        // VALIDACAO DATA DIFERNTE - SEGUNDO RELATORIO
        if ($request->relatorio == 2) {
            if ($datainicio != $datafim) {
                $exnotify[] = notifyWarning('Movimentação de caixa por dia com totais. <br> Data início - Data fim, não possuem o mesmo valor!');
                return redirect()->back()->with('notify', $exnotify);
            }
        }

        // GERAR PDF
        if ($request->exportacao == 2) {
            if ($request->relatorio == 1) {
                $exnotify[] = notifyDanger('Exportação inválida');
                return redirect()->back()->with('notify', $exnotify);
            }
            if ($request->relatorio == 2) {
                $caixas = Caixa::whereDate('created_at', '=', $datainicio)->get();
                $title = "RELATÓRIO CAIXA DIÁRIO";
                $count = count($caixas);
                
                if ($user == 0) {
                    $users = User::withTrashed()->get();
                    $pdf = PDF::loadview('caixa.relatorio.caixadiario', compact('caixas', 'title', 'data', 'count', 'users', 'datainicio'));
                } else {
                    $users = User::withTrashed()->where('id', '=', $user)->get();
                    $pdf = PDF::loadview('caixa.relatorio.caixadiario', compact('caixas', 'title', 'data', 'count', 'users', 'datainicio'));
                }

                return $pdf->download($datetime.'.pdf');
            }
            if ($request->relatorio == 3) {
                $caixas = Caixa::whereBetween('created_at', [$datainicio, $datafim])->get();
                $title = "RELATÓRIO FECHAMENTO DE CAIXA";
                $count = count($caixas);

                $pdf = PDF::loadview('caixa.relatorio.fechamento', compact('caixas', 'title', 'data', 'count', 'datainicio', 'datafim'));
                return $pdf->download($datetime.'.pdf');
            }
        }

        // GERAR CSV
        $relatorio = $request->relatorio;
        if ($request->relatorio == 2) {
            $exnotify[] = notifyDanger('Exportação inválida');
            return redirect()->back()->with('notify', $exnotify);
        }
        if ($request->exportacao == 1) {
            return Excel::download(new CaixaExport($datainicio, $datafim, $relatorio, $user), $datetime.'.csv');
        }
    }
}
