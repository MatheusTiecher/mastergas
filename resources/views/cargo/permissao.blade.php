@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Gerenciamento de permissões - {{$cargo->nome}}</h1>
					<hr class="my-4">
					<form action="{{route('cargo.permissaostore', $cargo)}}" method="POST">
						@csrf
						@include('cargo._form2')
						<a type="button" href="{{route('cargo.index')}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
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

@foreach($modulos as $modulo)
<script type="text/javascript">
	$("#switch-{{$modulo}}").on('change', function() {
		var todos = document.getElementById("switch-{{$modulo}}").checked;
		console.log(todos)
		if (todos == true) {
			@foreach($permissoes as $value)
			@if($modulo == $value->modulo)
			document.getElementById('switch-{{$value->id}}').checked = true
			@endif
			@endforeach
		} else {
			@foreach($permissoes as $value)
			@if($modulo == $value->modulo)
			document.getElementById('switch-{{$value->id}}').checked = false
			@endif
			@endforeach
		}
	});
</script>
@endforeach

@endsection