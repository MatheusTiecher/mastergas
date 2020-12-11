<a type="button" href="#" class="btn btn-sm btn-outline-default mb-2" data-toggle="modal" data-target="#ConfirmaEntrega" title="Confirmar Entrega"><i class="far fa-calendar-check"></i> Confirmar Entrega</a>
<!-- Modal Confirma Entrega -->
<div class="modal fade" id="ConfirmaEntrega" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Confirma Entrega</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{route('entrega.confirmaentrega', $entrega)}}" method="POST">
					@csrf
					<div class="row">
						<div class="col-md-12">
							<div class="form-group" align="left">
								<label for="forma_pagamento_id" class="form-control-label">Forma de Pagamento <abbr title="campo obrigatório">*</abbr></label>
								<select class="form-control" id="forma_pagamento_id" name="forma_pagamento_id">
									@foreach($formapagamentos as $formapagamento)
									<option value="{{$formapagamento->id}}" {{isset($formapagamento->descricao) && $formapagamento->descricao == $entrega->venda->formapagamento->descricao ? 'selected' : ''}}>{{$formapagamento->descricao}}</option>

									@endforeach
								</select>
							</div>
						</div> 
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"  data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i>  Voltar</button>
					<button type="submit" class="btn btn btn-outline-default"><i class="far fa-calendar-check"></i> Confirmar Entrega</button>
				</div>
			</form>
		</div>
	</div>
</div>