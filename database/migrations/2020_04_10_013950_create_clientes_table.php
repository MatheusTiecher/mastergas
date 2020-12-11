<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nomerazao');
            $table->string('fantasia')->nullable();
            $table->string('cpfcnpj',20)->unique();
            $table->string('rgie')->nullable();
            $table->string('email',60)->unique();
            $table->string('celular',20);
            $table->string('telefone',20)->nullable();
            // $table->string('mensagem', 1);
            $table->char('tipo');
            $table->softDeletes(); // coluna do soft delete
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
        Schema::dropIfExists('clientes');
    }
}
