@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Relatório de estoque</h1>
					<hr class="my-4">
					<form action="{{route('estoque.relatoriostore')}}" method="POST">
						<div class="row">
							@csrf
							<div class="col-md-6">
								<div class="form-group">
									<label for="status" class="form-control-label">Estoque <abbr title="campo obrigatório">*</abbr></label>
									<select class="form-control" id="status" name="status" required>
										<option value="1">Estoque Antigo</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="exportacao" class="form-control-label">Exportação <abbr title="campo obrigatório">*</abbr></label>
									<select class="form-control" id="exportacao" name="exportacao" required>
										<option value="1">CSV</option>
										<option value="2">PDF</option>
									</select>
								</div>
							</div>
						</div>
						<br>
						<a type="button" href="{{route('estoque.index')}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
						<button type="submit" class="btn btn-sm btn-success"  data-toggle="tooltip" title="Gerar" target="_blank"><i class="fas fa-check-circle"></i> Gerar</button>
					</form>
					<hr class="my-4">
				</div>
			</div>
		</div>
	</div>
</div>

@endsection