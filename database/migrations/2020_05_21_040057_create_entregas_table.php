<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status');
            $table->integer('forma_entrega');
            $table->dateTime('dataentrega')->nullable();
            $table->unsignedInteger('venda_id')->nullable();
            $table->foreign('venda_id')->references('id')->on('vendas');
            $table->timestamps();
        });

        Schema::create('ocorrencia_entregas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status');
            $table->string('anotacao')->nullable();
            $table->string('ocorrencia')->nullable();
            $table->dateTime('dataagendada')->nullable(); // nao pode alterar, caso queira altear tem que gerar um novo
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('entrega_id')->nullable();
            $table->foreign('entrega_id')->references('id')->on('entregas')->onDelete('cascade');
            $table->unsignedInteger('endereco_id')->nullable();
            $table->foreign('endereco_id')->references('id')->on('enderecos'); 
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
        Schema::dropIfExists('ocorrencia_entregas');
        Schema::dropIfExists('entregas');
    }
}
