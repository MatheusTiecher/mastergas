@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-10">
			@can('cliente-create')
			<a href="#" class="btn btn-dark mb-2" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" title="Novo"><i class="fas fa-plus-circle"></i> Novo</a>
			@endcan
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Clientes</h1>
					<div class="table-responsive py-4">
						<table class="table table-flush table-striped table-hover table-sm display compact" id="datatable-basic" width="100%">
							<thead class="thead-light">
								<th id="dtTitle">#</th>
								<th id="dtTitle">Nome / Razão</th>
								<th id="dtTitle">Cpf / Cnpj</th>
								<th id="dtTitle">Tipo</th>
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

<!-- Modal -->
@can('cliente-create')
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Selecione o tipo do cliente</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" align="center">
				<a href="{{route('cliente.createpf')}}" type="button" class="btn btn-primary" data-toggle="tooltip" title="Pessoa Física"><i class="fas fa-user-friends"></i> Pessoa Física</a>
				<a href="{{route('cliente.createpj')}}" type="button" class="btn btn-primary" data-toggle="tooltip" title="Pessoa Jurídica"><i class="fas fa-building"></i> Pessoa Jurídica</a>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" data-toggle="tooltip" title="Fechar"><i class="fas fa-arrow-circle-left"></i> Fechar</button>
			</div>
		</div>
	</div>
</div>
@endcan

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
    		displayLength: 10, //Começaremos com apenas 15 registros
    		paginate: true,    //Queremos paginas
    		filter: true,      //Queremos que o usuário possa procurar entre os 5k registros
    		ajax: '{{$page["data"]}}',
    		columns: [
    		{ data: 'id', name: 'id', searchable: false},	
    		{ data: 'nomerazao', name: 'nomerazao'},
    		{ data: 'cpfcnpj', name: 'cpfcnpj'},
    		{ data: 'tipo', name: 'tipo', searchable: false},
    		{ data: 'action', name: 'action', orderable: false, searchable: false}
    		]
    	});

	}); 

	@include('scripts.button_datatables')
</script>

@endsection