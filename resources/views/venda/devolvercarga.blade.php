@extends('layouts.app')

@section('content')
<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Venda #{{$venda->id}} - Devolução para carga #{{$venda->carga->id}}</h1>
					@if(count($itens) > 0)
					<div class="table-responsive mt-3 mt-4">
						<table class="table table-sm table-striped ">
							<thead>
								<tr>
									<th>Produto</th>
									<th>Quantidade</th>
									<th>Ações</th>
								</tr>
							</thead>
							@foreach($itens as $i)
							<tr>
								<td>{{$i->descricao}}</td>
								<td>{{number_format($i->quantidade, 2, ',', '.')}} {{strtoupper($i->sigla)}}</td>
								<td>@include('venda.modal.devolvercarga')</td>
							</tr>
							@endforeach
						</table>
					</div>
					@else
					<h3 align="center">Nenhum produto disponível para devolução!</h3>
					@endif
					<a type="button" href="{{route('venda.detalhar', $venda)}}" class="btn btn-secondary mb-2 mt-2" data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i> Voltar</a>
				</div>
			</div>
		</div>
	</div>
</div>

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

@include('scripts.mask')

@endsection