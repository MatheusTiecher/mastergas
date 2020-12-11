<a type="button" href="#" class="btn btn-danger mb-2" data-toggle="modal" data-target="#modalsaida" title="Saída"><i class="fas fa-minus-circle"></i> Saída</a>

<!-- Modal Saída -->
<div class="modal fade" id="modalsaida" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Saída</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{route('caixa.saidauser', $caixa)}}" method="POST">
					@csrf
					<div class="row">
						@include('caixa._form')
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"  data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i>  Voltar</button>
					<button type="submit" class="btn btn btn-success"><i class="fas fa-check-circle"></i> Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>