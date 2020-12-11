<div class="col-md-12">
	<div class="form-group">
		<label for="valor" class="form-control-label">Valor <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="valor" class="form-control {{$errors->has('valor') ? 'is-invalid' : '' }} money" value="{{old('valor')}}" placeholder="0,00" id="valor" minlength="4" maxlength="8" required="true">
		@if($errors->has('valor'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('valor') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-12">
	<div class="form-group">
		<label for="descricao" class="form-control-label">Descrição <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="descricao" class="form-control {{$errors->has('descricao') ? 'is-invalid' : '' }}" value="{{old('descricao')}}" placeholder="Descrição" id="descricao" minlength="3" maxlength="50" required="true">
		@if($errors->has('descricao'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('descricao') }}</p>  
		</div>
		@endif
	</div>
</div> 