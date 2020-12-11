<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Cidade</th>
            <th>Observação</th>
            <th>Desativado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rotas as $rota)
        <tr>
            <td>{{ $rota->nome }}</td>
            <td>{{ $rota->cidade->nome}}-{{$rota->cidade->estado->sigla }}</td>
            <td>{{ $rota->descricao }}</td>
            <td>{{ $rota->deleted_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>