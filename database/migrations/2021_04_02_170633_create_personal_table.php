<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('in_telefonico',50)->nullable()->comment('Numero de registro del empleado');
            $table->string('numero_empleado',50)->comment('Numero de Cedula de Identidad');
            $table->string('login_telefonico',60)->nullable()->comment('Login del usuario');
            $table->date('fecha_ingreso')->comment('Fecha de Ingreso');
            $table->date('fecha_baja')->nullable()->comment('Fecha de Baja');
            $table->unsignedBigInteger('cargo_id')->nullable()->unsigned()->index();
            $table->foreign('cargo_id')->references('id')->on('cargos')->onDelete('cascade');           
            $table->unsignedBigInteger('user_id')->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('jefe_inmediato_id')->nullable()->unsigned()->index();
            $table->foreign('jefe_inmediato_id')->references('id')->on('personal')->onDelete('cascade');            
            $table->unsignedBigInteger('jefe_inmediato_segundo_id')->nullable()->unsigned()->index();
            $table->foreign('jefe_inmediato_segundo_id')->references('id')->on('personal')->onDelete('cascade');
            $table->integer('estatus')->comment('Estatus del Empleado 1 activo;2 baja'); 
            $table->unsignedBigInteger('campana_id')->unsigned()->nullable();
            $table->foreign('campana_id')->references('id')->on('campanias')->onDelete("cascade");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal');
    }
}
