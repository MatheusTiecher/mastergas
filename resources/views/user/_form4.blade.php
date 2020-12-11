<div class="table-responsive">
    <table class="table table-sm table-bordered"> 
        <thead class="thead-light">
            <tr>
                <th>Cargos</th>
                <th>descrição</th>
            </tr>
        </thead>
        <tbody class="list">
            @foreach($cargos as $value)
            @if($value->id != 1)
            <tr>
                <td>
                    <div class="col-md-6">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="cargos[]" class="custom-control-input" id="switch-{{$value->id}}" value="{{$value->id}}" {{$user->userCargos()->wherePivot('cargo_id','=',$value->id)->exists() == 1 ? 'checked': ''}}>
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
        </tbody>
    </table>
</div>