<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>USUÁRIO</th>
			<th>CLIENTE</th>
			<th>DATA CRIAÇÃO</th>
		</tr>
	</thead>
	<tbody>
		@foreach($vendas as $venda)
		<tr>
			<td>{{$venda->id}}</td>
			<td>{{$venda->user->name}}</td>
			<td>{{$venda->cliente->nomerazao}}</td>
			<td>{{date('d/m/Y H:i', strtotime($venda->created_at))}}</td>
		</tr>
		@endforeach
	</tbody>
</table>