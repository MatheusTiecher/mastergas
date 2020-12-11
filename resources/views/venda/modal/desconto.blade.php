<a type="button" href="#" class="btn btn-outline-primary mb-2" data-toggle="modal" data-target="#modaldesconto" title="Ajustar Desconto"><i class="fas fa-tags"></i> Desconto</a>

<!-- Modal desconto -->
<div class="modal fade" id="modaldesconto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Ajustar Desconto</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{route('venda.updatedesconto', $venda)}}" method="POST">
					@csrf
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="desconto" class="form-control-label">Atualizar Desconto <abbr title="campo obrigatório">*</abbr></label>
								<input type="text" name="desconto" class="form-control form-control-sm {{$errors->has('desconto') ? 'is-invalid' : '' }} money" placeholder="0,00" id="desconto" value="{{old('desconto', isset($venda->desconto) ? $venda->desconto : null)}}" maxlength="8" minlength="4" required="true">
								@if($errors->has('desconto'))
								<div class="invalid-feedback">
									<p>{{ $errors->first('desconto') }}</p>  
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"  data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i>  Voltar</button>
					<button type="submit" class="btn btn-success"  data-toggle="tooltip" title="Salvar"><i class="fas fa-check-circle"></i> Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>