<div class="card col-xs-12 col-md-12 col-lg-4">
	<div class="card-body">
		<h5 class="card-title"><i class="fas fa-info-circle"></i> Informações</h5>
		<p class="card-text">
			<b>Entregador: </b>{{App\User::withTrashed()->find($carga->user_id)->name}}
			@if(isset($carga->observacao))
			<br><b>Observação: </b>{{$carga->observacao}}
			@endif
			<br><b>Data: </b>{{date('d/m/Y : H:i', strtotime($carga->created_at))}}
		</p>
	</div>
</div>
<div class="card col-xs-12 col-md-12 col-lg-4">
	<div class="card-body">
		<h5 class="card-title"><i class="fas fa-info-circle"></i> Itens em andamento</h5>
		<p class="card-text">
			<div class="table-responsive mt-3 mt-4">
				<table class="table table-sm table-striped ">
					<thead>
						<tr>
							<th>Produto</th>
							<th>Quantidade</th>
						</tr>
					</thead>
					@foreach($andamento as $i)
					<tr>
						<td>{{$i->descricao}}</td>
						<td>{{number_format($i->quantidade, 2, ',', '.')}} {{$i->sigla}}</td>
					</tr>
					@endforeach
				</table>
			</div>
		</p>
	</div>
</div>
<div class="card col-xs-12 col-md-12 col-lg-4">
	<div class="card-body">
		<h5 class="card-title"><i class="fas fa-info-circle"></i> Itens vendidos</h5>
		<p class="card-text">
			<div class="table-responsive mt-3 mt-4">
				<table class="table table-sm table-striped ">
					<thead>
						<tr>
							<th>Produto</th>
							<th>Quantidade</th>
						</tr>
					</thead>
					@foreach($vendido as $i)
					<tr>
						<td>{{$i->descricao}}</td>
						<td>{{number_format($i->quantidade, 2, ',', '.')}} {{$i->sigla}}</td>
					</tr>
					@endforeach
				</table>
			</div>
		</p>
	</div>
</div>