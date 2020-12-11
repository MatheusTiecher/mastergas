@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Editar cadastro de estoque</h1>
					<form action="{{route('estoque.update', $estoque)}}" method="POST">
						{{method_field ('PUT')}}
						<div class="row">
							@csrf
							@include('estoque._form')
						</div>
                        <a type="button" href="{{route('estoque.index')}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
                        <button type="submit" class="btn btn-sm btn-success"  data-toggle="tooltip" title="Salvar"><i class="fas fa-check-circle"></i> Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('scripts.mask')

@include('css.select2')
@include('scripts.select2')

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

<script type="text/javascript">
	$(document).ready(function(){ 
		var sampleArrayProduto = {!! $produto !!}; 
        //console.log(sampleArrayProduto);

        $(".js-data-produto-ajax").select2({ data: sampleArrayProduto });
        $('.js-data-produto-ajax').select2({
        	width: '100%', 
            theme: "bootstrap", 
            placeholder : "Clique aqui para selecionar um produto",      
            ajax: {
            	url: "{{route('estoque.infoproduto')}}",
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

        var sampleArrayFornecedor = {!! $fornecedor !!}; 
        //console.log(sampleArrayProduto);
        $(".js-data-fornecedor-ajax").select2({ data: sampleArrayFornecedor });
        $('.js-data-fornecedor-ajax').select2({
        	width: '100%', 
            theme: "bootstrap", 
            placeholder : "Clique aqui para selecionar um fornecedor",      
            ajax: {
            	url: "{{route('estoque.infofornecedor')}}",
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
    });
</script>

@endsection
