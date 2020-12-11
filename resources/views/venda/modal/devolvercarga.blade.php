<a type="button" href="#" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#modalitens-{{$i->id}}" title="Devolver"><i class="fas fa-sync-alt"></i> Devolver</a>
<!-- Modal devolver -->
<div class="modal fade" id="modalitens-{{$i->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Devolver para carga {{$i->descricao}}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{route('venda.storedevolvercarga', $venda)}}" method="POST">
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
								<label for="quantidade" class="form-control-label">Quantidade devolução <abbr title="campo obrigatório">*</abbr></label>
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
								<label for="descricao" class="form-control-label">Motivo devolução <abbr title="campo obrigatório">*</abbr></label>
								<input type="text" name="descricao" class="form-control form-control-sm {{$errors->has('descricao') ? 'is-invalid' : '' }}" placeholder="Produto devolvido para carga" id="descricao" value="{{old('descricao', !isset($asd) ? 'Produto devolvido para carga' : null)}}" minlength="3" maxlength="8" required="true">
								@if($errors->has('descricao'))
								<div class="invalid-feedback">
									<p>{{ $errors->first('descricao') }}</p>
								</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="desconto" class="form-control-label">Atualizar Desconto <abbr title="campo obrigatório">*</abbr></label>
								<input type="text" name="desconto" class="form-control form-control-sm {{$errors->has('desconto') ? 'is-invalid' : '' }} money" placeholder="0,00" id="desconto" value="{{old('desconto', isset($venda->desconto) ? $venda->desconto : null)}}" minlength="4" maxlength="8">
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
					<button type="submit" class="btn btn btn-outline-default"><i class="fas fa-sync-alt"></i> Devolver</button>
				</div>
			</form>
		</div>
	</div>
</div>