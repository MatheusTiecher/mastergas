<div class="card col-xs-12 col-md-12 col-lg-4">
	<div class="card-body">
		<h5 class="card-title"><i class="fas fa-user"></i> Cliente</h5>
		@if(isset($venda->cliente))
		<p class="card-text">
			{{$venda->cliente->nomerazao}}
			<br>{{$venda->cliente->cpfcnpj}}
		</p>
		@endif
		<hr class="mb-4 mt-2">
		<h5 class="card-title"><i class="fas fa-credit-card"></i> Pagamento</h5>
		@if(isset($venda->formapagamento->descricao))
		<p class="card-text">
			{{$venda->formapagamento->descricao}}
		</p>
		@endif
	</div>
</div>
<div class="card col-xs-12 col-md-12 col-lg-4">
	<div class="card-body">
		<h5 class="card-title"><i class="fas fa-wallet"></i> Total</h5>
		<p class="card-text">
			Subtotal R$ {{number_format($total, 2, ',', '.')}}
			<br>Frete R$ {{number_format($venda->frete, 2, ',', '.')}}  
			<br>Desconto R$ {{number_format($venda->desconto, 2, ',', '.')}} 
			<hr class="mb-2 mt-0">
			Total R$ {{number_format($total + $venda->frete - $venda->desconto, 2, ',', '.')}} 
		</p>
	</div>
</div>

@if(!empty($venda->carga))
<div class="card col-xs-12 col-md-12 col-lg-4">
	<div class="card-body">
		<h5 class="card-title"><i class="fas fa-truck"></i> Carga</h5>
		<p class="card-text">
			<b>Entregador:</b> {{$venda->carga->user->name}}
			<br><b>Data:</b> {{date('d/m/Y : H:i', strtotime($venda->carga->created_at))}}
			<br><b>Rota:</b> {{$venda->rota->nome}}
		</p>
	</div>
</div>
@else
<div class="card col-xs-12 col-md-12 col-lg-4">
	<div class="card-body">
		<h5 class="card-title"><i class="fas fa-home"></i> Endereço/Entrega</h5>
		@if(isset($venda->entrega))
		@if($venda->entrega->forma_entrega == 1)
		<p class="card-text">
			Pedido retirado em {{$venda->entrega->dataentrega}}
		</p>
		@endif
		@if($venda->entrega->forma_entrega == 2)
		<p class="card-text">
			{{$ocorrencia->endereco->rua}}, {{$ocorrencia->endereco->numero}} 
			<br>{{$ocorrencia->endereco->bairro}} - {{$ocorrencia->endereco->cidade->nome}}, {{$ocorrencia->endereco->cidade->estado->sigla}}
		</p>
		@if($venda->entrega->status != 0)
		<p class="card-text">
			<hr class="mb-2 mt-0">
			@if(isset($venda->entrega->dataentrega))
			Entregue em {{$venda->entrega->dataentrega}}
			@else
			Data entrega pedido {{$ocorrencia->dataagendada}}
			@endif
		</p>
		<a href="{{route('entrega.gerenciar', $venda->entrega)}}" class="card-link">Gerenciar Entrega</a>
		@else
		<p class="card-text ">
			<hr class="mb-2 mt-0">
			<h2 class="card-text text-danger">Entrega Cancelada</h2>
			<a href="{{route('entrega.gerenciar', $venda->entrega)}}" class="card-link">Gerenciar Entrega</a>
		</p>
		@endif
		@endif
		@endif
	</div>
</div>
@endif