@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Relatório de produto</h1>
					<hr class="my-4">
					<form action="{{route('produto.relatoriostore')}}" method="POST">
						<div class="row">
							@csrf
							<div class="col-md-6">
								<div class="form-group">
									<label for="relatorio" class="form-control-label">Relatório <abbr title="campo obrigatório">*</abbr></label>
									<select class="form-control" id="relatorio" name="relatorio" required>
										<option value="1">Produtos</option>
										<option value="2">Estoque mínimo</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="status" class="form-control-label">Produtos <abbr title="campo obrigatório">*</abbr></label>
									<select class="form-control" id="status" name="status" required>
										<option value="1">Ativos</option>
										<option value="2">Inativos</option>
										<option value="3">Todos</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
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
						<a type="button" href="{{route('produto.index')}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
						<button type="submit" class="btn btn-sm btn-success"  data-toggle="tooltip" title="Gerar"><i class="fas fa-check-circle"></i> Gerar</button>
					</form>
					<hr class="my-4">
				</div>
			</div>
		</div>
	</div>
</div>

@endsection