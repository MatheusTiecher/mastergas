<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>FORNECEDOR</th>
            <th>PRODUTO</th>
            <th>UNIDADE</th>
            <th>CUSTO</th>
            <th>TOTAL</th>
            <th>RESTANTE</th>
            <th>LANÇAMENTO</th>
        </tr>
    </thead>
    <tbody>
        @foreach($estoques as $estoque)
        <tr>
            <td>{{ $estoque->id }}</td>
            <td>{{ $estoque->fornecedor->nomerazao }}</td>
            <td>{{ $estoque->produto->descricao }}</td>
            <td>{{ $estoque->produto->unidade->sigla }} </td>
            <td>{{ $estoque->valorcusto }}</td>
            <td>{{ $estoque->total }} </td>
            <td>{{ $estoque->restante }} </td>
            <td>{{ $estoque->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>