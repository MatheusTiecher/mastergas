@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-frame">
				<div class=" card-body">
					<h1>Estoque mínimo</h1>
					<div class="table-responsive py-4">
						@if(count($produtos) > 0)
						<table class="table table-ordered table-hover" >
							<thead>
								<tr>
									<th>#</th>
									<th>Produto</th>
									<th>Estoque mínimo</th>
									<th>Estoque</th>
								</tr>				
							</thead>
							<tbody>
								@foreach($produtos as $produto)
								<tr> 
									<td>{{$produto->id}}</td>
									<td>{{$produto->descricao}}</td>
									<td>{{number_format($produto->minimo, 2, ',', '.')}} {{strtoupper($produto->sigla)}}</td>
									<td>{{number_format($produto->res, 2, ',', '.')}} {{strtoupper($produto->sigla)}}</td>
								</tr>
								@endforeach 
							</tbody>			
						</table>
						@else
						<h3 align="center">Nenhum produto com estoque mínimo!</h3>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
