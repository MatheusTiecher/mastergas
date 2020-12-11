<a type="button" href="#" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#modalitens-{{$i->id}}" title="Trocar"><i class="fas fa-sync-alt"></i> Trocar</a>
<!-- Modal devolver -->
<div class="modal fade" id="modalitens-{{$i->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Trocar {{$i->descricao}}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{route('venda.storetrocar', $venda)}}" method="POST">
					@csrf
					<div class="row">
						<input type="hidden" name="idproduto" id="idproduto" value="{{$i->id}}">
						<div class="col-md-6">
							<div class="form-group">
								<label for="quantidadestoque" class="form-control-label">Quantidade total</label>
								<input type="text" class="form-control form-control-sm" placeholder="0" value="{{number_format($i->quantidade, 2, ',', '.')}}" readonly=“true”>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="quantidade" class="form-control-label">Quantidade troca <abbr title="campo obrigatório">*</abbr></label>
								<input type="text" name="quantidade" class="form-control form-control-sm {{$errors->has('quantidade') ? 'is-invalid' : '' }} money" placeholder="0" id="quantidade" value="{{old('quantidade')}}" minlength="1" maxlength="8" required="true">
								@if($errors->has('quantidade'))
								<div class="invalid-feedback">
									<p>{{ $errors->first('quantidade') }}</p>  
								</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="descricao" class="form-control-label">Motivo troca <abbr title="campo obrigatório">*</abbr></label>
								<input type="text" name="descricao" class="form-control form-control-sm {{$errors->has('descricao') ? 'is-invalid' : '' }}" placeholder="Produto trocado" id="descricao" value="{{old('descricao', !isset($asd) ? 'Produto trocado' : null)}}" minlength="3" axlength="50" required="true">
								@if($errors->has('descricao'))
								<div class="invalid-feedback">
									<p>{{ $errors->first('descricao') }}</p>  
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"  data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i>  Voltar</button>
					<button type="submit" class="btn btn btn-outline-default"><i class="fas fa-exchange-alt"></i> Trocar</button>
				</div>
			</form>
		</div>
	</div>
</div>