@extends('layouts.app')

@section('content')
<form action="{{route('venda.storefinal', $venda)}}" method="POST">
	<div class="container-fluid">	
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card card-frame">
					<div class=" card-body">
						<h1>Venda #{{$venda->id}}</h1>
						<hr class="my-4">
						<div class="row">
							@csrf
							@include('venda._form3')
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid">	
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card card-frame">
					<div class=" card-body">
						<div class="table-responsive mt-3 mt-4">
							<div class="accordion" id="accordionExample">
								<div class="card">
									<div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										<h5 class="mb-0">Itens da venda</h5>
									</div>
									<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
										<div class="table-responsive mt-3 mt-4">
											<table class="table table-sm table-striped ">
												<thead>
													<tr>
														<th>Produto</th>
														<th>Quantidade</th>
														<th>Preço</th>
														<th>Total</th>
													</tr>
												</thead>
												@foreach($venda->vendaitem as $i)
												<tr>
													<td>{{$i->produto->descricao}}</td>
													<td>{{number_format($i->quantidade, 2, ',', '.')}} {{$i->produto->unidade->sigla}}</td>
													<td>R$ {{number_format($i->valorvenda, 2, ',', '.')}}</td>
													<td>R$ {{number_format($i->valorvenda * $i->quantidade, 2, ',', '.')}}</td>
												</tr>
												@endforeach
											</table>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid">	
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card card-frame">
					<div class=" card-body">
						<div class="table-responsive mt-3 mt-4">
							<hr class="my-2">
							<div class="col-lg-12" style="text-align: right;">
								<p>Subtotal R${{number_format($total, 2, ',', '.')}}</p>
								<p id="descontorecibo" value="descontorecibo"></p>
								<p id="freterecibo" value="freterecibo"></p>
								<p id="montante" value="montante"></p>
							</div>
							<hr class="my-4">
							<div class="col-lg-12" style="text-align: right;">
								<a type="button" href="{{route('venda.vendaitem', $venda)}}" class="btn btn-secondary mb-2" data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i> Voltar</a>
								@can('venda-finalizar')
								<button type="submit" class="btn btn-outline-default mb-2" data-toggle="tooltip" title="Finalizar Venda"><i class="fas fa-shopping-cart"></i> Finalizar Venda</button>
								@endcan
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
	var montante = {{($total + $venda->frete) - $venda->desconto}};
	var frete = {{$venda->frete}};
	var desconto = {{$venda->desconto}};
	document.getElementById('montante').innerText = 'Total R$' +montante.toLocaleString('pt-br', {minimumFractionDigits: 2});
	document.getElementById('freterecibo').innerText = 'Frete R$' +frete.toLocaleString('pt-br', {minimumFractionDigits: 2});
	document.getElementById('descontorecibo').innerText = 'Desconto R$' +desconto.toLocaleString('pt-br', {minimumFractionDigits: 2});


	$('#frete').keyup(function () {
		frete = document.getElementById('frete').value;
		frete = parseFloat(frete.replace(/\./gi,'').replace(/,/gi,'.'));

		desconto = document.getElementById('desconto').value;
		desconto = parseFloat(desconto.replace(/\./gi,'').replace(/,/gi,'.'));

		document.getElementById('freterecibo').innerText = 'Frete R$' +frete.toLocaleString('pt-br', {minimumFractionDigits: 2});
		document.getElementById('descontorecibo').innerText = 'Desconto R$' +desconto.toLocaleString('pt-br', {minimumFractionDigits: 2});

		montante = (({{$total}} + frete) - desconto);

		document.getElementById('montante').innerText = 'Total R$' +montante.toLocaleString('pt-br', {minimumFractionDigits: 2});
	});

	$('#desconto').keyup(function () {
		frete = document.getElementById('frete').value;
		frete = parseFloat(frete.replace(/\./gi,'').replace(/,/gi,'.'));

		desconto = document.getElementById('desconto').value;
		desconto = parseFloat(desconto.replace(/\./gi,'').replace(/,/gi,'.'));

		document.getElementById('descontorecibo').innerText = 'Desconto R$' +desconto.toLocaleString('pt-br', {minimumFractionDigits: 2});
		document.getElementById('freterecibo').innerText = 'Frete R$' +frete.toLocaleString('pt-br', {minimumFractionDigits: 2});

		montante = (({{$total}} + frete) - desconto);

		document.getElementById('montante').innerText = 'Total R$' +montante.toLocaleString('pt-br', {minimumFractionDigits: 2});
	});
</script>

@include('scripts.mask')

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

@endsection