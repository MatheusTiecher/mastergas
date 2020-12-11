@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Relatório de carga</h1>
					<hr class="my-4">
					<form action="{{route('carga.relatoriostore')}}" method="POST">
						@csrf
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="relatorio" class="form-control-label">Relatório <abbr title="campo obrigatório">*</abbr></label>
									<select class="form-control" id="relatorio" name="relatorio" required>
										<option value="1">Carga</option>
										<option value="2">Carga Geral</option>
									</select>
								</div>
							</div>
						</div>
						<div class="input-daterange datepicker row align-items-center">
							<div class="col">
								<div class="form-group">
									<label for="datainicio" class="form-control-label">Data início <abbr title="campo obrigatório">*</abbr></label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
										</div>
										<input class="form-control" placeholder="Start date" type="text" id="datainicio" name="datainicio">
									</div>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="datafim" class="form-control-label">Data fim <abbr title="campo obrigatório">*</abbr></label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
										</div>
										<input class="form-control" placeholder="End date" type="text" id="datafim" name="datafim">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6"> 
								<div class="form-group"> 
									<label for="status" class="form-control-label">Status <abbr title="campo obrigatório">*</abbr></label> 
									<select class="form-control" id="status" name="status"> 
										<option value="10" selected="true">Todos</option>
										<option value="0">Andamento</option> 
										<option value="1">Processando</option> 
										<option value="2">Finalizado</option> 
										<option value="3">Estornado</option> 
									</select> 
								</div> 
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="rota" class="form-control-label">Rota <abbr title="campo obrigatório">*</abbr></label>
									<select class="form-control" id="rota" name="rota" required disabled="true">
										<option value="0" selected="true">Todos</option>
										@foreach($rotas as $rota)
										<option value="{{$rota->id}}">{{$rota->nome}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-6" id="entregadore">
								<div class="form-group">
									<label for="entregadore" class="form-control-label">Entregador <abbr title="campo obrigatório">*</abbr></label>
									<select class="form-control" id="entregadore" name="entregadore" required>
										<option value="0" selected="true">Todos</option>
										@foreach($entregadores as $entregadore)
										<option value="{{$entregadore->id}}">{{$entregadore->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-6" id="exportacao">
								<div class="form-group">
									<label for="exportacao" class="form-control-label">Exportação <abbr title="campo obrigatório">*</abbr></label>
									<select class="form-control" id="exportacao" name="exportacao" required>
										<option value="1">CSV</option>
									</select>
								</div>
							</div>
						</div>
						<br>
						<a type="button" href="{{route('carga.index')}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
						<button type="submit" class="btn btn-sm btn-success"  data-toggle="tooltip" title="Gerar"><i class="fas fa-check-circle"></i> Gerar</button>
					</form>
					<hr class="my-4">
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
		$('#datainicio').datetimepicker({
			locale: 'pt-BR',
			format:'DD/MM/YYYY',
			defaultDate: new Date(),
			maxDate: new Date(),
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
		$('#datafim').datetimepicker({
			useCurrent: false,
			locale: 'pt-BR',
			format:'DD/MM/YYYY',
			defaultDate: new Date(),
			maxDate: new Date(),
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
		$("#datainicio").on("dp.change", function (e) {
			$('#datafim').data("DateTimePicker").minDate(e.date);
		});
		$("#datafim").on("dp.change", function (e) {
			$('#datainicio').data("DateTimePicker").maxDate(e.date);
		});
	});
</script>

<script type="text/javascript">
	$('#relatorio').change(function(){
		var id = $(this).children("option:selected").val();
		console.log(id)
		if (id == 1) {
			document.getElementById("rota").disabled = true;
		} 
		if (id == 2){
			document.getElementById("rota").disabled = false;
		}
	});
</script>

@endsection
