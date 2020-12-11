<div class="accordion" id="accordionExample">
	<div class="card">
		<div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			<h5 class="mb-0"><i class="fas fa-box mr-2"></i> Itens totais da carga</h5>
		</div>
		<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
			<div class="table-responsive mt-3 mt-4">
				<table class="table table-sm table-striped ">
					<thead>
						<tr>
							<th>Produto</th>
							<th>Quantidade</th>
						</tr>
					</thead>
					@foreach($itens as $i)
					<tr>
						<td>{{$i->descricao}}</td>
						<td>{{number_format($i->quantidade, 2, ',', '.')}} {{$i->sigla}}</td>
					</tr>
					@endforeach
				</table>
			</div>
		</div>
	</div>
</div>

