@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Cadastro de cliente - Pessoa Física</h1>
					<form action="{{route('cliente.store')}}" method="POST">
						<div class="row">
							@csrf
							@include('cliente._form')
						</div>
						<a type="button" href="{{route('cliente.index')}}" class="btn btn-sm btn-outline-danger" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
						<button type="submit" class="btn btn-sm btn-success" title="Salvar"><i class="fas fa-check-circle"></i> Salvar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@include('scripts.mask')

@endsection