<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasImperiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas_imperia', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->string('telefono_fijo', 15);
            $table->string('telefono_movil', 15);
            $table->string('nombre_cliente');
            $table->string('curp');
            $table->string('dni')->comment('Numero de Identificacion');
            $table->string('iban');       
            $table->string('mantenimiento');            
            $table->text('direccion')->comment('direccion del cliente');
            $table->string('poblacion');
            $table->string('dn_audio');
            $table->string('email');
            $table->dateTime('fecha_venta');
            $table->text('comentarios')->nullable()->comment('Comentarios de la Venta'); 
            $table->unsignedBigInteger('validador_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('supervisor_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('personal_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('tipificacion_id')->nullable()->unsigned()->index();            
            $table->foreign('tipificacion_id')->references('id')->on('tipificacion2')->onDelete('cascade');            
            $table->unsignedBigInteger('variante_id')->nullable()->unsigned()->index();            
            $table->foreign('variante_id')->references('id')->on('imp_variante')->onDelete('cascade');        
            $table->unsignedBigInteger('imp_tarifa_id')->nullable()->unsigned()->index();            
            $table->foreign('imp_tarifa_id')->references('id')->on('imp_tarifas')->onDelete('cascade');
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
        Schema::dropIfExists('ventas_imperia');
    }
}
