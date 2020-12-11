<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth::routes();
Auth::routes(['register' => false]);

Route::get('/', 'HomeController@index')->name('home')->middleware('auth');

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

// CLIENTE
Route::group(['middleware' => ['auth'],'prefix' => 'cliente'], function () {
	Route::get('/createpf',						['as'=>'cliente.createpf','uses'=>'ClienteController@createpf']);
	Route::get('/createpj',						['as'=>'cliente.createpj','uses'=>'ClienteController@createpj']);
	// ENDERECO
	Route::get('/createEndereco/{cliente}',		['as'=>'cliente.createEndereco','uses'=>'ClienteController@createEndereco']);
	Route::POST('/storeEndereco/{cliente}',		['as'=>'cliente.storeEndereco','uses'=>'ClienteController@storeEndereco']);
	Route::get('/editEndereco/{endereco}',		['as'=>'cliente.editEndereco','uses'=>'ClienteController@editEndereco']);
	Route::POST('/updateEndereco/{endereco}',	['as'=>'cliente.updateEndereco','uses'=>'ClienteController@updateEndereco']);
	Route::get('/destroyEndereco/{endereco}',	['as'=>'cliente.destroyEndereco','uses'=>'ClienteController@destroyEndereco']);
	Route::get('/info', 						['as'=>'cidade.info','uses'=>'ClienteController@info']);
	// CONSULTA CNPJ
	Route::get('/consultacnpj', 				['as'=>'cliente.consultacnpj','uses'=>'ClienteController@consultacnpj']);
	// DATATABLES
	Route::get('/showData',						['as'=>'cliente.showData','uses'=>'ClienteController@showData']);
	Route::get('/destroyTemp',					['as'=>'cliente.destroyTemp','uses'=>'ClienteController@destroyTemp']);
	Route::get('/forceDelete', 					['as'=>'cliente.forceDelete','uses'=>'ClienteController@forceDelete']);
	Route::get('/restore', 						['as'=>'cliente.restore','uses'=>'ClienteController@restore']);
	Route::get('/data', 						['as'=>'cliente.data','uses'=>'ClienteController@data']);
	// RELATORIO
	Route::get('/relatorio', 					['as'=>'cliente.relatorio','uses'=>'ClienteController@relatorio']);
	Route::POST('/relatorio',					['as'=>'cliente.relatoriostore','uses'=>'ClienteController@relatoriostore']);
});
Route::resource('cliente', 'ClienteController')->middleware('auth');

// FORNECEDOR
Route::group(['middleware' => ['auth'],'prefix' => 'fornecedor'], function () {
	Route::get('/createpf',						['as'=>'fornecedor.createpf','uses'=>'FornecedorController@createpf']);
	Route::get('/createpj',						['as'=>'fornecedor.createpj','uses'=>'FornecedorController@createpj']);
	// CONSULTA CNPJ
	Route::get('/consultacnpj', 				['as'=>'fornecedor.consultacnpj','uses'=>'FornecedorController@consultacnpj']);
	// DATATABLES
	Route::get('/showData',						['as'=>'fornecedor.showData','uses'=>'FornecedorController@showData']);
	Route::get('/destroyTemp',					['as'=>'fornecedor.destroyTemp','uses'=>'FornecedorController@destroyTemp']);
	Route::get('/forceDelete', 					['as'=>'fornecedor.forceDelete','uses'=>'FornecedorController@forceDelete']);
	Route::get('/restore', 						['as'=>'fornecedor.restore','uses'=>'FornecedorController@restore']);
	Route::get('/data', 						['as'=>'fornecedor.data','uses'=>'FornecedorController@data']);
	// RELATORIO
	Route::get('/relatorio', 					['as'=>'fornecedor.relatorio','uses'=>'FornecedorController@relatorio']);
	Route::POST('/relatorio',					['as'=>'fornecedor.relatoriostore','uses'=>'FornecedorController@relatoriostore']);
});
Route::resource('fornecedor', 'FornecedorController')->middleware('auth');

