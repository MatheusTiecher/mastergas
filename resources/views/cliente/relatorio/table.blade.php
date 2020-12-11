<table>
    <thead>
        <tr>
            <th>Name/Razão</th>
            <th>Nome Fantasia</th>
            <th>CPF/CNPJ</th>
            <th>RG/EI</th>
            <th>Email</th>
            <th>Celular</th>
            <th>Telefone</th>
            <th>Tipo</th>
            <th>Desativado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $cliente)
        <tr>
            <td>{{ $cliente->nomerazao }}</td>
            <td>{{ $cliente->fantasia }}</td>
            <td>{{ $cliente->cpfcnpj }}</td>
            <td>{{ $cliente->rgie }}</td>
            <td>{{ $cliente->email }}</td>
            <td>{{ $cliente->celular }}</td>
            <td>{{ $cliente->telefone }}</td>
            <td>{{ $cliente->tipo }}</td>
            <td>{{ $cliente->deleted_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>