<style>  

	th {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		width: 20%;
		text-align: left;
		padding: 5px;
		font-size: 20px;
	}

	h1, p {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	}

	b {
		font-size: 18px;
	}

	td {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		width: 20%;
		text-align: left;
		padding: 5px;
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

	.total {
		float: right;
		width: 30%;
		margin-left: 5px;
		margin-right: 5px;
		margin-bottom: 5px;
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
	<div class="clearfix">
		<div style="float: left; width: 25%; padding-right: 5px;">
			<p><b>Carga #{{$carga->id}}</b></p>
		</div>
		<div style="float: left; width: 75%;">
			<p><b>Entregador: </b> {{App\User::withTrashed()->find($carga->user_id)->name}}</p>
		</div>
	</div>
</div>
<br>
<div class="border" style="margin-top: 5px;">
	<table style="width: 100%;">
		<tr>
			<th style="width: 55%;">Produto</th>
			<th style="width: 25%;">Quantidade</th>
			<th style="width: 20%;">Devolução</th>
		</tr>
		@foreach($itens as $item)
		<tr>
			<td style="width: 55%;">{{$item->descricao}}</td>
			<td style="width: 25%;">{{number_format($item->quantidade, 2, ',', '.')}} {{$item->sigla}}</td>
			<td style="width: 20%;">__________</td>
		</tr>
		@endforeach
	</table>
</div>
<br>
<div style="">
	<div style="margin-top: 20px;">
		<p><b>Anotação: </b></p>
		<div style="border-top: 2px solid;" align="center">
		</div>
		<br>
		<br>
		<div style="border-top: 2px solid;" align="center">
		</div>
		<br>
		<br>
		<div style="border-top: 2px solid;" align="center">
		</div>
		<br>
		<br>
		<div style="border-top: 2px solid;" align="center">
		</div>
		<br>
		<br>
		<div style="border-top: 2px solid;" align="center">
		</div>
		<br>
		<br>
		<div style="border-top: 2px solid;" align="center">
		</div>
		<br>
		<br>
		<div style="border-top: 2px solid;" align="center">
		</div>
		<br>
	</div>
	<br>
	<div style="margin-top: 60px; page-break-inside: avoid;">
		<div style="border-top: 2px solid; margin-left: 20%; margin-right: 20%;" align="center">
			<p>{{App\User::withTrashed()->find($carga->user_id)->name}}</p>
		</div>
	</div>
</div>

<script type="text/php">
	$font = $fontMetrics->getFont("Arial", "bold");
	$pdf->page_text(500, 820, "Página {PAGE_NUM}/{PAGE_COUNT}", $font, 10, array(0, 0, 0));
</script>