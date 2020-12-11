<button type="button" class="btn btn-sm btn-outline-dark mr-2" data-toggle="modal" data-target="#modalendereco-{{$endereco->id}}" data-placement="top" title="Clique para ver detalhadamente" role="button"><i class="fas fa-eye"></i></button>
<!-- Modal ver endereco -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalendereco-{{$endereco->id}}">
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
							<td>{{$endereco->id}}</td>
						</tr>
						<tr>
							<th>Rua</th>
							<td>{{$endereco->rua}}</td>
						</tr>
						<tr>
							<th>Número</th>
							<td>{{$endereco->numero}}</td>
						</tr>
						<tr>
							<th>Bairro</th>
							<td>{{$endereco->bairro}}</td>
						</tr>
						<tr>
							<th>Complemento</th>
							<td>{{$endereco->complemento}}</td>
						</tr>
						<tr>
							<th>CEP</th>
							<td>{{$endereco->cep}}</td>
						</tr>
						<tr>
							<th>Cidade</th>
							<td>{{$endereco->cidade->nome}}</td>
						</tr>
						<tr>
							<th>Estado</th>
							<td>{{$endereco->cidade->estado->nome}} - {{$endereco->cidade->estado->sigla}}</td>
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