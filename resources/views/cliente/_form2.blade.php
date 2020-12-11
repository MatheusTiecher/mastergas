<div class="col-md-6">
	<div class="form-group">
		<label for="rua" class="form-control-label">Rua <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="rua" class="form-control form-control-sm {{$errors->has('rua') ? 'is-invalid' : '' }}" placeholder="Rua" id="rua" value="{{old('rua', isset($endereco->rua) ? $endereco->rua : null)}}">
		@if($errors->has('rua'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('rua') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-6">
	<div class="form-group">
		<label for="bairro" class="form-control-label">Bairro <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="bairro" class="form-control form-control-sm {{$errors->has('bairro') ? 'is-invalid' : '' }}" placeholder="Bairro" id="bairro" value="{{old('bairro', isset($endereco->bairro) ? $endereco->bairro : null)}}">
		@if($errors->has('bairro'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('bairro') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-3">
	<div class="form-group">
		<label for="numero" class="form-control-label">Número <abbr title="campo obrigatório">*</abbr></label>
		<input type="number" name="numero" class="form-control form-control-sm {{$errors->has('numero') ? 'is-invalid' : '' }}" placeholder="Número" id="numero" value="{{old('numero', isset($endereco->numero) ? $endereco->numero : null)}}">
		@if($errors->has('numero'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('numero') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-3">
	<div class="form-group">
		<label for="cep" class="form-control-label">CEP <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="cep" class="form-control form-control-sm {{$errors->has('cep') ? 'is-invalid' : '' }} cep" placeholder="00000-000" id="cep" value="{{old('cep', isset($endereco->cep) ? $endereco->cep : null)}}">
		@if($errors->has('cep'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('cep') }}</p>  
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
		<label for="complemento" class="form-control-label">Complemento</label>
		<input type="text" name="complemento" class="form-control form-control-sm {{$errors->has('complemento') ? 'is-invalid' : '' }}" placeholder="Complemento" id="complemento" value="{{old('complemento', isset($endereco->complemento) ? $endereco->complemento : null)}}">
		@if($errors->has('complemento'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('complemento') }}</p>  
		</div>
		@endif
	</div>
</div>