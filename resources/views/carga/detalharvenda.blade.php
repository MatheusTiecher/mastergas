@extends('layouts.app')

@section('content')

<div class="container-fluid">	
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card card-frame"> 
				<div class=" card-body">
					<h1>Vendas - Carga #{{$carga->id}}</h1>
					<hr class="my-4">
					<div class="row justify-content-left">
						<!-- BOTAO VOLTAR -->
						<a type="button" href="{{route('carga.detalhar', $carga)}}" class="btn btn-secondary ml-2" data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i> Voltar</a>
					</div>	
					<hr class="my-4">
					@if(count($carga->venda) > 0)
					@foreach($carga->venda as $venda)
					<div class="row justify-content-center">
						<div class="card col-12">
							<div class="card-body">
								<h1>Vendas - Rota {{App\Models\Rota::withTrashed()->find($venda->rota_id)->nome}}</h1>
								<a type="button" href="{{route('venda.detalhar', $venda)}}" class="btn btn-outline-default mb-2" data-toggle="tooltip" title="Detalhar Venda"><i class="far fa-list-alt"></i> Detalhar Venda</a>
								<div class="accordion" id="accordionExample">
									<div class="card">
										<div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne-{{$venda->id}}" aria-expanded="true" aria-controls="collapseOne">
											<h5 class="mb-0"><i class="fas fa-box mr-2"></i> Itens da venda</h5>
										</div>
										<div id="collapseOne-{{$venda->id}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
											<div class="table-responsive mt-3 mt-4">
												<table class="table table-sm table-striped ">
													<thead>
														<tr>
															<th>Produto</th>
															<th>Quantidade</th>
															<th>Preço</th>
															<th>Total</th>
															<th>Status</th>
														</tr>
													</thead>
													@foreach($venda->vendaitem as $i)
													<tr>
														<td>{{App\Models\Produto::withTrashed()->find($i->produto_id)->descricao}}</td>
														<td>{{number_format($i->quantidade, 2, ',', '.')}} {{App\Models\Unidade::withTrashed()->find(App\Models\Produto::withTrashed()->find($i->produto_id)->unidade_id)->sigla}}</td>
														<td>R$ {{number_format($i->valorvenda, 2, ',', '.')}}</td>
														<td>R$ {{number_format($i->valorvenda * $i->quantidade, 2, ',', '.')}}</td>
														<td>{{strtoupper($statusvendaitem[$i->status])}}</td>
													</tr>
													@endforeach
												</table>
											</div>
											<hr  class="my-4">
											<div class="card-body" align="right">
												<p class="card-text">
													<input type="hidden" name="bubtotal" value="{{$total = 0.00}}">
													@foreach($venda->vendaitem as $key => $value)
													@if($value->status != 4)
													<input type="hidden" name="bubtotal" value="{{$total += $value->valorvenda * $value->quantidade}}">
													@endif
													@endforeach
													Subtotal R$ {{number_format($total, 2, ',', '.')}}
													<br>Desconto R$ {{number_format($venda->desconto, 2, ',', '.')}} 
													<hr class="mb-2 mt-0">
													Total R$ {{number_format($total - $venda->desconto, 2, ',', '.')}} 
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<hr class="my-4">
					@endforeach
					@else
					<h1 align="center">Nenhuma venda gerada!</h1>
					@endif	
				</div>
			</div>
		</div>
	</div>
</div>

@include('css.notify')
@include('scripts.notify')
@include('scripts.notify_js')

@endsection