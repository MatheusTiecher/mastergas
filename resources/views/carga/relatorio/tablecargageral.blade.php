<table>
    @foreach($cargas as $carga)
    <tr>
        <th>#</th>
        <th>ID</th>
        <th>ENTREGADOR</th>
        <th>DATA CRIAÇÃO</th>
        <th>TOTAL</th>
        <th>STATUS</th>
    </tr>
    <tr>
        <td>Carga</td>
        <td>{{$carga->id}}</td>
        <td>{{$carga->user->name}}</td>
        <td>{{date('d/m/Y H:i', strtotime($carga->created_at))}}</td>
        <?php 
        $subtotal = 0.00;
        $total = 0.00;
        foreach ($carga->venda as $key => $venda) {
            foreach ($venda->vendaitem as $key => $value) {
                if($value->status != 4) {
                    $subtotal += $value->valorvenda * $value->quantidade;
                }
            }
            $total += $subtotal - $venda->desconto;
        }
        ?>
        <td>R$ {{number_format($total, 2, ',', '.')}}</td>
        <td>{{$statuscarga[$carga->status]}}</td>
    </tr>
    <tr>
        <th>#</th>
        <th>ID</th>
        <th>USUÁRIO</th>
        <th>DATA CRIAÇÃO</th>
        <th>SUBTOTAL</th>
        <th>DESCONTO</th>
        <th>TOTAL</th>
        <th>STATUS</th>
        <th>DATA FINALIZADA</th>
    </tr>
    @foreach($carga->venda as $venda)
    <tr>
        <td>Venda</td>
        <td>{{$venda->id}}</td>
        <td>{{$venda->user->name}}</td>
        <td>{{date('d/m/Y H:i', strtotime($venda->created_at))}}</td>
        <?php 
        $subtotal = 0.00;
        $total = 0.00;
        foreach ($venda->vendaitem as $key => $value) {
            if($value->status != 4) {
                $subtotal += $value->valorvenda * $value->quantidade;
            }
        }
        $total = $subtotal + $venda->frete - $venda->desconto;
        ?>
        <td>R$ {{number_format($subtotal, 2, ',', '.')}}</td>
        <td>R$ {{number_format($venda->desconto, 2, ',', '.')}}</td>
        <td>R$ {{number_format($total, 2, ',', '.')}}</td>
        <td>{{$statusvenda[$venda->status]}}</td>
        @if($venda->finalizavenda != null)
        <td>{{$venda->finalizavenda}}</td>
        @else
        <td>Não finalizado</td>
        @endif
    </tr>
    @endforeach
    @endforeach
</table>