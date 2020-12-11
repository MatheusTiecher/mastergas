<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Venda;
use App\Models\Caixa;
use App\Models\Lancamento;
use App\Models\Entrega;
use App\Models\OcorrenciaEntrega;
use App\Models\Produto;
use App\Models\Estoque;
use App\User;
use Illuminate\Support\Facades\Gate;

use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->date = date('Y-m-d');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // SEU CAIXA
        $valor = 0.00;
        $caixa = Caixa::Where('created_at', '>=', $this->date)->first();
        if(!empty($caixa)){
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
            $valor = ($entrada - $saida);
        }

        // ENTREGAS AGENDADAS DE HOJE
        $entregas = Venda::join('entregas', 'vendas.id', '=', 'entregas.venda_id')
        ->join('ocorrencia_entregas', 'entregas.id', '=', 'ocorrencia_entregas.entrega_id')
        ->where('ocorrencia_entregas.status', 1)->whereDate('ocorrencia_entregas.dataagendada', '=', $this->date)->get();
        $entregas = (count($entregas));

        // ESTOQUE MINIMO
        $minimo = Produto::minimo();
        $minimo = (count($minimo));

        $semana = [
            'Domingo',
            'Segunda',
            'Terça',
            'Quarta',
            'Quinta',
            'Sexta',
            'Sábado'
        ];

        $dt = date('Y-m-d');

        $data = [];
        $ArrSemana  = [];        
        $ArrAndamento  = [];        
        $ArrAgendado   = [];        
        $ArrFinalizado = [];        
        $ArrEstornado  = [];        

        for ($i=0; $i < 7; $i++) {
            $dt2 = date('Y-m-d', strtotime('-'.$i.' days', strtotime($dt)));
            
            $andamento = Venda::where('status', 0)->whereDate('created_at', '=', $dt2)->get();
            $agendado = Venda::where('status', 1)->whereDate('created_at', '=', $dt2)->get();
            $finalizado = Venda::where('status', 2)->whereDate('created_at', '=', $dt2)->get();
            $estornado = Venda::where('status', 3)->whereDate('created_at', '=', $dt2)->get();
            
            $ArrSemana[] = $semana[date('w', strtotime($dt2))];
            $ArrAndamento[] = count($andamento);
            $ArrAgendado[] = count($agendado);
            $ArrFinalizado[] = count($finalizado);         
            $ArrEstornado[] = count($estornado);
        }

        $data['andamento'] = $ArrAndamento;   
        $data['agendado'] = $ArrAgendado;
        $data['finalizado'] = $ArrFinalizado;
        $data['estornado'] = $ArrEstornado;
        $data['semana'] = $ArrSemana;

        $data = json_encode($data);

        return view('home', compact("minimo", "valor", "entregas", "data"));
    }
}
