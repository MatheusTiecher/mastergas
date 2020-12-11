<div class="col-md-12">
    <div class="form-group">
        <label for="name" class="form-control-label">Nome <abbr title="campo obrigatório">*</abbr></label>
        <input type="text" name="name" class="form-control form-control-sm {{$errors->has('name') ? 'is-invalid' : '' }}" placeholder="Nome" id="name" value="{{old('name', isset($user->name) ? $user->name : null)}}">
        @if($errors->has('name'))
        <div class="invalid-feedback">
            <p>{{ $errors->first('name') }}</p>  
        </div>
        @endif
    </div>
</div>

<div class="col-md-12">
    <div class="form-group">
        <label for="email" class="form-control-label">Email <abbr title="campo obrigatório">*</abbr></label>
        <input type="email" name="email" class="form-control form-control-sm {{$errors->has('email') ? 'is-invalid' : '' }}" id="email" placeholder="nome@exemplo.com" value="{{old('email', isset($user->email) ? $user->email : null)}}" >
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
        <input type="text" name="celular" class="form-control form-control-sm {{$errors->has('celular') ? 'is-invalid' : '' }} celular" placeholder="(00) 00000-0000" id="celular" value="{{old('celular', isset($user->celular) ? $user->celular : null)}}" >
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
        <input type="text" name="telefone" class="form-control form-control-sm {{$errors->has('telefone') ? 'is-invalid' : '' }} telefone" placeholder="(00) 0000-0000" id="telefone" value="{{old('telefone', isset($user->telefone) ? $user->telefone : null)}}">
        @if($errors->has('telefone'))
        <div class="invalid-feedback">
            <p>{{ $errors->first('telefone') }}</p>  
        </div>
        @endif
    </div>
</div>

@if(isset($user))
@if($user->entregador == 1)
<div class="col-md-12">
    <div class="form-group">
        <label for="sigla" class="form-control-label">Entregador <abbr title="campo obrigatório">*</abbr></label>
        <div class="form-group">
            <label class="custom-toggle">
                <input type="checkbox" name="entregador" checked>
                <span class="custom-toggle-slider rounded-circle" data-label-off="Não" data-label-on="Sim"></span>
            </label>
        </div>
    </div>
</div>
@else
<div class="col-md-12">
    <div class="form-group">
        <label for="sigla" class="form-control-label">Entregador <abbr title="campo obrigatório">*</abbr></label>
        <div class="form-group">
            <label class="custom-toggle">
                <input type="checkbox" name="entregador">
                <span class="custom-toggle-slider rounded-circle" data-label-off="Não" data-label-on="Sim"></span>
            </label>
        </div>
    </div>
</div>
@endif
@else
<div class="col-md-12">
    <div class="form-group">
        <label for="sigla" class="form-control-label">Entregador <abbr title="campo obrigatório">*</abbr></label>
        <div class="form-group">
            <label class="custom-toggle">
                <input type="checkbox" name="entregador">
                <span class="custom-toggle-slider rounded-circle" data-label-off="Não" data-label-on="Sim"></span>
            </label>
        </div>
    </div>
</div>
@endif