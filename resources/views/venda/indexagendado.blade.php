@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-10">
			@can('venda-create')
			<a href="#" class="btn btn-dark mb-2" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" title="Novo"><i class="fas fa-plus-circle" ></i> Novo</a>
			@endcan
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Vendas agendadas</h1>
					<div class="table-responsive py-4">
						<table class="table table-flush table-striped table-hover table-sm display compact" id="datatable-basic" width="100%">
							<thead class="thead-light">
								<th id="dtTitle">#</th>
								<th id="dtTitle">Cliente</th>
								<th id="dtTitle">Valor</th>
								<th id="dtTitle">Cidade</th>
								<th id="dtTitle">Rua/N°</th>
								<th id="dtTitle">Data Agendada</th>
								<th id="dtTitle">Entregador</th>
								<th id="dtTitle">Status</th>
								<th id="dtTitle">Ações</th>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h1 id="exampleModalLabel">Nova venda</h1>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{route('venda.store')}}" method="POST">
					@csrf
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="form-group">
									<label for="cliente_id" class="form-control-label">Cliente </label>
									<select class="form-control form-control-sm js-data-cliente-ajax {{$errors->has('cliente_id') ? 'is-invalid' : '' }}" id="cliente_id" name="cliente_id" data-toggle="select" title="Simple select" data-live-search="true" data-minimum-results-for-search="Infinity" required="true"> </select>
									@if($errors->has('cliente_id'))
									<div class="invalid-feedback">
										<p>{{ $errors->first('cliente_id') }}</p>  
									</div>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a type="button" href="#" class="btn btn btn-outline-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancelar</a>
					<button type="submit" class="btn btn btn-outline-default"><i class="fas fa-arrow-circle-right"></i> Iniciar Venda</button>
				</div>
			</form>
		</div>
	</div>
</div>


@include('css.datatables')
@include('scripts.datatables')

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

@include('css.select2')
@include('scripts.select2')

<script type="text/javascript">
	$('.js-data-cliente-ajax').select2({
		width: '100%', 
		theme: "bootstrap4", 
		placeholder : "Clique aqui para selecionar um cliente",      
		ajax: {
			url: "{{route('venda.infocliente')}}",
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


<script>
	$(document).ready(function() { 
		$('#datatable-basic').DataTable({	
			"language": {
				"url": "{{asset('json/Portuguese-Brasil.json')}}",
			},
			select: false,
			order: [5, "asc" ],
			processing: true,
			serverSide: true,
			ajax: '{{$page["data"]}}',
			columns: [
			{ data: 'venda_id', name: 'id' },	
			{ data: 'cliente', name: 'cliente', orderable: false, searchable: false},
			{ data: 'valor', name: 'valor', orderable: false, searchable: false},
			{ data: 'cidade', name: 'cidade'},
			{ data: 'rua', name: 'rua'},
			{ data: 'dataagendada', name: 'dataagendada'},
			{ data: 'entregador', name: 'entregador'},
			{ data: 'status', name: 'status', orderable: false, searchable: false},
			{ data: 'action', name: 'action', orderable: false, searchable: false}
			]
		});
	});
</script>

@endsection