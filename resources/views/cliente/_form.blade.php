@if($pf == 1)
<div class="col-md-12">
	<div class="form-group">
		<label for="nomerazao" class="form-control-label">Nome <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="nomerazao" class="form-control form-control-sm {{$errors->has('nomerazao') ? 'is-invalid' : '' }}" placeholder="Nome" id="nomerazao" value="{{old('nomerazao', isset($cliente->nomerazao) ? $cliente->nomerazao : null)}}">
		@if($errors->has('nomerazao'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('nomerazao') }}</p>  
		</div>
		@endif
	</div>
</div>
@endif

@if($pf == 0)
<div class="col-md-6">
	<div class="form-group">
		<label for="nomerazao" class="form-control-label">Razão Social <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="nomerazao" class="form-control form-control-sm {{$errors->has('nomerazao') ? 'is-invalid' : '' }}" placeholder="Razão Social" id="nomerazao" value="{{old('nomerazao', isset($cliente->nomerazao) ? $cliente->nomerazao : null)}}">
		@if($errors->has('nomerazao'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('nomerazao') }}</p>  
		</div>
		@endif
	</div>
</div>
@endif

@if($pf == 0)
<div class="col-md-6">
	<div class="form-group">
		<label for="fantasia" class="form-control-label">Nome Fantasia <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="fantasia" class="form-control form-control-sm {{$errors->has('fantasia') ? 'is-invalid' : '' }}" placeholder="Nome Fantasia" id="fantasia" value="{{old('fantasia', isset($cliente->fantasia) ? $cliente->fantasia : null)}}">
		@if($errors->has('fantasia'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('fantasia') }}</p>  
		</div>
		@endif
	</div>
</div>
@endif

@if($pf == 1)
<div class="col-md-6">
	<div class="form-group">
		<label for="cpfcnpj" class="form-control-label">CPF <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="cpfcnpj" class="form-control form-control-sm {{$errors->has('cpfcnpj') ? 'is-invalid' : '' }} cpf" placeholder="000.000.000-00" id="cpfcnpj" value="{{old('cpfcnpj', isset($cliente->cpfcnpj) ? $cliente->cpfcnpj : null)}}" >
		@if($errors->has('cpfcnpj'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('cpfcnpj') }}</p>  
		</div>
		@endif
	</div>
</div>
@endif

@if($pf == 1)
<div class="col-md-6">
	<div class="form-group">
		<label for="rgie" class="form-control-label">RG</label>
		<input type="text" name="rgie" class="form-control form-control-sm {{$errors->has('rgie') ? 'is-invalid' : '' }}" placeholder="Registro Geral" id="rgie" value="{{old('rgie', isset($cliente->rgie) ? $cliente->rgie : null)}}" >
		@if($errors->has('rgie'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('rgie') }}</p>  
		</div>
		@endif
	</div>
</div>
@endif

@if($pf == 0)
<div class="col-md-6">
	<div class="form-group">
		<label for="cpfcnpj" class="form-control-label">CNPJ <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="cpfcnpj" class="form-control form-control-sm {{$errors->has('cpfcnpj') ? 'is-invalid' : '' }} cnpj" placeholder="00.000.000/0000-00" id="cpfcnpj" value="{{old('cpfcnpj', isset($cliente->cpfcnpj) ? $cliente->cpfcnpj : null)}}" >
		@if($errors->has('cpfcnpj'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('cpfcnpj') }}</p>  
		</div>
		@endif
	</div>
</div>
@endif

@if($pf == 0)
<div class="col-md-6">
	<div class="form-group">
		<label for="rgie" class="form-control-label">IE</label>
		<input type="text" name="rgie" class="form-control form-control-sm {{$errors->has('rgie') ? 'is-invalid' : '' }}" placeholder="Inscrição Estadual" id="rgie" value="{{old('rgie', isset($cliente->rgie) ? $cliente->rgie : null)}}">
		@if($errors->has('rgie'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('rgie') }}</p>  
		</div>
		@endif
	</div>
</div>
@endif

<div class="col-md-12">
	<div class="form-group">
		<label for="email" class="form-control-label">Email <abbr title="campo obrigatório">*</abbr></label>
		<input type="email" name="email" class="form-control form-control-sm {{$errors->has('email') ? 'is-invalid' : '' }}" id="email" placeholder="nome@exemplo.com" value="{{old('email', isset($cliente->email) ? $cliente->email : null)}}" >
		@if($errors->has('email'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('email') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-6">
	<div class="form-group">
		<label for="celular" class="form-control-label">Telefone Celular <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="celular" class="form-control form-control-sm {{$errors->has('celular') ? 'is-invalid' : '' }} celular" placeholder="(00) 00000-0000" id="celular" value="{{old('celular', isset($cliente->celular) ? $cliente->celular : null)}}" >
		@if($errors->has('celular'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('celular') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-6">
	<div class="form-group">
		<label for="telefone" class="form-control-label">Telefone</label>
		<input type="text" name="telefone" class="form-control form-control-sm {{$errors->has('telefone') ? 'is-invalid' : '' }} telefone" placeholder="(00) 0000-0000" id="telefone" value="{{old('telefone', isset($cliente->telefone) ? $cliente->telefone : null)}}">
		@if($errors->has('telefone'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('telefone') }}</p>  
		</div>
		@endif
	</div>
</div>

<input type="hidden" name="pf" id="pf" value="{{$pf}}">