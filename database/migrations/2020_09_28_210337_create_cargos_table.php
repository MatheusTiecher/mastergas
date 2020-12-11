<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // CARGO
        Schema::create('cargos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome')->nullable();
            $table->string('descricao')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // PERMISSOES
        Schema::create('permissoes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome')->nullable();
            $table->string('descricao')->nullable();
            $table->string('modulo')->nullable();
            $table->timestamps();
        });

        // PIVOT CARGO PERMISSAO
        Schema::create('cargo_permissao', function (Blueprint $table) {
            $table->unsignedInteger('cargo_id');
            $table->foreign('cargo_id')->references('id')->on('cargos')->onDelete('cascade');
            $table->unsignedInteger('permissao_id');
            $table->foreign('permissao_id')->references('id')->on('permissoes')->onDelete('cascade');

            $table->primary(['cargo_id', 'permissao_id']);
        });

        // PIVOT CARGO USUARIO
        Schema::create('cargo_user', function (Blueprint $table) {
            $table->unsignedInteger('cargo_id');
            $table->foreign('cargo_id')->references('id')->on('cargos')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->primary(['cargo_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cargo_user');
        Schema::dropIfExists('cargo_permissao');
        Schema::dropIfExists('permissoes');
        Schema::dropIfExists('cargos');
    }
}
