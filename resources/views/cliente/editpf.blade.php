@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Editar cadastro de cliente - Pessoa Física</h1>
					<form action="{{route('cliente.update', $cliente)}}" method="POST">
						{{method_field ('PUT')}}
						<div class="row">
							@csrf
							@include('cliente._form')
						</div>
						<a type="button" href="{{route('cliente.index')}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
						<button type="submit" class="btn btn-sm btn-success" data-toggle="tooltip" title="Salvar"><i class="fas fa-check-circle"></i> Salvar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@can('cliente-endereco-menu')
@include('cliente.endereco')
@endcan

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

@include('scripts.mask')

<script type="text/javascript">
	$(document).on('click', '#del-perm', function(){
		if(confirm("Tem certeza que deseja excluir permanentemente esse registro?"))
		{

		}
		else
		{
			return false;
		}
	});
</script>

@endsection