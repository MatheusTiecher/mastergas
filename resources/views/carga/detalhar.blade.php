@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Carga #{{$carga->id}} - {{strtoupper($status[$carga->status])}}</h1>
					<hr class="my-4">
					<!-- BLOCO 1 -->
					<div class="row justify-content-center">
						@include('carga.blocos.bloco1')
					</div>
					<hr class="my-4">
					<!-- BLOCO 2 -->
					@include('carga.blocos.bloco2')
					<hr class="my-4">
					<!-- BLOCO 3 -->
					<div class="row justify-content-center">
						@include('carga.blocos.bloco3')
					</div>		
				</div>
			</div>
		</div>
	</div>
</div>

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

<script type="text/javascript">
	$(document).on('click', '#estornar', function(){
		if(confirm("Tem certeza que deseja estornar essa carga?"))
		{
			//
		}
		else
		{
			return false;
		}
	});
</script>

<script type="text/javascript">
	$(document).on('click', '#finalizacarga', function(){
		if(confirm("Tem certeza que deseja finalizar essa carga?"))
		{
			//
		}
		else
		{
			return false;
		}
	});
</script>

@endsection