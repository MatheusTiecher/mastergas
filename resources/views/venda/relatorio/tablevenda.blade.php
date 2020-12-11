<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>USUÁRIO</th>
			<th>CLIENTE</th>
			<th>DATA CRIAÇÃO</th>
			<th>SUBTOTAL</th>
			<th>FRETE</th>
			<th>DESCONTO</th>
			<th>TOTAL</th>
			<th>FORMA PAGAMENTO</th>
			<th>STATUS</th>
			<th>DATA FINALIZADA</th>
			<th>FORMA ENTREGA</th>
			<th>STATUS ENTREGA</th>
			<th>DATA ENTREGA</th>
			<th>ENTREGADOR</th>
			<th>DATA AGENDADA</th>
			<th>CARGA</th>
			<th>ROTA</th>
		</tr>
	</thead>
	<tbody>
		@foreach($vendas as $venda)
		<tr>
			<td>{{$venda->id}}</td>
			<td>{{$venda->user->name}}</td>
			<td>{{$venda->cliente->nomerazao}}</td>
			<td>{{date('d/m/Y H:i', strtotime($venda->created_at))}}</td>
			<?php 
			$subtotal = 0.00;
			foreach ($venda->vendaitem as $key => $value) {
				$subtotal += $value->valorvenda * $value->quantidade;
			}
			$total = $subtotal + $venda->frete - $venda->desconto;
			?>
			<td>R$ {{number_format($subtotal, 2, ',', '.')}}</td>
			<td>R$ {{number_format($venda->frete, 2, ',', '.')}}</td>
			<td>R$ {{number_format($venda->desconto, 2, ',', '.')}}</td>
			<td>R$ {{number_format($total, 2, ',', '.')}}</td>
			@if($venda->forma_pagamento_id != null)
			<td>{{$venda->formapagamento->descricao}}</td>
			@else
			<td>Não definido</td>
			@endif
			<td>{{$statusvenda[$venda->status]}}</td>
			@if($venda->finalizavenda != null)
			<td>{{$venda->finalizavenda}}</td>
			@else
			<td>Não finalizado</td>
			@endif
			@if(isset($venda->entrega))
			@if($venda->entrega->forma_entrega == 1)
			<td>Retirada na loja</td>
			<td>{{$statusentrega[$venda->status]}}</td>
			<td>{{$venda->entrega->dataentrega}}</td>
			<td>Retirada na loja</td>
			<td>Retirada na loja</td>
			@else
			<td>Entrega Agendada</td>
			<td>{{$statusentrega[$venda->status]}}</td>
			@if($venda->entrega->dataentrega == null)
			<td>Não entregue</td>
			@else
			<td>{{$venda->entrega->dataentrega}}</td>
			@endif
			<td>{{$ocorrenciaentrega = $venda->entrega->ocorrenciaentrega()->orderby('id', 'desc')->first()->user->name}}</td>
			<td>{{$ocorrenciaentrega = $venda->entrega->ocorrenciaentrega()->orderby('id', 'desc')->first()->dataagendada}}</td>
			@endif
			@else
			<td>Não definido</td>
			<td>Não definido</td>
			<td>Não definido</td>
			<td>Não definido</td>
			<td>Não definido</td>
			@endif
			@if($venda->carga != null)
			<td>{{$venda->carga->id}}</td>
			<td>{{$venda->rota->nome}}</td>
			@else
			<td>Não definido</td>
			<td>Não definido</td>
			@endif
		</tr>
		@endforeach
	</tbody>
</table>