@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-4">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Mudar Senha</h1>
					<form action="{{route('user.mudarsenhastore')}}" method="POST">
						{{method_field ('PUT')}}
						<div class="row">
							@csrf
							@include('user._form3')
						</div>
						<button type="submit" class="btn btn-sm btn-outline-default"  data-toggle="tooltip" title="Alterar Senha"><i class="fas fa-lock"></i> Alterar Senha</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection