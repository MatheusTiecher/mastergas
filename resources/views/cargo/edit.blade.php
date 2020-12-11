@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Editar cadastro de cargo</h1>
					<form action="{{route('cargo.update', $cargo)}}" method="POST">
						{{method_field ('PUT')}}
						<div class="row">
							@csrf
							@include('cargo._form')
						</div>
						<a type="button" href="{{route('cargo.index')}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
						<button type="submit" class="btn btn-sm btn-success"  data-toggle="tooltip" title="Salvar"><i class="fas fa-check-circle"></i> Salvar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@include('scripts.mask')

@endsection
