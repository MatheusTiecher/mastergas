@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-10">
			@can('carga-create')
			<a href="#" class="btn btn-dark mb-2" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" title="Novo"><i class="fas fa-plus-circle" ></i> Novo</a>
			@endcan
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Cargas</h1>
					<div class="table-responsive py-4">
						<table class="table table-flush table-striped table-hover table-sm display compact" id="datatable-basic" width="100%">
							<thead class="thead-light">
								<th id="dtTitle">#</th>
								<th id="dtTitle">Entregador</th>
								<th id="dtTitle">Status</th>
								<th id="dtTitle">Criação</th>
								<th id="dtTitle">Ações</th>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@can('carga-create')
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h1 id="exampleModalLabel">Nova carga</h1>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{route('carga.store')}}" method="POST">
					@csrf
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="user_id" class="form-control-label">Entregador</label>
								<select class="form-control form-control-md js-data-entregador-ajax {{$errors->has('user_id') ? 'is-invalid' : '' }}" id="user_id" name="user_id" data-toggle="select" title="Simple select" data-live-search="true" data-minimum-results-for-search="Infinity" required="true"> </select>
								@if($errors->has('user_id'))
								<div class="invalid-feedback">
									<p>{{ $errors->first('user_id') }}</p>  
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a type="button" href="#" class="btn btn btn-outline-danger" data-dismiss="modal" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
					<button type="submit" class="btn btn btn-outline-default" data-toggle="tooltip" title="Nova carga"><i class="fas fa-arrow-circle-right"></i> Nova Carga</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endcan

@include('css.datatables')
@include('scripts.datatables')

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

@include('css.select2')
@include('scripts.select2')

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
			processing: true,
			serverSide: true,
			ajax: '{{$page["data"]}}',
			columns: [
			{ data: 'id', name: 'id' },	
			{ data: 'user', name: 'user'},
			{ data: 'status', name: 'status'},
			{ data: 'created_at', name: 'created_at'},
			{ data: 'action', name: 'action', orderable: false, searchable: false}
			]
		});

	}); 
</script>

@endsection