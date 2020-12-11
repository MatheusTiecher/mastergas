<a href="#modalendereco-{{$o->endereco->id}}" class="card-link mr-4 mb-2" data-toggle="modal" data-target="#modalendereco-{{$o->endereco->id}}" data-placement="top" title="Clique para ver detalhadamente">Detalhar Endereço</a>
<!-- Modal ver endereco -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalendereco-{{$o->endereco->id}}">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Endereço detalhado</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="card-body table-responsive">
					<table class="table table-sm table-striped table-bordered table-striped">
						<tr>
							<th>ID</th>
							<td>{{$o->endereco->id}}</td>
						</tr>
						<tr>
							<th>Rua</th>
							<td>{{$o->endereco->rua}}</td>
						</tr>
						<tr>
							<th>Número</th>
							<td>{{$o->endereco->numero}}</td>
						</tr>
						<tr>
							<th>Bairro</th>
							<td>{{$o->endereco->bairro}}</td>
						</tr>
						<tr>
							<th>Complemento</th>
							<td>{{$o->endereco->complemento}}</td>
						</tr>
						<tr>
							<th>CEP</th>
							<td>{{$o->endereco->cep}}</td>
						</tr>
						<tr>
							<th>Cidade</th>
							<td>{{$o->endereco->cidade->nome}}</td>
						</tr> 
						<tr>
							<th>Estado</th>
							<td>{{$o->endereco->cidade->estado->nome}} - {{$o->endereco->cidade->estado->sigla}}</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"  data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i>  Voltar</button>
			</div>
		</div>
	</div>
</div>