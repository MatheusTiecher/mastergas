<a type="button" href="#" class="btn btn-success mb-2" data-toggle="modal" data-target="#modalentrada" title="Entrada"><i class="fas fa-plus-circle"></i> Entrada</a>

<!-- Modal Entrada -->
<div class="modal fade" id="modalentrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Entrada</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{route('caixa.entrada', $caixa)}}" method="POST">
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