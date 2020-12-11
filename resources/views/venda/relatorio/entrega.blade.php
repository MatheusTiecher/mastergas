<style type="text/css">  
	th {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		text-align: left;
		padding: 5px;
		font-size: 15px;
		border-bottom: 1px solid #ddd;
	}

	h1, h3, p, div{
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	}

	b {
		font-size: 18px;
	}

	td {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		text-align: left;
		padding: 5px;
		border-bottom: 1px solid #ddd;
	}

	.clearfix::after {
		content: "";
		clear: both;
		display: table;
	}

	.border {
		border-radius: 5px;
		border-top: 2px solid;
		border-bottom: 2px solid;
		border-left: 2px solid;
		border-right: 2px solid;
		padding-top: 5px;
		padding-left: 5px;
		padding-left: 10px;
		padding-right: 10px;
	}
</style>

<div class="border" style="background: #000;">
	<div class="clearfix">
		<div style="float: left; width: 8%;" align="right">
			<img style="height: 90px; width: 55px;" src="{{ asset('icon_branco.png') }}">
		</div>
		<div style="float: left; width: 22%;" align="left"> 
			<h1 style="color: #ffff;">JB GAS</h1>
		</div>
		<div style="float: left; width: 70%;" align="center">
			<p style="font-size: 20px; color: #ffff;"><b style="font-size: 20px; color: #ffff;">Endereço: </b> Centro, Planalto - PR, 85750-000 
				<br> <b style="font-size: 20px; color: #ffff;">Contato: </b> (46) 99973-0562</p>
			</div>
		</div>
	</div>
</div>
<br>
<div class="border" style="margin-top: 5px;">
	<h3 align="center">{{$title}}</h3>
</div>
<div align="right">
	Gerado em {{$data}} com {{$count}} registros 
</div>
<br>
<div style="margin-top: 5px;">
	@if(count($users) == 1)
	<h4 align="center">Usuário {{$users[0]->name}}</h4>
	@else
	<h4 align="center">Todos os usuários</h4>
	@endif
	<table style="width: 100%;">
		<thead style="position:fixed">			
			<tr>
				<th>ID</th>
				<th>USUÁRIO</th>
				<th>CLIENTE</th>
				<th>ENTREGADOR</th>
				<th>DATA ENTREGA</th>
			</tr>
		</thead>
		@foreach($vendas as $value)
		<?php 
		$venda = App\Models\Venda::find($value->ven_id);
		$ocorrencia = App\Models\OcorrenciaEntrega::find($value->oco_id);
		?>
		<tr>
			<td>{{$venda->id}}</td>
			<td>{{$venda->user->name}}</td>
			<td>{{$venda->cliente->nomerazao}}</td>
			<td>{{$ocorrencia->user->name}}</td>
			<td>{{$ocorrencia->dataagendada}}</td>
		</tr>
		@endforeach
	</table>
</div>

<script type="text/php">
	$font = $fontMetrics->getFont("Arial", "bold");
	$pdf->page_text(500, 820, "Página {PAGE_NUM}/{PAGE_COUNT}", $font, 10, array(0, 0, 0));
</script>