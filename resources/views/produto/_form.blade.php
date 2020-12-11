<div class="col-md-12">
	<div class="form-group">
		<label for="descricao" class="form-control-label">Descrição <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="descricao" class="form-control form-control-sm {{$errors->has('descricao') ? 'is-invalid' : '' }}" placeholder="Ex: Aguá, Gás..." id="descricao" value="{{old('descricao', isset($produto->descricao) ? $produto->descricao : null)}}">
		@if($errors->has('descricao'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('descricao') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-4">
	<div class="form-group">
		<label for="valorvenda" class="form-control-label">Valor de venda <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="valorvenda" class="form-control form-control-sm {{$errors->has('valorvenda') ? 'is-invalid' : '' }} money" placeholder="0,00" id="valorvenda" value="{{old('valorvenda', isset($produto->valorvenda) ? $produto->valorvenda : null)}}" minlength="4" maxlength="8">
		@if($errors->has('valorvenda'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('valorvenda') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-4">
	<div class="form-group">
		<label for="minimo" class="form-control-label">Estoque mínimo <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="minimo" class="form-control form-control-sm {{$errors->has('minimo') ? 'is-invalid' : '' }} money" placeholder="0,00" id="minimo" value="{{old('minimo', isset($produto->minimo) ? $produto->minimo : null)}}" minlength="1" maxlength="8">
		@if($errors->has('minimo'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('minimo') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-4">
	<div class="form-group">
		<label for="unidade_id" class="form-control-label">Unidade <abbr title="campo obrigatório">*</abbr></label>
		<select class="form-control form-control-sm js-data-unidade-ajax {{$errors->has('unidade_id') ? 'is-invalid' : '' }}" id="unidade_id" name="unidade_id" data-toggle="select" title="Simple select" data-live-search="true" data-minimum-results-for-search="Infinity"> </select>
		@if($errors->has('unidade_id'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('unidade_id') }}</p>  
		</div>
		@endif
	</div>
</div>