// PRODUTO
Route::group(['middleware' => ['auth'],'prefix' => 'produto'], function () {
	Route::get('/estoqueminimo', 				['as'=>'produto.estoqueminimo','uses'=>'ProdutoController@estoqueminimo']);
	// SELECT2
	Route::get('/infounidade', 					['as'=>'produto.infounidade','uses'=>'ProdutoController@infounidade']);
	// DATATABLES
	Route::get('/showData',						['as'=>'produto.showData','uses'=>'ProdutoController@showData']);
	Route::get('/destroyTemp',					['as'=>'produto.destroyTemp','uses'=>'ProdutoController@destroyTemp']);
	Route::get('/forceDelete', 					['as'=>'produto.forceDelete','uses'=>'ProdutoController@forceDelete']);
	Route::get('/restore', 						['as'=>'produto.restore','uses'=>'ProdutoController@restore']);
	Route::get('/data', 						['as'=>'produto.data','uses'=>'ProdutoController@data']);
	// RELATORIO
	Route::get('/relatorio', 					['as'=>'produto.relatorio','uses'=>'ProdutoController@relatorio']);
	Route::POST('/relatorio',					['as'=>'produto.relatoriostore','uses'=>'ProdutoController@relatoriostore']);
});
Route::resource('produto', 'ProdutoController')->middleware('auth');

// UNIDADE
Route::group(['middleware' => ['auth'],'prefix' => 'unidade'], function () {
	Route::get('/estoqueminimo', 				['as'=>'produto.estoqueminimo','uses'=>'ProdutoController@estoqueminimo']);
	// DATATABLES
	Route::get('/showData',						['as'=>'unidade.showData','uses'=>'UnidadeController@showData']);
	Route::get('/destroyTemp',					['as'=>'unidade.destroyTemp','uses'=>'UnidadeController@destroyTemp']);
	Route::get('/forceDelete', 					['as'=>'unidade.forceDelete','uses'=>'UnidadeController@forceDelete']);
	Route::get('/restore', 						['as'=>'unidade.restore','uses'=>'UnidadeController@restore']);
	Route::get('/data', 						['as'=>'unidade.data','uses'=>'UnidadeController@data']);
});
Route::resource('unidade', 'UnidadeController')->middleware('auth');

// ESTOQUE
Route::group(['middleware' => ['auth'],'prefix' => 'estoque'], function () {
	// SELECT2
	Route::get('/infoproduto', 					['as'=>'estoque.infoproduto','uses'=>'EstoqueController@infoproduto']);
	Route::get('/infofornecedor', 				['as'=>'estoque.infofornecedor','uses'=>'EstoqueController@infofornecedor']);
	// DATATABLES
	Route::get('/forceDelete', 					['as'=>'estoque.forceDelete','uses'=>'EstoqueController@forceDelete']);
	Route::get('/data', 						['as'=>'estoque.data','uses'=>'EstoqueController@data']);
	// RELATORIO
	Route::get('/relatorio', 					['as'=>'estoque.relatorio','uses'=>'EstoqueController@relatorio']);
	Route::POST('/relatorio',					['as'=>'estoque.relatoriostore','uses'=>'EstoqueController@relatoriostore']);
});
Route::resource('estoque', 'EstoqueController')->middleware('auth');

