<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card card-frame">
				<div class=" card-body">
					@can('cliente-endereco-create')
					<h1>Endereços do cliente <a href="{{route('cliente.createEndereco', $cliente)}}" type="button" title="Clique para adicionar um novo endereço" class="btn btn-sm btn-outline-dark ml-2"><i class="fas fa-map-marker-alt"></i> Adicionar</a></h1>
					@endcan
					<div class="table-responsive">
						@if(count($cliente->endereco) > 0)
						<div>
							<table class="table align-items-center"> 
								<thead class="thead-light">
									<tr>
										<th>Cidade</th>
										<th>Rua</th>
										<th>Bairro</th>
										<th>N°</th>
										<th>Ações</th>
									</tr>
								</thead>
								<tbody class="list">
									@foreach($cliente->endereco as $endereco)
									<tr>
										<td>{{$endereco->cidade->nome}}/{{$endereco->cidade->estado->sigla}}</td>
										<td>{{$endereco->rua}}</td>
										<td>{{$endereco->bairro}}</td>
										<td>{{$endereco->numero}}</td>
										<td>
											@can('cliente-endereco-show')
											@include('cliente.modal.show')
											@endcan
											@can('cliente-endereco-edit')
											<a class="btn btn-outline-info btn-sm edit mt-1" data-toggle="tooltip" title="Editar" href="{{route('cliente.editEndereco',$endereco)}}"><i class="fas fa-pencil-alt"></i></a>
											@endcan
											@can('cliente-endereco-del-perm')
											<a class="btn btn-outline-danger btn-sm del-perm mt-1" data-toggle="tooltip" title="Exclusão Permanente" href="{{route('cliente.destroyEndereco',$endereco)}}" id="del-perm" ><i class="fas fa-fire"></i></a>
											@endcan
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						@else
						<h3 align="center">Cliente não tem nenhum endereço cadastrado!</h3>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

