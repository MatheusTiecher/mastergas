@foreach($modulos as $modulo)
<div class="accordion" id="accordionExample">
    <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseExample-{{$modulo}}" aria-expanded="true" aria-controls="collapseExample-{{$modulo}}">
        <h3 class="mb-0">Permissões {{$modulo}}</h3>
    </div>
    <div class="card card-frame">
        <div class="collapse" id="collapseExample-{{$modulo}}">
            <div class="table-responsive">
                <table class="table table-sm table-bordered"> 
                    <thead class="thead-light">
                        <tr>
                            <th>Permissão</th>
                            <th>descrição</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach($permissoes as $value)
                        @if($modulo == $value->modulo)
                        <tr>
                            <td>
                                <div class="col-md-6">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="permissoes[]" class="custom-control-input" id="switch-{{$value->id}}" value="{{$value->id}}" {{$cargo->cargoPermissoes()->wherePivot('permissao_id','=',$value->id)->exists() == 1 ? 'checked': ''}}>
                                        <label class="custom-control-label" for="switch-{{$value->id}}"> {{$value->nome}}</label>
                                    </div>
                                </div>
                            </td>
                            <div class="col-md-6">
                                <td>{{$value->descricao}}</td>
                            </div>
                        </tr>
                        @endif
                        @endforeach
                        <tr>
                            <td>
                                <div class="col-md-6">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="switch-{{$modulo}}">
                                        <label class="custom-control-label" for="switch-{{$modulo}}"> Todos</label>
                                    </div>
                                </div>
                            </td>
                            <div class="col-md-6">
                                <td>Marcar todos os campos</td>
                            </div>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach