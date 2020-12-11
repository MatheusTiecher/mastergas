<!-- BOTÂO VOLTAR -->
<a type="button" href="{{route('venda.index')}}" class="btn btn-secondary mb-2" data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i> Voltar</a>

<!-- BOTÂO ESTORNAR -->
@if($venda->status == 0 || $venda->status == 1)
@can('venda-estornar')
<a type="button" id="estornar" href="{{route('venda.estornar', $venda)}}" class="btn btn-danger mb-2" data-toggle="tooltip" title="Estornar"><i class="fas fa-history"></i> Estornar</a>
@endcan
@endif

<!-- BOTÂO TROCAR/DEVOLVER -->
@if($venda->status == 2)
@can('venda-trocar')
<a type="button" href="{{route('venda.trocar', $venda)}}" class="btn btn-danger mb-2" data-toggle="tooltip" title="Trocar"><i class="fas fa-exchange-alt"></i> Trocar</a>
@endcan
@can('venda-devolver')
<a type="button" href="{{route('venda.devolver', $venda)}}" class="btn btn-danger mb-2" data-toggle="tooltip" title="Devolver"><i class="fas fa-sync-alt"></i> Devolver</a>
@endcan
@endif

<!-- BOTÂO CONTINUAR VENDA -->
@if($venda->status == 0)
@can('venda-create')
<a type="button" href="{{route('venda.vendaitem', $venda)}}" class="btn btn-success mb-2" data-toggle="tooltip" title="Continuar Venda"><i class="fas fa-shopping-cart"></i> Continuar Venda</a>
@endcan
@endif

<!-- BOTÂO RECIBO -->
@if(($venda->status != 0 && $venda->status != 3) && $venda->carga == null)
<a type="button" href="{{route('venda.vendapdf', $venda)}}" class="btn btn-default mb-2" data-toggle="tooltip" title="Recibo" target="_blank"><i class="fas fa-receipt"></i> Recibo</a>
@endif

@if(!empty($venda->carga))
@if($venda->carga->status == 1)
@can('carga-create')
@include('venda.modal.desconto')
@endcan
@can('carga-venda-devolver')
<a type="button" href="{{route('venda.devolvercarga', $venda)}}" class="btn btn-danger mb-2" data-toggle="tooltip" title="Devolver para carga"><i class="fas fa-sync-alt"></i> Devolver carga</a>
@endcan
@endif
@can('carga-detalhar')
<a type="button" href="{{route('carga.detalhar', $venda->carga)}}" class="btn btn-default mb-2" data-toggle="tooltip" title="Detalhar Carga"><i class="fas fa-truck"></i> Detalhar Carga</a>
@endcan
@endif