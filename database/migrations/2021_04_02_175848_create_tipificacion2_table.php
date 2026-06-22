<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipificacion2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipificacion2', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre_tipificacion2');
            $table->unsignedBigInteger('tipificacion1_id');
            $table->foreign('tipificacion1_id')->references('id')->on('tipificacion1')->onDelete("cascade");
            $table->unsignedBigInteger('estatus_id');
            $table->foreign('estatus_id')->references('id')->on('estatus')->onDelete("cascade");
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
        Schema::dropIfExists('tipificacion2');
    }
}
