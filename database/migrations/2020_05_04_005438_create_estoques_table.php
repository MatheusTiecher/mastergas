<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstoquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estoques', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('total', 8, 2);
            $table->decimal('restante', 8, 2);
            $table->decimal('valorcusto', 8, 2);
            $table->unsignedInteger('fornecedor_id');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedors');
            $table->unsignedInteger('produto_id');
            $table->foreign('produto_id')->references('id')->on('produtos');
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
        Schema::dropIfExists('estoques');
    }
}
