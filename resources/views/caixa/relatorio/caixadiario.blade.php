<style type="text/css">  
th {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    text-align: left;
    padding: 5px;
    font-size: 15px;
    border-bottom: 1px solid #ddd;
}

h1, h3, p, div{
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
}

b {
    font-size: 18px;
}

td {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    text-align: left;
    padding: 5px;
    border-bottom: 1px solid #ddd;
}

.clearfix::after {
    content: "";
    clear: both;
    display: table;
}

.border {
    border-radius: 5px;
    border-top: 2px solid;
    border-bottom: 2px solid;
    border-left: 2px solid;
    border-right: 2px solid;
    padding-top: 5px;
    padding-left: 5px;
    padding-left: 10px;
    padding-right: 10px;
}
</style>

<div class="border" style="background: #000;">
    <div class="clearfix">
        <div style="float: left; width: 8%;" align="right">
            <img style="height: 90px; width: 55px;" src="{{ asset('icon_branco.png') }}">
        </div>
        <div style="float: left; width: 22%;" align="left"> 
            <h1 style="color: #ffff;">JB GAS</h1>
        </div>
        <div style="float: left; width: 70%;" align="center">
            <p style="font-size: 20px; color: #ffff;"><b style="font-size: 20px; color: #ffff;">Endereço: </b> Centro, Planalto - PR, 85750-000 
                <br> <b style="font-size: 20px; color: #ffff;">Contato: </b> (46) 99973-0562</p>
            </div>
        </div>
    </div>
</div>
<br>
<div class="border" style="margin-top: 5px;">
    <h3 align="center">{{$title}}</h3>
</div>
<div align="right">
    Gerado em {{$data}}
</div>
<br>
<div style="margin-top: 5px;">
    @if(count($users) == 1)
    <h4 align="center">Data {{date('d/m/Y', strtotime($datainicio))}}  - Usuário {{$users[0]->name}}</h4>
    @else
    <h4 align="center">Data {{date('d/m/Y', strtotime($datainicio))}}  - Todos os usuários</h4>
    @endif
    @if(count($caixas) > 0)
    <table style="width: 100%;">
        @foreach($caixas as $caixa)
        <tr style="background: #D3D3D3;">
            <th>DATA</th>
            <th>ABERTURA</th>
            <th>INICIAL</th>
            <th>ENTRADA</th>
            <th>SAÍDA</th>
            <th>TOTAL</th>
        </tr>
        <tbody>
            <tr>
                <td>{{date('d/m/Y', strtotime($caixa->created_at))}}</td>
                <td>{{$caixa->user->name}}</td>
                <td>R$ {{number_format($caixa->inicial, 2, ',', '.')}}</td>
                <td>R$ {{number_format($caixa->entrada, 2, ',', '.')}}</td>
                <td>R$ {{number_format($caixa->saida, 2, ',', '.')}}</td>
                <td>R$ {{number_format($caixa->inicial + $caixa->entrada - $caixa->saida, 2, ',', '.')}}</td>
            </tr>
            @foreach($users as $user)
            <?php 
            $montante = 0.00;
            $entrada = 0.00;
            $saida = 0.00;
            ?>
            @if($caixa->lancamento()->where("user_id", "=", $user->id)->first() != null)
            <tr style="background: #D3D3D3;" >
                <th>HORA</th>
                <th>VENDA</th>
                <th>USUÁRIO</th>
                <th>TIPO</th>
                <th>VALOR</th>
                <th>MONTANTE</th>
            </tr>
            @endif
            @foreach($caixa->lancamento()->where("user_id", "=", $user->id)->get() as $lacamento)
            <tr>
                <td>{{date('H:i', strtotime($lacamento->created_at))}}</td>
                @if(empty($lacamento->venda_id))
                <td>Não</td>
                @else
                <td>Sim</td>
                @endif
                <td>{{$lacamento->user->name}}</td>
                @if($lacamento->tipo_lancamento == 1)
                <td>Entrada</td>
                <td style="color: #32CD32;">R$ +{{number_format($lacamento->valor, 2, ',', '.')}}</td>
                <td>R$ {{number_format($montante+= $lacamento->valor, 2, ',', '.')}}</td>
                <?php $entrada+= $lacamento->valor ?>
                @else
                <td>Saída</td>
                <td style="color: #B22222;">R$ -{{number_format($lacamento->valor, 2, ',', '.')}}</td>
                <td>R$ {{number_format($montante-= $lacamento->valor, 2, ',', '.')}}</td>
                <?php $saida+= $lacamento->valor ?>
                @endif
            </tr>
            @endforeach
            @if($caixa->lancamento()->where("user_id", "=", $user->id)->first() != null)
            <tr>
                <td style="background: #D3D3D3;" align="right">ENTRADA</td>
                <td style="background: #D3D3D3; color: #32CD32;" align="right">R$ +{{number_format($entrada, 2, ',', '.')}}</td>
                <td style="background: #D3D3D3;" align="right">SAÍDA</td>
                <td style="background: #D3D3D3; color: #B22222;" align="right">R$ -{{number_format($saida, 2, ',', '.')}}</td>
                <td style="background: #D3D3D3;" align="right">TOTAL</td>
                <td style="background: #D3D3D3;" align="right">R$ {{number_format($montante, 2, ',', '.')}}</td>
            </tr>
            @endif
            @endforeach
            @endforeach
        </tbody>
    </table>
    @else
    <h1 align="center">Nenhum caixa encontrado!</h1>
    @endif
</div>

<script type="text/php">
    $font = $fontMetrics->getFont("Arial", "bold");
    $pdf->page_text(500, 820, "Página {PAGE_NUM}/{PAGE_COUNT}", $font, 10, array(0, 0, 0));
</script>