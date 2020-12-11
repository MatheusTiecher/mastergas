<table>
    <thead>
        <tr>
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
        @foreach($caixas as $caixa)
        <tr>
            <td>{{$caixa->id}}</td>
            <td>{{$caixa->created_at}}</td>
            <td>{{$caixa->user->name}}</td>
            <td>R$ {{number_format($caixa->inicial, 2, ',', '.')}}</td>
            <td>R$ {{number_format($caixa->entrada, 2, ',', '.')}}</td>
            <td>R$ {{number_format($caixa->saida, 2, ',', '.')}}</td>
            <td>R$ {{number_format($caixa->inicial + $caixa->entrada - $caixa->saida, 2, ',', '.')}}</td>
            <td>{{date('d/m/Y', strtotime($caixa->created_at))}}</td>
        </tr>
        @endforeach
    </tbody>
</table>