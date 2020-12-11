<div class="col-md-12">
	<div class="form-group">
		<label for="nome" class="form-control-label">Nome <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="nome" class="form-control form-control-sm {{$errors->has('nome') ? 'is-invalid' : '' }}" placeholder="Nome" id="nome" value="{{old('nome', isset($cargo->nome) ? $cargo->nome : null)}}">
		@if($errors->has('nome'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('nome') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-12">
	<div class="form-group">
		<label for="descricao" class="form-control-label">Descrição <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="descricao" class="form-control form-control-sm {{$errors->has('descricao') ? 'is-invalid' : '' }}" placeholder="Uma breve observação" id="descricao" value="{{old('descricao', isset($cargo->descricao) ? $cargo->descricao : null)}}">
		@if($errors->has('descricao'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('descricao') }}</p>  
		</div>
		@endif
	</div>
</div>