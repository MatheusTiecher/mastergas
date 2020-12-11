@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Gerar venda - Carga #{{$carga->id}}</h1>
					<hr class="my-4">
					<form action="{{route('carga.storevenda', $carga)}}" method="POST">
						<div class="row">
							@csrf
							@include('carga._form2')
						</div>
						<hr class="my-4">
						<div class="col-lg-12" style="text-align: right;">
							<a type="button" href="{{route('carga.detalhar', $carga)}}" class="btn btn-secondary mb-2" data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i> Voltar</a>
							<button type="submit" class="btn btn-default mb-2" data-toggle="tooltip" title="Gerar Venda"><i class="far fa-list-alt"></i> Gerar Venda</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@include('scripts.mask')

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

@include('css.select2')
@include('scripts.select2')

<!-- FAZER SOMAR O TOTAL CONFORME FOR DIGITANDO -->
@foreach($itens as $i)
<script type="text/javascript">
	$('#vendido-{{$i->id}}').keyup(function () {
		vendido = document.getElementById('vendido-{{$i->id}}').value;
		vendido = parseFloat(vendido.replace(/\./gi,'').replace(/,/gi,'.'));

		valorvenda = document.getElementById('valorvenda-{{$i->id}}').value;
		valorvenda = parseFloat(valorvenda.replace(/\./gi,'').replace(/,/gi,'.'));

		montante = (vendido * valorvenda);

		document.getElementById('montante-{{$i->id}}').value = 'R$ '+montante.toLocaleString('pt-br', {minimumFractionDigits: 2});
	});
	$('#valorvenda-{{$i->id}}').keyup(function () {
		vendido = document.getElementById('vendido-{{$i->id}}').value;
		vendido = parseFloat(vendido.replace(/\./gi,'').replace(/,/gi,'.'));

		valorvenda = document.getElementById('valorvenda-{{$i->id}}').value;
		valorvenda = parseFloat(valorvenda.replace(/\./gi,'').replace(/,/gi,'.'));

		montante = (vendido * valorvenda);

		document.getElementById('montante-{{$i->id}}').value = 'R$ '+montante.toLocaleString('pt-br', {minimumFractionDigits: 2});
	});
</script>
@endforeach

<script type="text/javascript">
	$('.js-data-entregador-ajax').select2({
		width: '100%', 
		theme: "bootstrap", 
		placeholder : "Clique aqui para selecionar uma rota",      
		ajax: {
			url: "{{route('carga.inforota')}}",
			dataType: 'json',
			delay: 0,
			data: function (params) {
				return {
                    q: params.term // search term
                };
            },
            processResults: function (data) {
            	return {
            		results: data
            	};
            },
            cache: false
        },
        minimumInputLength: 3
    });
</script>

@endsection