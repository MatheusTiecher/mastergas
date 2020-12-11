<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMensagemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('mensagems', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('nome');
        //     $table->string('msg', 500);
        //     $table->string('rotina');
        //     $table->string('hora');
        //     $table->integer('ativo');
        //     $table->unsignedInteger('produto_id');
        //     $table->foreign('produto_id')->references('id')->on('produtos');
        //     $table->softDeletes(); // coluna do soft delete
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('mensagems');
    }
}
