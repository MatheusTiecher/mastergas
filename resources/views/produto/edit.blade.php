@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Editar cadastro de produto</h1>
					<form action="{{route('produto.update', $produto)}}" method="POST">
						{{method_field ('PUT')}}
						<div class="row">
							@csrf
							@include('produto._form')
						</div>
						<a type="button" href="{{route('produto.index')}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
						<button type="submit" class="btn btn-sm btn-success"  data-toggle="tooltip" title="Salvar"><i class="fas fa-check-circle"></i> Salvar</button>
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

<script type="text/javascript">
	$(document).ready(function(){ 
		var sampleArray = {!! $data !!}; 
		$(".js-data-unidade-ajax").select2({ data: sampleArray });
		$('.js-data-unidade-ajax').select2({
			width: '100%', 
			theme: "bootstrap", 
			placeholder : "Clique aqui para selecionar uma unidade",      
			ajax: {
				url: "{{route('produto.infounidade')}}",
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
	});
</script>

@endsection
