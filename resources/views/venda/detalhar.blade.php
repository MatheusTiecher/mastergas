@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Venda #{{$venda->id}} - {{strtoupper($status[$venda->status])}}</h1>
					@if($venda->status != 0)
					<hr class="my-4">
					<!-- BLOCO 1 -->
					<div class="row justify-content-center">
						@include('venda.blocos.bloco1')
					</div>
					@if($venda->observacao != null)
					<hr class="my-4">
					<!-- BLOCO 4 -->
					@include('venda.blocos.bloco4')
					@endif
					<hr class="my-4">
					<!-- BLOCO 2 -->
					@include('venda.blocos.bloco2')
					@endif
					<hr class="my-4">
					<!-- BLOCO 3 -->
					<div class="row justify-content-center">
						@include('venda.blocos.bloco3')
					</div>		
				</div>
			</div>
		</div>
	</div>
</div>

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

@include('scripts.mask')

<script type="text/javascript">
	$(document).on('click', '#estornar', function(){
		if(confirm("Tem certeza de que deseja estornar essa venda?"))
		{

		}
		else
		{
			return false;
		}
	});
</script>

@endsection