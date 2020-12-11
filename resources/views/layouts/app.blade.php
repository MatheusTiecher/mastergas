<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link href="{{asset('icon.svg')}}" rel="icon" type="image/png">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Icons -->
    <link href="{{asset('assets/vendor/nucleo/css/nucleo.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet">

    <!-- Argon CSS -->
    <link type="text/css" href="{{asset('assets/css/argon.min.css')}}" rel="stylesheet">

    <style type="text/css">

        #dtTitle{
            font-size: 0.8rem;
        }

        body{
            background-image: url("{{asset('fndo.jpg')}}"); 
            min-height: 100vh;
            background-size: cover;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-position: center center;
        }
        /*navbar ajustavel para mobile*/ 
        @media only screen and (min-width: 992px) {
            body{
                background-image: url("{{asset('fundo.jpg')}}"); 
                min-height: 100vh;
                background-size: cover;
                background-attachment: fixed;
                background-repeat: no-repeat;
                background-position: center center;
            }

            nav{
                height: 55px;
            }
        }

        abbr{
            color: red;
        }

        footer{
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #212529;
            color: white;
            text-align: center;
            z-index: 10;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    <img src="{{asset('icon.svg')}}" style="width: 22px;">{{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @guest
                    @else
                    <ul class="navbar-nav mr-auto">
                        @can('cliente-menu')
                        <li class="nav-item active" >
                            <a class="nav-link" href="{{route('cliente.index')}}">Clientes</a>
                        </li>
                        @endcan
                        @can('fornecedor-menu')
                        <li class="nav-item active" >
                            <a class="nav-link" href="{{route('fornecedor.index')}}">Fornecedores</a>
                        </li>
                        @endcan
                        @can('estoque-menu')
                        <li class="nav-item active" >
                            <a class="nav-link" href="{{route('estoque.index')}}">Estoque</a>
                        </li>
                        @endcan
                        @can('produto-menu')
                        <li class="nav-item active" >
                            <a class="nav-link" href="{{route('produto.index')}}">Produtos</a>
                        </li>
                        @endcan
                        @can('rota-menu')
                        <li class="nav-item active" >
                            <a class="nav-link" href="{{route('rota.index')}}">Rota</a>
                        </li>
                        @endcan
                        @can('carga-menu')
                        <li class="nav-item active" >
                            <a class="nav-link" href="{{route('carga.index')}}">Carga</a>
                        </li>                 
                        @endcan
                        @can('caixa-menu')
                        <li class="nav-item active" >
                            <a class="nav-link" href="{{route('caixa.indexuser')}}">Caixa</a>
                        </li>
                        @endcan
                        @can('venda-menu')
                        <li class="nav-item dropdown active">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Vendas
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @can('venda-todas')
                                <a class="dropdown-item" href="{{route('venda.index')}}"><i class="far fa-list-alt"></i>Vendas </a>
                                @endcan
                                @can('venda-andamentos')
                                <a class="dropdown-item" href="{{route('venda.indexandamento')}}"><i class="fas fa-shopping-cart"></i>Vendas Andamento</a>
                                @endcan
                                @can('venda-agendadas')
                                <a class="dropdown-item" href="{{route('venda.indexagendado')}}"><i class="far fa-calendar-alt"></i>Vendas Agendadas</a>
                                @endcan
                            </div>
                        </li>
                        @endcan
                        <li class="nav-item dropdown active">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Relatórios
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @can('cliente-relatorio')
                                <a class="dropdown-item" href="{{route('cliente.relatorio')}}">Cliente</a>
                                @endcan
                                @can('fornecedor-relatorio')
                                <a class="dropdown-item" href="{{route('fornecedor.relatorio')}}">Fornecedor</a>
                                @endcan
                                @can('rota-relatorio')
                                <a class="dropdown-item" href="{{route('rota.relatorio')}}">Rota</a>
                                @endcan
                                @can('estoque-relatorio')
                                <a class="dropdown-item" href="{{route('estoque.relatorio')}}">Estoque</a>
                                @endcan
                                @can('produto-relatorio')
                                <a class="dropdown-item" href="{{route('produto.relatorio')}}">Produto</a>
                                @endcan
                                @can('caixa-relatorio')
                                <a class="dropdown-item" href="{{route('caixa.relatorio')}}">Caixa</a>
                                @endcan
                                @can('venda-relatorio')
                                <a class="dropdown-item" href="{{route('venda.relatorio')}}">Venda</a>
                                @endcan
                                @can('carga-relatorio')
                                <a class="dropdown-item" href="{{route('carga.relatorio')}}">Carga</a>
                                @endcan
                            </div>
                        </li>
                        <li class="nav-item dropdown active">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Gerencial
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @can('user-menu')
                                <a class="dropdown-item" href="{{route('user.index')}}"><i class="fas fa-user"></i>Usuários</a>
                                @endcan
                                @can('cargo-menu')
                                <a class="dropdown-item" href="{{route('cargo.index')}}"><i class="fas fa-folder-open"></i>Cargos</a>
                                @endcan
                                @can('unidade-menu')
                                <a class="dropdown-item" href="{{route('unidade.index')}}"><i class="fas fa-weight-hanging"></i>Unidades</a>
                                @endcan
                                @can('admin-admin')
                                <a class="dropdown-item" href="{{route('config.index')}}"><i class="fas fa-city"></i>Cidades</a>
                                @endcan
                            </div>
                        </li>
                        <li class="nav-item active" >
                            @include('sobre')
                        </li>
                    </ul>
                    @endguest

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown active">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i>Sair</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <a class="dropdown-item" href="{{route('user.mudarsenha')}}"><i class="fas fa-lock"></i>Mudar senha</a>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- Core -->
    <script src="{{asset('assets/vendor/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/vendor/js-cookie/js.cookie.js')}}"></script>


    <!-- Argon JS -->
    <script src="{{asset('assets/js/argon.min.js')}}"></script>

    <main class="py-4">
        @yield('content')
    </main>

    <footer>
        <div class="container text-center">
          <small><i class="fab fa-gripfire"></i>MasterGas ©Matheus Tiecher 2020</small>
      </div>
  </footer>

</body>
</html>
