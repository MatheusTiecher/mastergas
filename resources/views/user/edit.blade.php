@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Editar cadastro de usuário</h1>
					<form action="{{route('user.senhaupdate', $user)}}" method="POST">
						{{method_field ('PUT')}}
						<div class="row">
							@csrf
							@include('user._form3')
						</div>
						<button type="submit" class="btn btn-sm btn-outline-default"  data-toggle="tooltip" title="Alterar Senha"><i class="fas fa-lock"></i> Alterar Senha</button>
					</form>
					<hr class="my-4">
					<form action="{{route('user.update', $user)}}" method="POST">
						{{method_field ('PUT')}}
						<div class="row">
							@csrf
							@include('user._form2')
						</div>
						<a type="button" href="{{route('user.index')}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
						<button type="submit" class="btn btn-sm btn-success"  data-toggle="tooltip" title="Salvar"><i class="fas fa-check-circle"></i> Salvar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@include('scripts.mask')

@endsection
