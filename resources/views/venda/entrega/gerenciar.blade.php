@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Gerenciar Entrega Venda #{{$entrega->venda->id}} - {{strtoupper($status[$entrega->status])}}</h1>
					<hr class="my-4">
					<div align="center">
						<a type="button" href="{{route('venda.detalhar', $entrega->venda)}}" class="btn btn-sm btn-secondary mb-2" data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i> Voltar</a>
						@if($entrega->status == 1)
						@can('venda-entrega-reagendar')
						<a type="button" href="{{route('entrega.createocorrencia', $entrega)}}" class="btn btn-sm btn-outline-default mb-2" data-toggle="tooltip" title="Reagendar Entrega"><i class="far fa-clock"></i> Reagendar</a>
						@endcan
						@can('venda-entrega-confirmar')
						@include('venda.entrega.confirmaentrega')
						@endcan
						@endif
					</div>
					<hr class="my-4">
					@foreach($ocorrenciaentrega as $o)
					<div class="card col-md-12">
						<div class="card-body">
							<h5 class="card-title"><i class="fas fa-home"></i> Ocorrência Entrega - {{strtoupper($status[$o->status])}}</h5>
							<p class="card-text">
								<b>Cliente:</b> {{$entrega->venda->cliente->nomerazao}}
							</p>
							<p class="card-text">
								@if(isset($o->anotacao))
								<b>Anotação:</b> {{$o->anotacao}}
								@endif
								<br><b>Endereço:</b> {{$o->endereco->rua}}, {{$o->endereco->numero}}, {{$o->endereco->bairro}} - {{$o->endereco->cidade->nome}}, {{$o->endereco->cidade->estado->sigla}} 
							</p>
							<p class="card-text">
								<b>Entregador:</b> {{$o->user->name}}
								<br><b>Data entrega:</b> {{$o->dataagendada}}
							</p>
							@if(isset($o->ocorrencia))
							<p class="card-text">
								<b>Ocorrência:</b> {{$o->ocorrencia}}
							</p>
							@endif
							<div align="center">
								@include('venda.entrega.detalharendereco')
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

@include('scripts.datepicker')

<script type="text/javascript">
	$(function () {
		$('#dataentrega').datetimepicker({
			locale: 'pt-BR',
			// format:'YYYY-MM-DD HH:mm:ss',
			format:'DD/MM/YYYY HH:mm',
			// minDate: new Date(),
			defaultDate: new Date(),
			icons: {
				time: "fa fa-clock",
				date: "fa fa-calendar-day",
				up: "fa fa-chevron-up",
				down: "fa fa-chevron-down",
				previous: 'fa fa-chevron-left',
				next: 'fa fa-chevron-right',
				today: 'fa fa-screenshot',
				clear: 'fa fa-trash',
				close: 'fa fa-remove'
			}
		});
	});
</script>

@endsection