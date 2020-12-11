@extends('layouts.app')

@section('content')

<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-10">
			<div class="card border">
				<div class="card-body table-responsive">
					<h1 class="card-title">Cidades</h1>
					<div class="form-group">
						<form class="form-inline" action="{{route('config.cidades')}}" method="GET">
							<input class="form-control mr-sm-2" type="search" name="pesquisa" placeholder="Pesquisar" aria-label="Pesquisar">
							<button class="btn btn-outline-dark my-2 my-sm-0" type="submit">Pesquisar</button>
						</form>
					</div>
					<table class="table table-ordered table-hover" >
						<thead>
							<tr>
								<th>Código</th>
								<th>Nome</th>
								<th>Estado</th>
							</tr>				
						</thead>
						<tbody>
							@foreach($cidades as $cidade)
							<tr> 
								<td>{{$cidade->id}}</td>
								<td>{{$cidade->nome}}</td>
								<td>{{$cidade->estado->nome}} / {{$cidade->estado->sigla}}</td>
							</tr>
							@endforeach
						</tbody>			
					</table>
					<div class="pagination justify-content-center card-footer">
						{{$cidades->links()}}
						<!-- {!! $cidades->render() !!} -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection