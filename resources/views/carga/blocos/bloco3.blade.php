<!-- BOTAO VOLTAR -->
<a type="button" href="{{route('carga.index')}}" class="btn btn-secondary mb-2" data-toggle="tooltip" title="Voltar"><i class="fas fa-arrow-circle-left"></i> Voltar</a>

<!-- BOTAO ESTORNAR -->
@if($carga->status == 0)
@can('carga-estornar')
<a type="button" id="estornar" href="{{route('carga.estornar', $carga)}}" class="btn btn-outline-danger mb-2" data-toggle="tooltip" title="Estornar"><i class="fas fa-history"></i> Estornar</a>
@endcan

<!-- BOTAO ADICIONAR ITENS -->
@can('carga-create')
<a type="button" href="{{route('carga.cargaitem', $carga)}}" class="btn btn-outline-success mb-2" data-toggle="tooltip" title="Adicionar itens"><i class="fas fa-shopping-cart"></i> Adicionar itens</a>
@endcan
@endif

<!-- BOTAO DOWLOAD PDF -->
@if($carga->status != 4)
<a type="button" href="{{route('carga.cargapdf', $carga)}}" target="_blank" class="btn btn-outline-default mb-2" data-toggle="tooltip" title="Download - Visualização em PDF"><i class="fas fa-receipt"></i> Download</a>
@endif

@if($carga->status == 0 || $carga->status == 1)
<!-- BOTAO GERAR VENDA -->
@can('carga-gerar-venda')
<a type="button" href="{{route('carga.venda', $carga)}}" class="btn btn-default mb-2" data-toggle="tooltip" title="Gerar Venda"><i class="far fa-list-alt"></i> Gerar Venda</a>
@endcan
@endif

@if($carga->status == 1 || $carga->status == 3)
<!-- BOTAO DIRECIONA PARA DETALHAR VENDAS -->
@can('carga-detalhar-venda')
<a type="button" href="{{route('carga.detalharvenda', $carga)}}" class="btn btn-default mb-2" data-toggle="tooltip" title="Detalhar Vendas"><i class="far fa-list-alt"></i> Detalhar Vendas</a>
@endcan
@endif

@if($carga->status == 0 || $carga->status == 1)
<!-- BOTAO FINALIZAR CARGA -->
@can('carga-finalizar')
<a type="button" id="finalizacarga" href="{{route('carga.finalizacarga', $carga)}}" class="btn btn-default mb-2" data-toggle="tooltip" title="Finalizar Carga"><i class="far fa-list-alt"></i> Finalizar Carga</a>
@endcan
@endif
