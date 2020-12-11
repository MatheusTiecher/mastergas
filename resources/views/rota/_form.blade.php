<div class="col-md-6">
	<div class="form-group">
		<label for="nome" class="form-control-label">Nome <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="nome" class="form-control form-control-sm {{$errors->has('nome') ? 'is-invalid' : '' }}" placeholder="Nome" id="nome" value="{{old('nome', isset($rota->nome) ? $rota->nome : null)}}">
		@if($errors->has('nome'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('nome') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-6">
	<div class="form-group">
		<label for="cidade_id" class="form-control-label">Cidade <abbr title="campo obrigatório">*</abbr></label>
		<select class="form-control form-control-sm js-data-cidade-ajax {{$errors->has('cidade_id') ? 'is-invalid' : '' }}" id="cidade_id" name="cidade_id" data-toggle="select" title="Simple select" data-live-search="true" data-minimum-results-for-search="Infinity"> </select>
		@if($errors->has('cidade_id'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('cidade_id') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-12">
	<div class="form-group">
		<label for="descricao" class="form-control-label">Observação </label>
		<input type="text" name="descricao" class="form-control form-control-sm {{$errors->has('descricao') ? 'is-invalid' : '' }}" placeholder="Uma breve observação" id="descricao" value="{{old('descricao', isset($rota->descricao) ? $rota->descricao : null)}}">
		@if($errors->has('descricao'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('descricao') }}</p>  
		</div>
		@endif
	</div>
</div>