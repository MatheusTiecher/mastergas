<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaixasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // CAIXA
        Schema::create('caixas', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('inicial', 8, 2);
            $table->decimal('entrada', 8, 2);
            $table->decimal('saida', 8, 2);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        // LANÇAMENTO - ENTRADA - SAIDA 
        Schema::create('lancamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tipo_lancamento');
            $table->string('descricao')->nullable(); 
            $table->decimal('valor', 8, 2);
            $table->unsignedInteger('caixa_id')->nullable();
            $table->foreign('caixa_id')->references('id')->on('caixas');
            $table->unsignedInteger('venda_id')->nullable();
            $table->foreign('venda_id')->references('id')->on('vendas');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('lancamentos');
        Schema::dropIfExists('caixas');
    }
}
