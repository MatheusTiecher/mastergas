<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // FORMA PAGAMENTO
        Schema::create('forma_pagamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao');
            $table->timestamps();
        });

        // VENDA
        Schema::create('vendas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('observacao')->nullable();
            $table->decimal('desconto', 8, 2)->nullable();
            $table->decimal('frete', 8, 2)->nullable();
            $table->integer('status');
            $table->dateTime('finalizavenda')->nullable();
            $table->unsignedInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('forma_pagamento_id')->nullable();
            $table->foreign('forma_pagamento_id')->references('id')->on('forma_pagamentos');
            $table->unsignedInteger('rota_id')->nullable();
            $table->foreign('rota_id')->references('id')->on('rotas');
            $table->unsignedInteger('carga_id')->nullable();
            $table->foreign('carga_id')->references('id')->on('cargas');
            $table->timestamps();
        });

        // VENDA ITENS
        Schema::create('venda_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sequencia');
            $table->decimal('quantidade', 8, 2);
            $table->integer('status');
            $table->decimal('valorvenda', 8, 2);
            $table->string('descricao')->nullable();
            $table->unsignedInteger('venda_id');
            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
            $table->unsignedInteger('produto_id');
            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->unsignedInteger('estoque_id')->nullable();
            $table->foreign('estoque_id')->references('id')->on('estoques');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venda_items');
        Schema::dropIfExists('vendas');
        Schema::dropIfExists('forma_pagamentos');
    }
}
