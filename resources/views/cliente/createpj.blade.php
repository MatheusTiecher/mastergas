@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Cadastro de cliente - Pessoa Jurídica</h1>
					<form action="{{route('cliente.store')}}" method="POST">
						<div class="row">
							@csrf
							@include('cliente._form')
						</div>
						<a type="button" href="{{route('cliente.index')}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
						<button type="submit" class="btn btn-sm btn-success"  data-toggle="tooltip" title="Salvar"><i class="fas fa-check-circle"></i> Salvar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@include('scripts.mask')

<!-- <script type="text/javascript">
	$( document ).ready(function() {
		var data = 0;
		$('#cpfcnpj').keyup(function () {
			var data = document.getElementById('cpfcnpj').value;
			// console.log(data)
			if (data.length == 18) {
				$.ajax({
					type: 'GET',
					url: "{{route('cliente.consultacnpj')}}",
					data: {data: data},
					dataType: 'json',
					success: function (data) {
						// console.log(data);
						document.querySelector("[name='nomerazao']").value = data.nome;
						document.querySelector("[name='fantasia']").value = data.fantasia;
						document.querySelector("[name='telefone']").value = data.telefone.slice(0, 14);

					},error:function(){ 
						console.log(data);
					}
				});
			}
		});
	});
</script> -->

@endsection