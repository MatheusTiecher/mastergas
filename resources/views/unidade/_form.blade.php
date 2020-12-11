<div class="col-md-8">
	<div class="form-group">
		<label for="descricao" class="form-control-label">Descrição <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="descricao" class="form-control form-control-sm {{$errors->has('descricao') ? 'is-invalid' : '' }}" placeholder="Ex: Untário, Metro..." id="descricao" value="{{old('descricao', isset($unidade->descricao) ? $unidade->descricao : null)}}">
		@if($errors->has('descricao'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('descricao') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-2">
	<div class="form-group">
		<label for="sigla" class="form-control-label">Sigla <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="sigla" class="form-control form-control-sm {{$errors->has('sigla') ? 'is-invalid' : '' }}" placeholder="Ex: und" id="sigla" value="{{old('sigla', isset($unidade->sigla) ? $unidade->sigla : null)}}">
		@if($errors->has('sigla'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('sigla') }}</p>  
		</div>
		@endif
	</div>
</div>

@if(isset($unidade))
	@if($unidade->inteiro == 1)
	<div class="col-md-2">
		<div class="form-group">
			<label for="sigla" class="form-control-label">Inteiro <abbr title="campo obrigatório">*</abbr></label>
			<div class="form-group">
				<label class="custom-toggle">
					<input type="checkbox" name="inteiro" checked>
					<span class="custom-toggle-slider rounded-circle" data-label-off="Não" data-label-on="Sim"></span>
				</label>
			</div>
		</div>
	</div>
	@else
	<div class="col-md-2">
		<div class="form-group">
			<label for="sigla" class="form-control-label">Inteiro <abbr title="campo obrigatório">*</abbr></label>
			<div class="form-group">
				<label class="custom-toggle">
					<input type="checkbox" name="inteiro">
					<span class="custom-toggle-slider rounded-circle" data-label-off="Não" data-label-on="Sim"></span>
				</label>
			</div>
		</div>
	</div>
	@endif
@else
<div class="col-md-2">
	<div class="form-group">
		<label for="sigla" class="form-control-label">Inteiro <abbr title="campo obrigatório">*</abbr></label>
		<div class="form-group">
			<label class="custom-toggle">
				<input type="checkbox" name="inteiro" checked>
				<span class="custom-toggle-slider rounded-circle" data-label-off="Não" data-label-on="Sim"></span>
			</label>
		</div>
	</div>
</div>
@endif