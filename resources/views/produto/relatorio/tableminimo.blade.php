<table>
    <thead>
        <tr>
            <th>DESCRIÇÃO</th>
            <th>MÍNIMO</th>
            <th>RESTANTE</th>
        </tr>
    </thead>
    <tbody>
        @foreach($produtos as $produto)
        <tr>
            <td>{{$produto->descricao}}</td>
            <td>{{$produto->minimo}} {{$produto->sigla}}</td>
            <td>{{$produto->res}}</td>
        </tr>
        @endforeach
    </tbody>
</table>