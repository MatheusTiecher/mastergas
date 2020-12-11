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
        @foreach($fornecedores as $fornecedor)
        <tr>
            <td>{{ $fornecedor->nomerazao }}</td>
            <td>{{ $fornecedor->fantasia }}</td>
            <td>{{ $fornecedor->cpfcnpj }}</td>
            <td>{{ $fornecedor->rgie }}</td>
            <td>{{ $fornecedor->email }}</td>
            <td>{{ $fornecedor->celular }}</td>
            <td>{{ $fornecedor->telefone }}</td>
            <td>{{ $fornecedor->tipo }}</td>
            <td>{{ $fornecedor->deleted_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>