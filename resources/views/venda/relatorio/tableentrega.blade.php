<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>USUÁRIO</th>
            <th>CLIENTE</th>
            <th>ENTREGADOR</th>
            <th>DATA ENTREGA</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vendas as $value)
        <?php 
        $venda = App\Models\Venda::find($value->ven_id);
        $ocorrencia = App\Models\OcorrenciaEntrega::find($value->oco_id);
        ?>
        <tr>
            <td>{{$venda->id}}</td>
            <td>{{$venda->user->name}}</td>
            <td>{{$venda->cliente->nomerazao}}</td>
            <td>{{$ocorrencia->user->name}}</td>
            <td>{{$ocorrencia->dataagendada}}</td>
        </tr>
        @endforeach
    </tbody>
</table>