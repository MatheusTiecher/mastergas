@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Entrega venda #{{$venda->id}}</h1>
					@can('venda-agendar')
					<hr class="my-4">
					<form action="{{route('venda.agendarentrega', $venda)}}" method="POST">
						<div class="row">
							@csrf
							@include('venda._form2')
						</div>
						<div class="col-lg-12" style="text-align: right;">
							@can('cliente-endereco-create')
							<a href="{{route('entrega.createEnderecoVenda', $venda)}}" type="button" data-toggle="tooltip" title="Clique para adicionar um novo endereço" class="btn btn-outline-success ml-2 mb-2"><i class="fas fa-map-marker-alt"></i> Adicionar Endereço</a>
							@endcan
							<button type="submit" class="btn btn-outline-default mb-2" data-toggle="tooltip" title="Agendar Entrega"><i class="fas fa-arrow-circle-right"></i> Agendar Entrega</button>
						</div>
					</form>
					@endcan
					<hr class="my-4">
					<div class="col-lg-12" style="text-align: right;">
						<a type="button" href="{{route('venda.vendaitem', $venda)}}" class="btn btn-secondary mb-2" data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i> Voltar</a>
						<a type="button" href="{{route('venda.index')}}" class="btn btn btn-outline-danger mb-2" data-dismiss="modal" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
						@can('venda-retirar')
						<a type="button" href="{{route('venda.retirarentrega', $venda)}}" class="btn btn-outline-default mb-2" data-toggle="tooltip" title="Retirada na Loja"><i class="fas fa-arrow-circle-right"></i> Retirada na Loja</a>
						@endcan
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

@include('css.select2')
@include('scripts.select2')

@include('scripts.datepicker')

<script type="text/javascript">
	$(function () {
		$('#dataagendada').datetimepicker({
			locale: 'pt-BR',
			// format:'YYYY-MM-DD HH:mm:ss',
			format:'DD/MM/YYYY HH:mm',
			minDate: new Date(),
			defaultDate: new Date(),
			icons: {
				time: "fa fa-clock",
				date: "fa fa-calendar-day",
				up: "fa fa-chevron-up",
				down: "fa fa-chevron-down",
				previous: 'fa fa-chevron-left',
				next: 'fa fa-chevron-right',
				today: 'fa fa-screenshot',
				clear: 'fa fa-trash',
				close: 'fa fa-remove'
			}
		});
	});
</script>

<script type="text/javascript">
	$('.js-data-entregador-ajax').select2({
		width: '100%', 
		theme: "bootstrap", 
		placeholder : "Clique aqui para selecionar um entregador",      
		ajax: {
			url: "{{route('venda.infousuario')}}",
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
        minimumInputLength: 0
    });

	$('.js-data-endereco-ajax').select2({
		width: '100%', 
		theme: "bootstrap", 
		placeholder : "Clique aqui para selecionar o endereço do cliente",      
		ajax: {
			url: "{{route('venda.infoendereco', $venda)}}",
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
        minimumInputLength: 0
    });
</script>

@endsection