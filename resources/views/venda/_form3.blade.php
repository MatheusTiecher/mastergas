<div class="col-md-12">
	<div class="form-group">
		<label for="forma_pagamento_id" class="form-control-label">Forma de Pagamento <abbr title="campo obrigatório">*</abbr></label>
		<select class="form-control" id="forma_pagamento_id" name="forma_pagamento_id">
			@foreach($formapagamentos as $formapagamento)
			<option value="{{$formapagamento->id}}">{{$formapagamento->descricao}}</option>
			@endforeach
		</select>
	</div>
</div> 

<div class="col-md-6">
	<div class="form-group">
		<label for="frete" class="form-control-label">Frete <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="frete" class="form-control {{$errors->has('frete') ? 'is-invalid' : '' }} money" placeholder="0,00" id="frete" value="{{old('frete', isset($venda->frete) ? $venda->frete : 0,00)}}" minlength="4" maxlength="8">
		@if($errors->has('frete'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('frete') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-6">
	<div class="form-group">
		<label for="desconto" class="form-control-label">Desconto <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="desconto" class="form-control {{$errors->has('desconto') ? 'is-invalid' : '' }} money" placeholder="0,00" id="desconto" value="{{old('desconto', isset($venda->desconto) ? $venda->desconto : 0,00)}}" minlength="2" maxlength="8">
		@if($errors->has('desconto'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('desconto') }}</p>  
		</div>
		@endif
	</div>
</div>