<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>ENTREGADOR</th>
			<th>DATA CRIAÇÃO</th>
			<th>TOTAL</th>
			<th>STATUS</th>
		</tr>
	</thead>
	<tbody>
		@foreach($cargas as $carga)
		<tr>
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
		@endforeach
	</tbody>
</table>