// VENDA
Route::group(['middleware' => ['auth'],'prefix' => 'venda'], function () {
	Route::get('indexagendado',					['as'=>'venda.indexagendado','uses'=>'VendaController@indexagendado']);
	Route::get('indexandamento',				['as'=>'venda.indexandamento','uses'=>'VendaController@indexandamento']);
	Route::get('{venda}/vendaitem',				['as'=>'venda.vendaitem','uses'=>'VendaController@vendaitem']);
	Route::POST('{venda}/vendaitemstore',		['as'=>'venda.vendaitemstore','uses'=>'VendaController@vendaitemstore']);
	Route::get('{vendaitem}/destroyitem',		['as'=>'venda.destroyitem','uses'=>'VendaController@destroyitem']);
	Route::POST('{venda}/atualizavenda',		['as'=>'venda.atualizavenda','uses'=>'VendaController@atualizavenda']);
	Route::GET('{venda}/createentrega',			['as'=>'venda.createentrega','uses'=>'VendaController@createentrega']);
	Route::POST('{venda}/agendarentrega',		['as'=>'venda.agendarentrega','uses'=>'VendaController@agendarentrega']);
	Route::GET('{venda}/retirarentrega',		['as'=>'venda.retirarentrega','uses'=>'VendaController@retirarentrega']);
	Route::get('{venda}/createfinal',			['as'=>'venda.createfinal','uses'=>'VendaController@createfinal']);
	Route::POST('{venda}/storefinal',			['as'=>'venda.storefinal','uses'=>'VendaController@storefinal']);
	Route::get('{venda}/detalhar',				['as'=>'venda.detalhar','uses'=>'VendaController@detalhar']);
	Route::get('{venda}/estornar',				['as'=>'venda.estornar','uses'=>'VendaController@estornar']);
	Route::get('{venda}/devolver',				['as'=>'venda.devolver','uses'=>'VendaController@devolver']);
	Route::POST('{venda}/storedevolver',		['as'=>'venda.storedevolver','uses'=>'VendaController@storedevolver']);
	Route::get('{venda}/trocar',				['as'=>'venda.trocar','uses'=>'VendaController@trocar']);
	Route::POST('{venda}/storetrocar',			['as'=>'venda.storetrocar','uses'=>'VendaController@storetrocar']);
	Route::get('{venda}/devolvercarga',			['as'=>'venda.devolvercarga','uses'=>'VendaController@devolvercarga']);
	Route::POST('{venda}/storedevolvercarga',	['as'=>'venda.storedevolvercarga','uses'=>'VendaController@storedevolvercarga']);
	Route::get('{venda}/devolvercarga',			['as'=>'venda.devolvercarga','uses'=>'VendaController@devolvercarga']);
	Route::POST('{venda}/storedevolvercarga',	['as'=>'venda.storedevolvercarga','uses'=>'VendaController@storedevolvercarga']);
	Route::POST('{venda}/updatedesconto',		['as'=>'venda.updatedesconto','uses'=>'VendaController@updatedesconto']);
	// PDF
	Route::get('{venda}/vendapdf', 				['as'=>'venda.vendapdf','uses'=>'VendaController@vendapdf']);
	// SELECT2
	Route::get('/infocliente', 					['as'=>'venda.infocliente','uses'=>'VendaController@infocliente']);
	Route::get('/infoproduto', 					['as'=>'venda.infoproduto','uses'=>'VendaController@infoproduto']);
	Route::get('/infoendereco/{venda}',			['as'=>'venda.infoendereco','uses'=>'VendaController@infoendereco']);
	Route::get('/infousuario', 					['as'=>'venda.infousuario','uses'=>'VendaController@infousuario']);
	// AJAX
	Route::get('/proautocomplete', 				['as'=>'venda.proautocomplete','uses'=>'VendaController@proautocomplete']);
	// DATATABLES
	Route::get('/data', 						['as'=>'venda.data','uses'=>'VendaController@data']);
	Route::get('/dataagendado', 				['as'=>'venda.dataagendado','uses'=>'VendaController@dataagendado']);
	Route::get('/dataandamento', 				['as'=>'venda.dataandamento','uses'=>'VendaController@dataandamento']);
	// RELATORIO
	Route::get('/relatorio', 					['as'=>'venda.relatorio','uses'=>'VendaController@relatorio']);
	Route::POST('/relatorio',					['as'=>'venda.relatoriostore','uses'=>'VendaController@relatoriostore']);
});
Route::resource('venda', 'VendaController')->middleware('auth');

// ENTREGA - GERENCIAR ENTREGA
Route::group(['middleware' => ['auth'],'prefix' => 'entrega'], function () {
	Route::get('{entrega}/gerenciar',			['as'=>'entrega.gerenciar','uses'=>'EntregaController@gerenciar']);
	Route::get('{entrega}/createocorrencia',	['as'=>'entrega.createocorrencia','uses'=>'EntregaController@createocorrencia']);
	Route::POST('{entrega}/storeocorrencia',	['as'=>'entrega.storeocorrencia','uses'=>'EntregaController@storeocorrencia']);
	Route::POST('{entrega}/confirmaentrega',	['as'=>'entrega.confirmaentrega','uses'=>'EntregaController@confirmaentrega']);
	// ADICIONAR ENDERECO NA ENTREGA VENDA E OCORRENCIA ENTREGA
	Route::get('{venda}/createEnderecoVenda', 	['as'=>'entrega.createEnderecoVenda','uses'=>'EntregaController@createEnderecoVenda']);
	Route::POST('{venda}/storeEnderecoVenda', 	['as'=>'entrega.storeEnderecoVenda','uses'=>'EntregaController@storeEnderecoVenda']);
	Route::get('{entrega}/createEndereco', 		['as'=>'entrega.createEndereco','uses'=>'EntregaController@createEndereco']);
	Route::POST('{entrega}/storeEndereco', 		['as'=>'entrega.storeEndereco','uses'=>'EntregaController@storeEndereco']);

});
Route::resource('entrega', 'EntregaController')->middleware('auth');

