<div class="col-md-6">
	<div class="form-group">
		<label for="password" class="form-control-label">Senha <abbr title="campo obrigatório">*</abbr></label>
		<input id="password" type="password" class="form-control form-control-sm {{$errors->has('password') ? 'is-invalid' : '' }}" name="password" autocomplete="new-password" placeholder="********">
		@if($errors->has('password'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('password') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-6">
	<div class="form-group">
		<label for="password-confirm" class="form-control-label">Confirmar senha <abbr title="campo obrigatório">*</abbr></label>
		<input id="password-confirm" type="password" class="form-control form-control-sm {{$errors->has('password_confirmation') ? 'is-invalid' : '' }}" name="password_confirmation" autocomplete="new-password" placeholder="********">
		@if($errors->has('password_confirmation'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('password_confirmation') }}</p>  
		</div>
		@endif
	</div>
</div>