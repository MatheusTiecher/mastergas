<?php

use Illuminate\Database\Seeder;
use App\Models\Permissao;

class PermissaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // ADMIN
      $value = Permissao::firstOrCreate([
        'nome' => 'admin-admin',
        'descricao' => '[ Admin ] Admin',
        'modulo' => 'admin',
      ]);

      // CLIENTE 
      $value = Permissao::firstOrCreate([
        'nome' => 'cliente-menu',
        'descricao' => '[ Clientes ] Menu',
        'modulo' => 'cliente',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cliente-create',
        'descricao' => '[ Clientes ] Criar',
        'modulo' => 'cliente',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cliente-edit',
        'descricao' => '[ Clientes ] Editar',
        'modulo' => 'cliente',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cliente-delete',
        'descricao' => '[ Clientes ] Excluir',
        'modulo' => 'cliente',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cliente-del-perm',
        'descricao' => '[ Clientes ] Excluir Permanente',
        'modulo' => 'cliente',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cliente-restore',
        'descricao' => '[ Clientes ] Restaurar',
        'modulo' => 'cliente',
      ]);        

      $value = Permissao::firstOrCreate([
        'nome' => 'cliente-show',
        'descricao' => '[ Clientes ] Visualizar',
        'modulo' => 'cliente',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cliente-endereco-menu',
        'descricao' => '[ Clientes Endereços ] Menu',
        'modulo' => 'cliente',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cliente-endereco-create',
        'descricao' => '[ Clientes Endereços ] Criar',
        'modulo' => 'cliente',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cliente-endereco-edit',
        'descricao' => '[ Clientes Endereços ] Editar',
        'modulo' => 'cliente',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cliente-endereco-del-perm',
        'descricao' => '[ Clientes Endereços ] Excluir Permanente',
        'modulo' => 'cliente',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cliente-endereco-show',
        'descricao' => '[ Clientes Endereços ] Visualizar',
        'modulo' => 'cliente',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cliente-relatorio',
        'descricao' => '[ Clientes ] Gerar Relatório',
        'modulo' => 'cliente',
      ]);

      // FORNECEDOR 
      $value = Permissao::firstOrCreate([
        'nome' => 'fornecedor-menu',
        'descricao' => '[ Fornecedor ] Menu',
        'modulo' => 'fornecedor',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'fornecedor-create',
        'descricao' => '[ Fornecedor ] Criar',
        'modulo' => 'fornecedor',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'fornecedor-edit',
        'descricao' => '[ Fornecedor ] Editar',
        'modulo' => 'fornecedor',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'fornecedor-delete',
        'descricao' => '[ Fornecedor ] Excluir',
        'modulo' => 'fornecedor',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'fornecedor-del-perm',
        'descricao' => '[ Fornecedor ] Excluir Permanente',
        'modulo' => 'fornecedor',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'fornecedor-show',
        'descricao' => '[ Fornecedor ] Visualizar',
        'modulo' => 'fornecedor',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'fornecedor-restore',
        'descricao' => '[ Fornecedor ] Restaurar',
        'modulo' => 'fornecedor',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'fornecedor-relatorio',
        'descricao' => '[ Fornecedor ] Gerar Relatório',
        'modulo' => 'fornecedor',
      ]);

      // ESTOQUE 
      $value = Permissao::firstOrCreate([
        'nome' => 'estoque-menu',
        'descricao' => '[ Estoque ] Menu',
        'modulo' => 'estoque',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'estoque-create',
        'descricao' => '[ Estoque ] Criar',
        'modulo' => 'estoque',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'estoque-edit',
        'descricao' => '[ Estoque ] Editar',
        'modulo' => 'estoque',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'estoque-del-perm',
        'descricao' => '[ Estoque ] Excluir Permanente',
        'modulo' => 'estoque',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'estoque-relatorio',
        'descricao' => '[ Estoque ] Gerar Relatório',
        'modulo' => 'estoque',
      ]);

      // PRODUTO
      $value = Permissao::firstOrCreate([
        'nome' => 'produto-menu',
        'descricao' => '[ Produto ] Menu',
        'modulo' => 'produto',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'produto-create',
        'descricao' => '[ Produto ] Criar',
        'modulo' => 'produto',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'produto-edit',
        'descricao' => '[ Produto ] Editar',
        'modulo' => 'produto',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'produto-delete',
        'descricao' => '[ Produto ] Excluir',
        'modulo' => 'produto',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'produto-del-perm',
        'descricao' => '[ Produto ] Excluir Permanente',
        'modulo' => 'produto',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'produto-show',
        'descricao' => '[ Produto ] Visualizar',
        'modulo' => 'produto',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'produto-restore',
        'descricao' => '[ Produto ] Restaurar',
        'modulo' => 'produto',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'produto-minimo',
        'descricao' => '[ Produto ] Estoque Mínimo',
        'modulo' => 'produto',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'produto-relatorio',
        'descricao' => '[ Produto ] Gerar Relatório',
        'modulo' => 'produto',
      ]);

      // UNIDADE 
      $value = Permissao::firstOrCreate([
        'nome' => 'unidade-menu',
        'descricao' => '[ Unidade ] Menu',
        'modulo' => 'unidade',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'unidade-create',
        'descricao' => '[ Unidade ] Criar',
        'modulo' => 'unidade',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'unidade-edit',
        'descricao' => '[ Unidade ] Editar',
        'modulo' => 'unidade',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'unidade-delete',
        'descricao' => '[ Unidade ] Excluir',
        'modulo' => 'unidade',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'unidade-del-perm',
        'descricao' => '[ Unidade ] Excluir Permanente',
        'modulo' => 'unidade',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'unidade-show',
        'descricao' => '[ Unidade ] Visualizar',
        'modulo' => 'unidade',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'unidade-restore',
        'descricao' => '[ Unidade ] Restaurar',
        'modulo' => 'unidade',
      ]);

      // ROTA 
      $value = Permissao::firstOrCreate([
        'nome' => 'rota-menu',
        'descricao' => '[ Rota ] Menu',
        'modulo' => 'rota',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'rota-create',
        'descricao' => '[ Rota ] Criar',
        'modulo' => 'rota',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'rota-edit',
        'descricao' => '[ Rota ] Editar',
        'modulo' => 'rota',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'rota-delete',
        'descricao' => '[ Rota ] Excluir',
        'modulo' => 'rota',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'rota-del-perm',
        'descricao' => '[ Rota ] Excluir Permanente',
        'modulo' => 'rota',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'rota-show',
        'descricao' => '[ Rota ] Visualizar',
        'modulo' => 'rota',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'rota-restore',
        'descricao' => '[ Rota ] Restaurar',
        'modulo' => 'rota',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'rota-relatorio',
        'descricao' => '[ Rota ] Gerar Relatório',
        'modulo' => 'rota',
      ]);

      // MENSAGEM 
      // $value = Permissao::firstOrCreate([
      //   'nome' => 'mensagem-menu',
      //   'descricao' => '[ Mensagem ] Menu',
      //   'modulo' => 'mensagem',
      // ]);

      // $value = Permissao::firstOrCreate([
      //   'nome' => 'mensagem-create',
      //   'descricao' => '[ Mensagem ] Criar',
      //   'modulo' => 'mensagem',
      // ]);

      // $value = Permissao::firstOrCreate([
      //   'nome' => 'mensagem-edit',
      //   'descricao' => '[ Mensagem ] Editar',
      //   'modulo' => 'mensagem',
      // ]);

      // $value = Permissao::firstOrCreate([
      //   'nome' => 'mensagem-delete',
      //   'descricao' => '[ Mensagem ] Excluir',
      //   'modulo' => 'mensagem',
      // ]);

      // $value = Permissao::firstOrCreate([
      //   'nome' => 'mensagem-del-perm',
      //   'descricao' => '[ Mensagem ] Excluir Permanente',
      //   'modulo' => 'mensagem',
      // ]);

      // $value = Permissao::firstOrCreate([
      //   'nome' => 'mensagem-show',
      //   'descricao' => '[ Mensagem ] Visualizar',
      //   'modulo' => 'mensagem',
      // ]);

      // $value = Permissao::firstOrCreate([
      //   'nome' => 'mensagem-restore',
      //   'descricao' => '[ Mensagem ] Restaurar',
      //   'modulo' => 'mensagem',
      // ]);

      // CARGO 
      $value = Permissao::firstOrCreate([
        'nome' => 'cargo-menu',
        'descricao' => '[ Cargo ] Menu',
        'modulo' => 'cargo',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cargo-delete',
        'descricao' => '[ Cargo ] Excluir',
        'modulo' => 'cargo',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cargo-show',
        'descricao' => '[ Cargo ] Visualizar',
        'modulo' => 'cargo',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'cargo-restore',
        'descricao' => '[ Cargo ] Restaurar',
        'modulo' => 'cargo',
      ]);

      // USER 
      $value = Permissao::firstOrCreate([
        'nome' => 'user-menu',
        'descricao' => '[ Usuário ] Menu',
        'modulo' => 'user',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'user-create',
        'descricao' => '[ Usuário ] Criar',
        'modulo' => 'user',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'user-edit',
        'descricao' => '[ Usuário ] Editar',
        'modulo' => 'user',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'user-delete',
        'descricao' => '[ Usuário ] Excluir',
        'modulo' => 'user',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'user-del-perm',
        'descricao' => '[ Usuário ] Excluir Permanente',
        'modulo' => 'user',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'user-show',
        'descricao' => '[ Usuário ] Visualizar',
        'modulo' => 'user',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'user-restore',
        'descricao' => '[ Usuário ] Restaurar',
        'modulo' => 'user',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'user-cargo',
        'descricao' => '[ Usuário ] Definir Cargos',
        'modulo' => 'user',
      ]);

      // CAIXA 
      $value = Permissao::firstOrCreate([
        'nome' => 'caixa-menu',
        'descricao' => '[ Caixa ] Menu',
        'modulo' => 'caixa',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'caixa-user-caixa',
        'descricao' => '[ Caixa ] Seu Caixa',
        'modulo' => 'caixa',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'caixa-saida',
        'descricao' => '[ Caixa ] Saída',
        'modulo' => 'caixa',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'caixa-entrada',
        'descricao' => '[ Caixa ] Entrada',
        'modulo' => 'caixa',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'caixa-geral',
        'descricao' => '[ Caixa ] Caixa Geral',
        'modulo' => 'caixa',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'caixa-saida-geral',
        'descricao' => '[ Caixa ] Saída Geral',
        'modulo' => 'caixa',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'caixa-abrir',
        'descricao' => '[ Caixa ] Abrir Caixa',
        'modulo' => 'caixa',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'caixa-relatorio',
        'descricao' => '[ Caixa ] Gerar Relatório',
        'modulo' => 'caixa',
      ]);

      // CARGA
      $value = Permissao::firstOrCreate([
        'nome' => 'carga-menu',
        'descricao' => '[ Carga ] Menu',
        'modulo' => 'carga',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'carga-create',
        'descricao' => '[ Carga ] Criar',
        'modulo' => 'carga',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'carga-detalhar',
        'descricao' => '[ Carga ] Detalhar',
        'modulo' => 'carga',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'carga-estornar',
        'descricao' => '[ Carga ] Estornar',
        'modulo' => 'carga',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'carga-gerar-venda',
        'descricao' => '[ Carga ] Gerar Venda',
        'modulo' => 'carga',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'carga-finalizar',
        'descricao' => '[ Carga ] Finalizar Carga',
        'modulo' => 'carga',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'carga-detalhar-venda',
        'descricao' => '[ Carga ] Detalhar Vendas',
        'modulo' => 'carga',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'carga-venda-devolver',
        'descricao' => '[ Carga ] Devolver Carga',
        'modulo' => 'carga',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'carga-relatorio',
        'descricao' => '[ Carga ] Gerar Relatório',
        'modulo' => 'carga',
      ]);

      // VENDA
      $value = Permissao::firstOrCreate([
        'nome' => 'venda-menu',
        'descricao' => '[ Venda ] Menu',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-todas',
        'descricao' => '[ Venda ] Vendas',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-andamentos',
        'descricao' => '[ Venda ] Vendas em Andamento',
        'modulo' => 'venda',
      ]);      

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-agendadas',
        'descricao' => '[ Venda ] Vendas Agendadas',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-create',
        'descricao' => '[ Venda ] Criar',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-agendar',
        'descricao' => '[ Venda ] Venda Agendar Entrega',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-retirar',
        'descricao' => '[ Venda ] Venda Retirar',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-finalizar',
        'descricao' => '[ Venda ] Finalizar Venda',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-detalhar',
        'descricao' => '[ Venda ] Detalhar',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-estornar',
        'descricao' => '[ Venda ] Estornar',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-devolver',
        'descricao' => '[ Venda ] Devolver',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-trocar',
        'descricao' => '[ Venda ] Trocar',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-entrega-gerenciar',
        'descricao' => '[ Venda ] Gerenciar Entrega',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-entrega-reagendar',
        'descricao' => '[ Venda ] Reagendar Entrega',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-entrega-confirmar',
        'descricao' => '[ Venda ] Confirmar Entrega',
        'modulo' => 'venda',
      ]);

      $value = Permissao::firstOrCreate([
        'nome' => 'venda-relatorio',
        'descricao' => '[ Venda ] Gerar Relatório',
        'modulo' => 'venda',
      ]);

      echo "Permissões criadas com sucesso! \n";
    }
  }
