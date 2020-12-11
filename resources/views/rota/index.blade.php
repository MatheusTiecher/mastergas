@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-10">
			@can('rota-create')
			<a href="{{route('rota.create')}}" class="btn btn-dark mb-2" data-toggle="tooltip" title="Novo"><i class="fas fa-plus-circle" ></i> Novo</a>
			@endcan
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Rotas</h1>
					<div class="table-responsive py-4">
						<table class="table table-flush table-striped table-hover table-sm display compact" id="datatable-basic" width="100%">
							<thead class="thead-light">
								<th id="dtTitle">#</th>
								<th id="dtTitle">Nome</th>
								<th id="dtTitle">Cidade</th>
								<th id="dtTitle">Ações</th>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="varshow"></div>

@include('css.datatables')
@include('scripts.datatables')

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

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
			{ data: 'nome', name: 'nome'},
			{ data: 'cidade', name: 'cidade'},
			{ data: 'action', name: 'action', orderable: false, searchable: false}
			]
		});

	}); 

	@include('scripts.button_datatables')
</script>

@endsection