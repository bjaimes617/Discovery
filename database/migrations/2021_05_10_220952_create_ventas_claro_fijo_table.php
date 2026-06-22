<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasClaroFijoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas_claro_fijo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo_llamada');
            $table->unsignedBigInteger('numero_contrato');
            $table->string('dn')->comment('Numero de Identificacion');
            $table->string('rut');
            $table->string('nombre_cliente'); 
            $table->unsignedBigInteger('servicio_contratado_id')->nullable()->unsigned()->index();            
            $table->foreign('servicio_contratado_id')->references('id')->on('cl_servicio_contratado')->onDelete('cascade');
            $table->unsignedBigInteger('plan_id')->nullable()->unsigned()->index();            
            $table->foreign('plan_id')->references('id')->on('cl_plan')->onDelete('cascade');
            $table->decimal('pago',$precision = 15, $scale = 2); 
            $table->text('direccion'); 
            $table->string('numero_alterno1');
            $table->string('numero_alterno2');
            $table->string('correo_electronico'); 
            $table->date('fecha_venta');
            $table->text('comentarios')->nullable()->comment('Comentarios de la Venta'); 
            $table->unsignedBigInteger('validador_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('supervisor_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('personal_id')->nullable()->unsigned()->index();            
            $table->unsignedBigInteger('tipificacion_id')->nullable()->unsigned()->index();            
            $table->foreign('tipificacion_id')->references('id')->on('tipificacion2')->onDelete('cascade');
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
        Schema::dropIfExists('ventas_claro_fijo');
    }
}