// CAIXA 
Route::group(['middleware' => ['auth'],'prefix' => 'caixa'], function () {
	Route::get('/indexuser',					['as'=>'caixa.indexuser','uses'=>'CaixaController@indexuser']);
	Route::POST('{caixa}/entrada',				['as'=>'caixa.entrada','uses'=>'CaixaController@entrada']);
	Route::POST('{caixa}/saida',				['as'=>'caixa.saida','uses'=>'CaixaController@saida']);
	Route::POST('{caixa}/saidauser',			['as'=>'caixa.saidauser','uses'=>'CaixaController@saidauser']);
	// RELATORIO
	Route::get('/relatorio', 					['as'=>'caixa.relatorio','uses'=>'CaixaController@relatorio']);
	Route::POST('/relatorio',					['as'=>'caixa.relatoriostore','uses'=>'CaixaController@relatoriostore']);
});
Route::resource('caixa', 'CaixaController')->middleware('auth');

// Rota
Route::group(['middleware' => ['auth'],'prefix' => 'rota'], function () {
	Route::get('/index',						['as'=>'rota.index','uses'=>'RotaController@index']);
	Route::get('/create',						['as'=>'rota.create','uses'=>'RotaController@create']);
	Route::POST('/store',						['as'=>'rota.store','uses'=>'RotaController@store']);
	Route::get('{rota}/edit',					['as'=>'rota.edit','uses'=>'RotaController@edit']);
	Route::PUT('{rota}/update',					['as'=>'rota.update','uses'=>'RotaController@update']);
	// SELECT 2
	Route::get('/infocidade', 					['as'=>'rota.infocidade','uses'=>'RotaController@infocidade']);
	// DATATABLES
	Route::get('/showData',						['as'=>'rota.showData','uses'=>'RotaController@showData']);
	Route::get('/destroyTemp',					['as'=>'rota.destroyTemp','uses'=>'RotaController@destroyTemp']);
	Route::get('/forceDelete', 					['as'=>'rota.forceDelete','uses'=>'RotaController@forceDelete']);
	Route::get('/restore', 						['as'=>'rota.restore','uses'=>'RotaController@restore']);
	Route::get('/data', 						['as'=>'rota.data','uses'=>'RotaController@data']);
	// RELATORIO
	Route::get('/relatorio', 					['as'=>'rota.relatorio','uses'=>'RotaController@relatorio']);
	Route::POST('/relatorio',					['as'=>'rota.relatoriostore','uses'=>'RotaController@relatoriostore']);
});
// Route::resource('rota', 'RotaController')->middleware('auth');

// CARGA 
Route::group(['middleware' => ['auth'],'prefix' => 'carga'], function () {
	Route::get('{carga}/cargaitem',				['as'=>'carga.cargaitem','uses'=>'CargaController@cargaitem']);
	Route::POST('{carga}/cargaitemstore',		['as'=>'carga.cargaitemstore','uses'=>'CargaController@cargaitemstore']);
	Route::get('{cargaitem}/destroyitem',		['as'=>'carga.destroyitem','uses'=>'CargaController@destroyitem']);
	Route::POST('{carga}/atualizacarga',		['as'=>'carga.atualizacarga','uses'=>'CargaController@atualizacarga']);
	Route::get('{carga}/detalhar',				['as'=>'carga.detalhar','uses'=>'CargaController@detalhar']);
	Route::get('{carga}/estornar',				['as'=>'carga.estornar','uses'=>'CargaController@estornar']);
	Route::get('{carga}/finalizacarga', 		['as'=>'carga.finalizacarga','uses'=>'CargaController@finalizacarga']);
	Route::get('{carga}/venda', 				['as'=>'carga.venda','uses'=>'CargaController@venda']);
	Route::POST('{carga}/storevenda', 			['as'=>'carga.storevenda','uses'=>'CargaController@storevenda']);
	Route::get('{carga}/detalharvenda', 		['as'=>'carga.detalharvenda','uses'=>'CargaController@detalharvenda']);
	// PDF
	Route::get('{carga}/cargapdf', 				['as'=>'carga.cargapdf','uses'=>'CargaController@cargapdf']);
	// AJAX
	Route::get('/proautocomplete', 				['as'=>'carga.proautocomplete','uses'=>'CargaController@proautocomplete']);
	// SELECT 2
	Route::get('/inforota', 					['as'=>'carga.inforota','uses'=>'CargaController@inforota']);
	Route::get('/infoproduto', 					['as'=>'carga.infoproduto','uses'=>'CargaController@infoproduto']);
	// DATATABLES
	Route::get('/data', 						['as'=>'carga.data','uses'=>'CargaController@data']);
	// RELATORIO
	Route::get('/relatorio', 					['as'=>'carga.relatorio','uses'=>'CargaController@relatorio']);
	Route::POST('/relatorio',					['as'=>'carga.relatoriostore','uses'=>'CargaController@relatoriostore']);
});
Route::resource('carga', 'CargaController')->middleware('auth');

