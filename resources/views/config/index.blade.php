@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-frame">
				<div class="card-header">
					<h1>Configurações Cidades</h1>
				</div>
				<div class="card-body" align="center">
					<a href="{{route('config.estado')}}" class="btn btn-primary mr-2 mb-2">Popular Estados</a>
					<a href="{{route('config.cidade')}}" class="btn btn-primary mr-2 mb-2">Popular Cidades</a>
					<a href="{{route('config.cidades')}}" class="btn btn-primary mr-2 mb-2">Cidades</a>
				</div>
			</div>
		</div>
	</div>
</div>

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

@endsection