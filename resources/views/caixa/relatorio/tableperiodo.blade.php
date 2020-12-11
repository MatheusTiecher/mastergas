<table>
    @foreach($caixas as $caixa)
    <thead>
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>ABERTURA</th>
            <th>USUÁRIO</th>
            <th>INICIAL</th>
            <th>ENTRADA</th>
            <th>SAÍDA</th>
            <th>TOTAL</th>
            <th>DATA</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Caixa</td>
            <td>{{$caixa->id}}</td>
            <td>{{$caixa->created_at}}</td>
            <td>{{$caixa->user->name}}</td>
            <td>R$ {{number_format($caixa->inicial, 2, ',', '.')}}</td>
            <td>R$ {{number_format($caixa->entrada, 2, ',', '.')}}</td>
            <td>R$ {{number_format($caixa->saida, 2, ',', '.')}}</td>
            <td>R$ {{number_format($caixa->inicial + $caixa->entrada - $caixa->saida, 2, ',', '.')}}</td>
            <td>{{date('d/m/Y', strtotime($caixa->created_at))}}</td>
        </tr>
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>ADICIONADO</th>
            <th>TIPO</th>
            <th>DESCRIÇÃO</th>
            <th>VALOR</th>
            <th>VENDA</th>
            <th>USUÁRIO</th>
            <th>DATA</th>
        </tr>
        @foreach($caixa->lancamento as $lacamento)
        @if(isset($user))
        @if($user == $lacamento->user_id)
        <tr>
            <td>Lançamentos</td>
            <td>{{$lacamento->id}}</td>
            <td>{{$lacamento->created_at}}</td>
            @if($lacamento->tipo_lancamento == 1)
            <td>entrada</td>
            @else
            <td>saída</td>
            @endif
            <td>{{$lacamento->descricao}}</td>
            <td>R$ {{number_format($lacamento->valor, 2, ',', '.')}}</td>
            <td>{{$lacamento->venda_id}}</td>
            <td>{{$lacamento->user->name}}</td>
            <td>{{date('d/m/Y', strtotime($lacamento->created_at))}}</td>
        </tr>
        @endif
        @else
        <tr>
            <td>Lançamentos</td>
            <td>{{$lacamento->id}}</td>
            <td>{{$lacamento->created_at}}</td>
            @if($lacamento->tipo_lancamento == 1)
            <td>entrada</td>
            @else
            <td>saída</td>
            @endif
            <td>{{$lacamento->descricao}}</td>
            <td>R$ {{number_format($lacamento->valor, 2, ',', '.')}}</td>
            <td>{{$lacamento->venda_id}}</td>
            <td>{{$lacamento->user->name}}</td>
            <td>{{date('d/m/Y', strtotime($lacamento->created_at))}}</td>
        </tr>
        @endif
        @endforeach
        @endforeach
    </tbody>
</table>