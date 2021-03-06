@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Editar configuração de mensagem (Média de Venda)</h1>
					<form action="{{route('mensagem.update', $mensagem)}}" method="POST">
						{{method_field ('PUT')}}
						<div class="row">
                            @csrf
							@include('mensagem._form')
						</div>
                        <a type="button" href="{{route('mensagem.index')}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
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
        var sampleArray = {!! $data !!}; 
        //console.log(sampleArray);

        $(".js-data-produto-ajax").select2({ data: sampleArray });
        $('.js-data-produto-ajax').select2({
            width: '100%', 
            theme: "bootstrap", 
            placeholder : "Clique aqui para selecionar um produto",      
            ajax: {
                url: "{{route('mensagem.infoproduto')}}",
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
