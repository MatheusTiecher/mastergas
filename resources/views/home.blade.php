@extends('layouts.app')

@section('content')

<div class="container-fluid">   
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-frame">
                <div class=" card-body">
                    <h1>Painel de controle</h1>
                    <hr class="my-4">
                    <div class="row mb-4">
                        @can('caixa-user-caixa')
                        <div class="col-lg-4">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <a href="{{route('caixa.indexuser')}}" title="Clique aqui para ver seu caixa">
                                                <h5 class="card-title text-uppercase text-muted mb-0">Seu caixa</h5>
                                                <span class="h2 font-weight-bold mb-0">R$ {{number_format($valor, 2, ',', '.') }}</span>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow">
                                                <i class="fas fa-cash-register"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        Valor em caixa
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endcan
                        @can('venda-agendar')
                        <div class="col-lg-4">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <a href="{{route('venda.indexagendado')}}" title="Clique aqui para ver as entregas restantes de hoje">
                                                <h5 class="card-title text-uppercase text-muted mb-0">Entregas</h5>
                                                <span class="h2 font-weight-bold mb-0">{{$entregas}}</span>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                                <i class="ni ni-delivery-fast"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        Entregas restantes de hoje
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endcan
                        @can('produto-minimo')
                        <div class="col-lg-4">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <a href="{{route('produto.estoqueminimo')}}" title="Clique aqui para ver os estoques minimos">
                                                <h5 class="card-title text-uppercase text-muted mb-0">Estoque mínimo</h5>
                                                <span class="h2 font-weight-bold mb-0">{{$minimo}}</span>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-danger text-white rounded-circle shadow">
                                                <i class="ni ni-box-2"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        Produtos
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@can('venda-relatorio')
<div class="container-fluid">   
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-frame">
                <div class=" card-body">
                    <h1>Gráfico de venda semanal</h1>
                    <div align="center" style="width: 100%;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script type="text/javascript">
    var ctx = document.getElementById('myChart').getContext('2d');
    var data = {!! $data !!};

    console.log(data);
    var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'line',

    // The data for our dataset
    data: {
        labels: data.semana.reverse(),
        datasets: [{
            label: 'Andamento',
            backgroundColor: 'transparent',
            borderColor: 'rgb(75, 192, 192)',
            data: data.andamento.reverse()
        },{
            label: 'Agendado',
            backgroundColor: 'transparent',
            borderColor: 'rgb(54, 162, 235)',
            data: data.agendado.reverse()
        },{
            label: 'Finalizado',
            backgroundColor: 'transparent',
            borderColor: 'rgb(201, 203, 207)',
            data: data.finalizado.reverse()
        },{
            label: 'Estornado',
            backgroundColor: 'transparent',
            borderColor: 'rgb(255, 99, 132)',
            data: data.estornado.reverse()
        }]
    },

    // Configuration options go here
    options: {}
});
</script>

@endsection