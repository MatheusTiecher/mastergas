@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Adicionar produtos - Carga #{{$carga->id}}</h1>
					<hr class="my-4">
					<form action="{{route('carga.cargaitemstore', $carga)}}" method="POST">
						<div class="row">
							@csrf
							@include('carga._form')
						</div>
						<div class="col-lg-12" style="text-align: right;">
							<button type="submit" class="btn btn-outline-success" data-toggle="tooltip" title="Adicionar"><i class="fas fa-cart-plus"></i></button>
						</div>
					</form>
					<div class="table-responsive mt-3 mt-4">
						<table class="table table-sm table-striped ">
							<thead>
								<tr>
									<th>Produto</th>
									<th>Quantidade</th>
									<th>Ações</th>
								</tr>
							</thead>
							@foreach($carga->cargaitem as $i)
							<tr>
								<td>{{ App\Models\Produto::withTrashed()->find($i->produto_id)->descricao}}</td>
								<td>{{number_format($i->quantidade, 2, ',', '.')}} {{App\Models\Unidade::withTrashed()->find(App\Models\Produto::withTrashed()->find($i->produto_id)->unidade_id)->sigla}}</td>
								<td>
									<a href="{{route('carga.destroyitem', $i)}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="top" title="Remover"><i class="fas fa-trash-alt"></i> Remover</a>
								</td>
							</tr>
							@endforeach
						</table>
					</div>
					<hr class="my-4">
					<button class="btn btn-outline-dark btn-sm " type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" data-toggle="tooltip" title="Adicionar Observação"><i class="fas fa-plus"></i></button>
					<form action="{{route('carga.atualizacarga', $carga)}}" method="POST">
						@csrf
						<div class="collapse" id="collapseExample">
							<div class="card card-body">
								<div class="row">
									<div class="col-md-12"> 
										<div class="form-group">
											<label for="observacao" class="form-control-label">Observação </label>
											<input type="text" name="observacao" class="form-control form-control-sm {{$errors->has('observacao') ? 'is-invalid' : '' }}" placeholder="Uma breve observação" id="observacao" value="{{old('observacao', isset($carga->observacao) ? $carga->observacao : null)}}">
											@if($errors->has('observacao'))
											<div class="invalid-feedback">
												<p>{{ $errors->first('observacao') }}</p>  
											</div>
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12" style="text-align: right;">
							<a type="button" href="{{route('carga.index')}}" class="btn btn btn-outline-danger mt-4" data-dismiss="modal" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
							<button type="submit" class="btn btn-outline-default mt-4" data-toggle="tooltip" title="Concluir"><i class="fas fa-arrow-circle-right"></i> Concluir</button>
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

<script type="text/javascript">
	$('.js-data-produto-ajax').select2({
		width: '100%', 
		theme: "bootstrap", 
		placeholder : "Clique aqui para selecionar um produto",      
		ajax: {
			url: "{{route('carga.infoproduto')}}",
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

	$('.js-data-produto-ajax').change(function(){
		var id = $(this).children("option:selected").val();

		$.ajax({
			type: "GET",
			url: "{{route('carga.proautocomplete')}}",
			data: { id: id },
			success: function(data) {
				document.getElementById('customedio').value = data.customedio
				document.getElementById('quantidadestoque').value = data.quantidadestoque
			},
			error:function(data){
				console.log(data);
			}
		});
	});

</script>

@endsection