<div class="col-md-12">
	<div class="form-group">
		<label for="produto_id" class="form-control-label">Produto <abbr title="campo obrigatório">*</abbr></label>
		<select class="form-control form-control-sm js-data-produto-ajax {{$errors->has('produto_id') ? 'is-invalid' : '' }}" id="produto_id" name="produto_id" data-toggle="select" title="Simple select" data-live-search="true" data-minimum-results-for-search="Infinity">> </select>
		@if($errors->has('produto_id'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('produto_id') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-4">
	<div class="form-group">
		<label for="quantidadestoque" class="form-control-label">Quantidade em estoque</label>
		<input type="text" class="form-control form-control-sm" placeholder="0" id="quantidadestoque" readonly=“true”>
	</div>
</div>

<div class="col-md-4">
	<div class="form-group">
		<label for="customedio" class="form-control-label">Custo médio</label>
		<input type="text" class="form-control form-control-sm" placeholder="0,00" id="customedio" readonly=“true”>
	</div>
</div>

<div class="col-md-4">
	<div class="form-group">
		<label for="quantidade" class="form-control-label">Quantidade <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="quantidade" class="form-control form-control-sm {{$errors->has('quantidade') ? 'is-invalid' : '' }} money" placeholder="0" id="quantidade" value="{{old('quantidade')}}" minlength="1" maxlength="8">
		@if($errors->has('quantidade'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('quantidade') }}</p>  
		</div>
		@endif
	</div>
</div>