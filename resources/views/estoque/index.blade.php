@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-10">
			@can('estoque-create')
			<a href="{{route('estoque.create')}}" class="btn btn-dark mb-2" data-toggle="tooltip" title="Novo"><i class="fas fa-plus-circle" ></i> Novo</a>
			@endcan
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Estoques</h1>
					<div class="table-responsive py-4">
						<table class="table table-flush table-striped table-hover table-sm display compact" id="datatable-basic" width="100%">
							<thead class="thead-light">
								<th id="dtTitle">#</th>
								<th id="dtTitle">Produto</th>
								<th id="dtTitle">Fornecedor</th>
								<th id="dtTitle">Custo</th>
								<th id="dtTitle">Restante</th>
								<th id="dtTitle">Ações</th>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

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
			{ data: 'produto', name: 'produto'},
			{ data: 'fornecedor', name: 'fornecedor'},
			{ data: 'valorcusto', name: 'valorcusto'},
			{ data: 'restante', name: 'restante'},
			{ data: 'action', name: 'action', orderable: false, searchable: false}
			]
		});

	}); 

	//delete permanente com ajax - Inicio
	$(document).on('click', '.del-perm', function(){
		var id = $(this).attr('id');
		var tr = $(this).closest('tr');
		var table = $('#datatable-basic').DataTable();
		if(confirm("Tem certeza que deseja excluir permanentemente esse registro?"))
		{
			$.ajax({
				url:'{{$page["forceDelete"]}}',
				mehtod:"get",
				data:{id:id},
				success:function(data)
				{
					$.notify({
						icon: data.icon,
						title: data.title,
						message: data.message
					},{
						type: data.type
					});
					table.row(tr).remove().draw( false );

				}
			})
		}
		else
		{
			return false;
		}
	});
	//delete permanente com ajax - Fim
	
</script>

@endsection