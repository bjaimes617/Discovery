<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipificacion1Table extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tipificacion1', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre_tipificacion1');
            $table->unsignedBigInteger('campana_id');
            $table->foreign('campana_id')->references('id')->on('campanias')->onDelete("cascade");
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
    public function down() {
        Schema::dropIfExists('tipificacion1');
    }

}
