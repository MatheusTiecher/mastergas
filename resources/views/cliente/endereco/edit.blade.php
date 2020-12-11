@extends('layouts.app')

@section('content')

<div class="container-fluid">   
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-frame">
                <div class=" card-body">
                    <h1>Editar endereço - {{$endereco->cliente->nomerazao}}</h1>
                    <form action="{{route('cliente.updateEndereco', $endereco)}}" method="POST">
                        <div class="row">
                            @csrf
                            @include('cliente._form2')
                        </div>
                        <a type="button" href="{{route('cliente.edit', $endereco->cliente)}}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Cancelar"><i class="fas fa-times-circle"></i> Cancelar</a>
                        <button type="submit" class="btn btn-sm btn-success"  data-toggle="tooltip" title="Salvar"><i class="fas fa-check-circle"></i> Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('css.select2')
@include('scripts.select2')

@include('scripts.mask')

<script type="text/javascript">
    $(document).ready(function(){ 
        var sampleArray = {!! $data !!}; 
        //console.log(sampleArray);

        $(".js-data-cidade-ajax").select2({ data: sampleArray });
        $('.js-data-cidade-ajax').select2({
            width: '100%', 
            theme: "bootstrap", 
            placeholder : "Clique aqui para selecionar uma cidade",      
            ajax: {
                url: "{{route('cidade.info')}}",
                dataType: 'json',
                delay: 0,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            },
            minimumInputLength: 3
        });
    });
</script>

@endsection