// CONFIG CIDADES
Route::group(['middleware' => ['auth'],'prefix' => 'config'], function () {
	Route::get('/', 							['as'=>'config.index','uses'=>'ConfigController@index']);
	Route::get('/estado', 						['as'=>'config.estado','uses'=>'ConfigController@estado']);
	Route::get('/cidade', 						['as'=>'config.cidade','uses'=>'ConfigController@cidade']);
	Route::get('/cidades', 						['as'=>'config.cidades','uses'=>'ConfigController@cidades']);
});

// MENSAGEM
// Route::group(['middleware' => ['auth'],'prefix' => 'mensagem'], function () {
// 	Route::get('/mensagemenvio', 				['as'=>'mensagem.envio','uses'=>'MensagemController@sendWhatsAppMessage']);
// 	Route::get('/teste', 						['as'=>'mensagem.teste','uses'=>'MensagemController@teste']);
// 	// SELECT2
// 	Route::get('/infoproduto', 					['as'=>'mensagem.infoproduto','uses'=>'MensagemController@infoproduto']);
// 	Route::get('/info', 						['as'=>'mensagem.infoproduto','uses'=>'MensagemController@infoproduto']);
// 	// DATATABLES
// 	Route::get('/showData',						['as'=>'mensagem.showData','uses'=>'MensagemController@showData']);
// 	Route::get('/destroyTemp',					['as'=>'mensagem.destroyTemp','uses'=>'MensagemController@destroyTemp']);
// 	Route::get('/forceDelete', 					['as'=>'mensagem.forceDelete','uses'=>'MensagemController@forceDelete']);
// 	Route::get('/restore', 						['as'=>'mensagem.restore','uses'=>'MensagemController@restore']);
// 	Route::get('/data', 						['as'=>'mensagem.data','uses'=>'MensagemController@data']);
// });
// Route::resource('mensagem', 'MensagemController')->middleware('auth');

// CARGO
Route::group(['middleware' => ['auth'],'prefix' => 'cargo'], function () {
	Route::get('/{cargo}/permissao',			['as'=>'cargo.permissao','uses'=>'CargoController@permissao']);
	Route::POST('/{cargo}/permissaostore',		['as'=>'cargo.permissaostore','uses'=>'CargoController@permissaostore']);
	// DATATABLES
	Route::get('/showData',						['as'=>'cargo.showData','uses'=>'CargoController@showData']);
	Route::get('/destroyTemp',					['as'=>'cargo.destroyTemp','uses'=>'CargoController@destroyTemp']);
	Route::get('/forceDelete', 					['as'=>'cargo.forceDelete','uses'=>'CargoController@forceDelete']);
	Route::get('/restore', 						['as'=>'cargo.restore','uses'=>'CargoController@restore']);
	Route::get('/data', 						['as'=>'cargo.data','uses'=>'CargoController@data']);
});
Route::resource('cargo', 'CargoController')->middleware('auth');

// USER
Route::group(['middleware' => ['auth'],'prefix' => 'user'], function () {
	Route::get('/{user}/cargo',					['as'=>'user.cargo','uses'=>'UserController@cargo']);
	Route::POST('/{user}/cargostore',			['as'=>'user.cargostore','uses'=>'UserController@cargostore']);
	Route::get('/mudarsenha',					['as'=>'user.mudarsenha','uses'=>'UserController@mudarsenha']);
	Route::PUT('/mudarsenhastore',				['as'=>'user.mudarsenhastore','uses'=>'UserController@mudarsenhastore']);
	Route::PUT('/{user}/senhaupdate',			['as'=>'user.senhaupdate','uses'=>'UserController@senhaupdate']);
	// DATATABLES
	Route::get('/showData',						['as'=>'user.showData','uses'=>'UserController@showData']);
	Route::get('/destroyTemp',					['as'=>'user.destroyTemp','uses'=>'UserController@destroyTemp']);
	Route::get('/forceDelete', 					['as'=>'user.forceDelete','uses'=>'UserController@forceDelete']);
	Route::get('/restore', 						['as'=>'user.restore','uses'=>'UserController@restore']);
	Route::get('/data', 						['as'=>'user.data','uses'=>'UserController@data']);
});
Route::resource('user', 'UserController')->middleware('auth');