@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Relatório de caixa</h1>
					<hr class="my-4">
					<form action="{{route('caixa.relatoriostore')}}" method="POST">
						@csrf
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="relatorio" class="form-control-label">Relatório <abbr title="campo obrigatório">*</abbr></label>
									<select class="form-control" id="relatorio" name="relatorio" required>
										<option value="1">Movimentação de caixa por período</option>
										<option value="2">Movimentação de caixa por dia com totais</option>
										<option value="3">Fechamento caixa</option>
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
						<div class="row" id="alterar">
							<div class="col-md-6" id="user">
								<div class="form-group">
									<label for="user" class="form-control-label">Usuário <abbr title="campo obrigatório">*</abbr></label>
									<select class="form-control" id="user" name="user" required>
										@foreach($users as $user)
										<option value="{{$user->id}}">{{$user->name}}</option>
										@endforeach
										<option value="0">Todos</option>
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
						<a type="button" href="{{route('caixa.index')}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
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
			document.getElementById('alterar').innerHTML = '<div class="col-md-6" id="user"> <div class="form-group"> <label for="user" class="form-control-label">Usuário <abbr title="campo obrigatório">*</abbr></label> <select class="form-control" id="user" name="user" required> @foreach($users as $user) <option value="{{$user->id}}">{{$user->name}}</option> 										@endforeach <option value="0">Todos</option> </select> </div> </div> <div class="col-md-6" id="exportacao"> <div class="form-group"> 									<label for="exportacao" class="form-control-label">Exportação <abbr title="campo obrigatório">*</abbr></label> <select class="form-control" id="exportacao" name="exportacao" required> <option value="1">CSV</option> </select> </div> </div>';
		} 
		if (id == 2){
			document.getElementById('alterar').innerHTML = '<div class="col-md-6" id="user"> <div class="form-group"> <label for="user" class="form-control-label">Usuário <abbr title="campo obrigatório">*</abbr></label> <select class="form-control" id="user" name="user" required> @foreach($users as $user) <option value="{{$user->id}}">{{$user->name}}</option> 										@endforeach <option value="0">Todos</option> </select> </div> </div> <div class="col-md-6" id="exportacao"> <div class="form-group"> 									<label for="exportacao" class="form-control-label">Exportação <abbr title="campo obrigatório">*</abbr></label> <select class="form-control" id="exportacao" name="exportacao" required> <option value="2">PDF</option> </select> </div> </div>';
		}
		if (id == 3){
			document.getElementById('alterar').innerHTML = '<div class="col-md-6" id="exportacao"> <div class="form-group"> <label for="exportacao" class="form-control-label">Exportação <abbr title="campo obrigatório">*</abbr></label> <select class="form-control" id="exportacao" name="exportacao" required> <option value="1">CSV</option> <option value="2">PDF</option> </select> </div> </div>';
		}
	});
</script>

@endsection
