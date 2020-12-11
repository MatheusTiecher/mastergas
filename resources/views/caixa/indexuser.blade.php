@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card card-frame">
				<div class=" card-body">
					@if($caixa != null)
					<h1>Seu caixa</h1>
					@else
					<h1>Caixa fechado</h1>
					@endif
					@if($caixa != null)
					<hr class="my-4">
					<div class="row justify-content ml-4">
						@can('caixa-entrada')
						@include('caixa.entrada')
						@endcan
						@can('caixa-saida')
						@include('caixa.saidauser')
						@endcan
						@can('caixa-geral')
						<a type="button" href="{{route('caixa.index')}}" class="btn btn-outline-default mb-2" data-toggle="tooltip" title="Caixa Geral"><i class="fas fa-cash-register"></i> Caixa Geral</a>
						@endcan
					</div>
					<hr class="my-4">
					<div class="row justify-content-center">
						<div class="card col-xs-12 col-md-6 col-lg-3">
							<div class="card-body">
								<h5 class="card-title"><i class="fas fa-plus-circle"></i> Entrada</h5>
								<h2 class="card-text text-success"> R$ {{number_format($entrada, 2, ',', '.') }}</h2>
							</div>
						</div>
						<div class="card col-xs-12 col-md-6 col-lg-3">
							<div class="card-body">
								<h5 class="card-title"><i class="fas fa-minus-circle"></i> Saída</h5>
								<h2 class="card-text text-danger"> R$ {{number_format($saida, 2, ',', '.') }}</h2>
							</div>
						</div>
						<div class="card col-xs-12 col-md-6 col-lg-3">
							<div class="card-body">
								<h5 class="card-title"><i class="fas fa-cash-register"></i> Valor em caixa</h5>
								<h2 class="card-text"> R$ {{number_format($entrada - $saida , 2, ',', '.') }}</h2>
							</div>
						</div>
					</div>
					<hr class="my-4">
					@if(count($lancamento) > 0)
					<div class="accordion" id="accordionExample">
						<div class="card">
							<div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								<h5 class="mb-0"><i class="fas fa-cash-register"></i> Lançamentos caixa</h5>
							</div>
							<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
								<div class="table-responsive mt-3 mt-4">
									<table class="table table-sm table-striped">
										<thead>
											<tr>
												<th><b>Data</b></th>
												<th><b>Descrição</b></th>
												<th><b>Valor</b></th>
												<th><b>Tipo Lançamento</b></th>
											</tr>
										</thead>
										@foreach($lancamento as $l)
										@if($l->tipo_lancamento == 1)
										<tr>
											<td>{{date('d/m/Y : H:i', strtotime($l->created_at))}}</td>
											<td>{{$l->descricao}}</td>
											<td class="card-text text-success">R$ {{number_format($l->valor, 2, ',', '.')}}</td>
											<td>Entrada</td>
										</tr>
										@else
										<tr>
											<td>{{date('d/m/Y : H:i', strtotime($l->created_at))}}</td>
											<td>{{$l->descricao}}</td>
											<td class="card-text text-danger">R$ {{number_format($l->valor, 2, ',', '.')}}</td>
											<td>Saída</td>
										</tr>
										@endif
										@endforeach
									</table>
								</div>
							</div>
						</div>
					</div>
					@else
					<h1 align="center">Você não tem nenhum lançamento hoje</h1>
					@endif
					@else
					@can('caixa-abrir')
					<hr class="my-4">
					<a type="button" href="{{route('caixa.create')}}" class="btn btn-default mb-2" data-toggle="tooltip" title="Abrir Caixa"><i class="fas fa-cash-register"></i> Abrir Caixa</a>
					@endcan
					@endif		
				</div>
			</div>
		</div>
	</div>
</div>

@include('scripts.mask')

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

@endsection