@foreach($itens as $i)
<div class="col-xs-12 col-md-8 col-lg-6">
	<div class="form-group">
		<label for="produto-{{$i->id}}" class="form-control-label">Produto</label>
		<input type="text" class="form-control form-control-sm" value="{{$i->descricao}}" id="produto-{{$i->id}}" readonly=“true”>
	</div>
</div>

<div class="col-xs-12 col-md-4 col-lg-6">
	<div class="form-group">
		<label for="total-{{$i->id}}" class="form-control-label">Quantidade Disponível</label>
		<input type="text" class="form-control form-control-sm" value="{{number_format($i->quantidade, 2, ',', '.')}} {{strtoupper($i->sigla)}}" id="total-{{$i->id}}" readonly=“true”>
	</div>
</div>

<div class="col-xs-12 col-md-4 col-lg-4">
	<div class="form-group">
		<label for="vendido-{{$i->id}}" class="form-control-label">Quantidade Vendida<abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="vendido[]" class="form-control form-control-sm {{$errors->has('vendido[]') ? 'is-invalid' : '' }} money" placeholder="0" id="vendido-{{$i->id}}" value="0" minlength="1" maxlength="8" required="true">
		@if($errors->has('vendido[]'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('rota_id') }}</p>
		</div>
		@endif
	</div>
</div>

<div class="col-xs-12 col-md-4 col-lg-4">
	<div class="form-group">
		<label for="valorvenda" class="form-control-label">Valor <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="valorvenda[]" class="form-control form-control-sm {{$errors->has('valorvenda[]') ? 'is-invalid' : '' }} money" placeholder="0,00" id="valorvenda-{{$i->id}}" value="0,00" minlength="4" maxlength="8" required="true">
		@if($errors->has('valorvenda[]'))
		<div class="invalid-feedback"> 
			<p>{{ $errors->first('rota_id') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-xs-12 col-md-4 col-lg-4">
	<div class="form-group">
		<label for="montante-{{$i->id}}" class="form-control-label">Total</label>
		<input type="text" class="form-control form-control-sm" placeholder="R$ 0,00" id="montante-{{$i->id}}" readonly=“true”>
	</div>
</div>

<div class="col-md-12">	
	<hr>
</div>

<input type="hidden" name="idproduto[]" id="idproduto" value="{{$i->id}}">
@endforeach

<div class="col-md-8">
	<div class="form-group">
		<label for="rota_id" class="form-control-label">Rota <abbr title="campo obrigatório">*</abbr></label>
		<select class="form-control form-control-sm js-data-entregador-ajax {{$errors->has('rota_id') ? 'is-invalid' : '' }}" id="rota_id" name="rota_id" data-toggle="select" title="Simple select" data-live-search="true" data-minimum-results-for-search="Infinity"> </select>
		@if($errors->has('rota_id'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('rota_id') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-4">
	<div class="form-group">
		<label for="desconto" class="form-control-label"> Desconto <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="desconto" class="form-control form-control-sm {{$errors->has('desconto') ? 'is-invalid' : '' }} money" placeholder="0,00" value="{{old('desconto')}}" id="desconto" minlength="4" maxlength="8" required="true">
		@if($errors->has('desconto'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('desconto') }}</p>  
		</div>
		@endif
	</div>
</div>