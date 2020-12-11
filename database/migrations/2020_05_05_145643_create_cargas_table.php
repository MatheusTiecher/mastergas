<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // CARGA
        Schema::create('cargas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('observacao')->nullable();
            $table->integer('status');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        // CARGA ITENS
        Schema::create('carga_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sequencia');
            $table->decimal('quantidade', 8, 2);
            $table->integer('status');
            $table->string('descricao')->nullable();
            $table->unsignedInteger('carga_id');
            $table->foreign('carga_id')->references('id')->on('cargas')->onDelete('cascade');
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
        Schema::dropIfExists('carga_items');
        Schema::dropIfExists('cargas');
    }
}
