<div class="accordion" id="accordionExample">
	<div class="card">
		<div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			<h5 class="mb-0"><i class="fas fa-box mr-2"></i>Itens da venda</h5>
		</div>
		<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
			<div class="table-responsive mt-3 mt-4">
				<table class="table table-sm table-striped ">
					<thead>
						<tr>
							<th>Produto</th>
							<th>Quantidade</th>
							<th>Preço</th>
							<th>Total</th>
							<th>Status</th>
							<th>Descrição</th>
							<th>Data</th>
						</tr>
					</thead>
					@foreach($venda->vendaitem as $i)
					<tr>
						<td>{{$i->produto->descricao}}</td>
						<td>{{number_format($i->quantidade, 2, ',', '.')}} {{$i->produto->unidade->sigla}}</td>
						<td>R$ {{number_format($i->valorvenda, 2, ',', '.')}}</td>
						<td>R$ {{number_format($i->valorvenda * $i->quantidade, 2, ',', '.')}}</td>
						<td>{{strtoupper($statusvendaitem[$i->status])}}</td>
						<td>{{strtoupper($i->descricao)}}</td>
						<td>{{date('d/m/Y : H:i', strtotime($i->updated_at))}}</td>
					</tr>
					@endforeach
				</table>
			</div>
		</div>
	</div>
</div>

