<div class="col-md-12">
	<div class="form-group">
		<label for="nome" class="form-control-label">Nome <abbr title="campo obrigatório">*</abbr></label>
		<input type="text" name="nome" class="form-control form-control-sm {{$errors->has('nome') ? 'is-invalid' : '' }}" placeholder="Nome" id="nome" value="{{old('nome', isset($mensagem->nome) ? $mensagem->nome : null)}}">
		@if($errors->has('nome'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('nome') }}</p>  
		</div>
		@endif
	</div>
</div>

<div class="col-md-5">
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

<div class="col-md-3">
	<div class="form-group">
		<label for="rotina" class="form-control-label">Rotina <abbr title="campo obrigatório">*</abbr></label>
		<select class="form-control form-control-sm" id="rotina" name="rotina">
			<option value="todo-dia" {{isset($mensagem->rotina) && $mensagem->rotina == 'todo-dia' ? 'selected' : ''}}>todo-dia</option>
			<option value="dias-da-semana" {{isset($mensagem->rotina) && $mensagem->rotina == 'dias-da-semana' ? 'selected' : ''}}>dias-da-semana</option>
		</select>
	</div>
</div>

<div class="col-md-2">
	<div class="form-group">
		<label for="hora" class="form-control-label">Horário de envio <abbr title="campo obrigatório">*</abbr></label>
		<input type="time" name="hora" class="form-control form-control-sm {{$errors->has('hora') ? 'is-invalid' : '' }} timef" placeholder="00:00" id="hora" value="{{old('hora', isset($mensagem->hora) ? $mensagem->hora : null)}}">
		@if($errors->has('hora'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('hora') }}</p>  
		</div>
		@endif
	</div>
</div>

@if(isset($mensagem))
@if($mensagem->ativo == 1)
<div class="col-md-2">
	<div class="form-group">
		<label for="sigla" class="form-control-label">Ativo <abbr title="campo obrigatório">*</abbr></label>
		<div class="form-group">
			<label class="custom-toggle">
				<input type="checkbox" name="ativo" checked>
				<span class="custom-toggle-slider rounded-circle" data-label-off="Não" data-label-on="Sim"></span>
			</label>
		</div>
	</div>
</div>
@else
<div class="col-md-2">
	<div class="form-group">
		<label for="sigla" class="form-control-label">Ativo <abbr title="campo obrigatório">*</abbr></label>
		<div class="form-group">
			<label class="custom-toggle">
				<input type="checkbox" name="ativo">
				<span class="custom-toggle-slider rounded-circle" data-label-off="Não" data-label-on="Sim"></span>
			</label>
		</div>
	</div>
</div>
@endif
@else
<div class="col-md-2">
	<div class="form-group">
		<label for="sigla" class="form-control-label">Ativo <abbr title="campo obrigatório">*</abbr></label>
		<div class="form-group">
			<label class="custom-toggle">
				<input type="checkbox" name="ativo" checked>
				<span class="custom-toggle-slider rounded-circle" data-label-off="Não" data-label-on="Sim"></span>
			</label>
		</div>
	</div>
</div>
@endif

<div class="col-md-12">
	<div class="form-group">
		<label for="msg" class="form-control-label">Mensagem <abbr title="campo obrigatório">*</abbr></label>
		<textarea type="text" rows="5" name="msg" class="form-control form-control-sm {{$errors->has('msg') ? 'is-invalid' : '' }}" placeholder="Mensagem a ser enviada" id="msg" maxlength="500">{{old('msg', isset($mensagem->msg) ? $mensagem->msg : null)}}</textarea>
		@if($errors->has('msg'))
		<div class="invalid-feedback">
			<p>{{ $errors->first('msg') }}</p>  
		</div>
		@endif
	</div>
</div>