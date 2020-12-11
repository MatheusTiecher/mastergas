<div class="col-md-12">
	<div class="form-group">
		<label for="endereco_id" class="form-control-label">Endereço <abbr title="campo obrigatório">*</abbr></label>
		<select class="form-control form-control-md js-data-endereco-ajax {{$errors->has('endereco_id') ? 'is-invalid' : '' }}" id="endereco_id" name="endereco_id" data-toggle="select" title="Simple select" data-live-search="true" data-minimum-results-for-search="Infinity"> </select>
		@if($errors->has('endereco_id'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('endereco_id') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-12">
	<div class="form-group">
		<label for="user_id" class="form-control-label">Entregador <abbr title="campo obrigatório">*</abbr></label>
		<select class="form-control form-control-md js-data-entregador-ajax {{$errors->has('user_id') ? 'is-invalid' : '' }}" id="user_id" name="user_id" data-toggle="select" title="Simple select" data-live-search="true" data-minimum-results-for-search="Infinity"> </select>
		@if($errors->has('user_id'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('user_id') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-12">
	<div class="form-group">
		<label for="anotacao" class="form-control-label">Anotação</label>
		<input type="text" name="anotacao" class="form-control form-control-md {{$errors->has('anotacao') ? 'is-invalid' : '' }}" placeholder="Anotação para entrega" id="anotacao">
		@if($errors->has('anotacao'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('anotacao') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-12">
	<div class="form-group">
		<label for="dataagendada" class="form-control-label">Data Entrega <abbr title="campo obrigatório">*</abbr></label>
		<div class="input-group input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
			</div>
			<input class="form-control form-control datepicker" placeholder="Select date" type="text" id="dataagendada" name="dataagendada">
		</div>
	</div>
</div>