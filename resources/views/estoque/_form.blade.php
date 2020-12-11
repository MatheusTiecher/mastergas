<div class="col-md-6">
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

<div class="col-md-6">
	<div class="form-group">
		<label for="fornecedor_id" class="form-control-label">Fornecedor <abbr title="campo obrigatório">*</abbr></label>
		<select class="form-control form-control-sm js-data-fornecedor-ajax {{$errors->has('fornecedor_id') ? 'is-invalid' : '' }}" id="fornecedor_id" name="fornecedor_id" data-toggle="select" title="Simple select" data-live-search="true" data-minimum-results-for-search="Infinity">> </select>
		@if($errors->has('fornecedor_id'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('fornecedor_id') }}</p>  
		</div>
		@endif
	</div>
</div>

@if(!isset($estoque))
<div class="col-md-6">
	<div class="form-group">
		<label for="valorcusto" class="form-control-label">Valor de custo <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="valorcusto" class="form-control form-control-sm {{$errors->has('valorcusto') ? 'is-invalid' : '' }} money" placeholder="0,00" id="valorcusto" value="{{old('valorcusto', isset($estoque->valorcusto) ? $estoque->valorcusto : null)}}" minlength="4" maxlength="8">
		@if($errors->has('valorcusto'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('valorcusto') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-6">
	<div class="form-group">
		<label for="total" class="form-control-label">Quantidade <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="total" class="form-control form-control-sm {{$errors->has('total') ? 'is-invalid' : '' }} money" placeholder="0" id="total" value="{{old('total', isset($estoque->total) ? $estoque->total : null)}}" minlength="1" maxlength="8">
		@if($errors->has('total'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('total') }}</p>  
		</div>
		@endif
	</div>
</div>
@endif

@if(isset($estoque))
<div class="col-md-4">
	<div class="form-group">
		<label for="valorcusto" class="form-control-label">Valor de custo <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="valorcusto" class="form-control form-control-sm {{$errors->has('valorcusto') ? 'is-invalid' : '' }} money" placeholder="0,00" id="valorcusto" value="{{old('valorcusto', isset($estoque->valorcusto) ? $estoque->valorcusto : null)}}" minlength="4" maxlength="8">
		@if($errors->has('valorcusto'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('valorcusto') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-4">
	<div class="form-group">
		<label for="total" class="form-control-label">Quantidade <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="total" class="form-control form-control-sm {{$errors->has('total') ? 'is-invalid' : '' }} money" placeholder="0" id="total" value="{{old('total', isset($estoque->total) ? $estoque->total : null)}}" minlength="1" maxlength="8">
		@if($errors->has('total'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('total') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-4">
	<div class="form-group">
		<label for="restante" class="form-control-label">Restante</label>
		<input type="text" class="form-control form-control-sm money" placeholder="0" id="restante" value="{{$estoque->restante}}" readonly=“true”>
	</div>
</div>
@endif