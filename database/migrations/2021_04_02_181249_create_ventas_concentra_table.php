<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasConcentraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas_concentra', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dn')->comment('Numero de Indentificacion');
            $table->string('nombre_cliente');
            $table->string('folio_venta')->nullable()->comment('Folio de la Venta');
            $table->string('curp')->comment('CURP de la Venta');
            $table->string('nip')->comment('NIP de la Venta');
            $table->text('centro_atencion')->nullable();            
            $table->string('dn_alterno')->nullable()->comment('Numero de Indentificacion');
            $table->string('dn_audio')->nullable()->comment('Numero de Indentificacion');
            $table->integer('fvc')->nullable()->unsigned()->comment('1-.24Hrs   2-.48Hrs');
            $table->dateTime('fecha_venta')->comment('Fecha de la Venta');
            $table->unsignedBigInteger('validador_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('supervisor_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('personal_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('modificado_por')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('tipificacion_id')->nullable()->unsigned()->index();            
            $table->foreign('tipificacion_id')->references('id')->on('tipificacion2')->onDelete('cascade');
            $table->unsignedBigInteger('origen_id')->nullable()->unsigned()->index();
            $table->foreign('origen_id')->references('id')->on('origen_data')->onDelete('cascade');
            $table->text('comentarios')->nullable()->comment('Comentarios de la Venta');     
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
        Schema::dropIfExists('ventas_concentra');
    }
}
