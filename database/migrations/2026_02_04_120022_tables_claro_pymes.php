<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TablesClaroPymes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('claro_masivo_ventas', function (Blueprint $table) {
            $table->integer('cajas')->nullable()->default(0);
        });

        Schema::create('claro_pymes_estatus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion');
            $table->boolean('active')->default(1);
        });

        Schema::create('claro_pymes_productos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });

        Schema::create('claro_pymes_planes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion');
            $table->boolean('active')->default(1);
            $table->string('group')->nullable();
            $table->bigInteger('producto_id')->index()->unsigned()->nullable();
            $table->foreign('producto_id')->references('id')->on('claro_pymes_productos')->onDelete(NULL);
            $table->timestamps();
        });

        Schema::create('claro_pymes_ventas', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('producto_id')->index()->unsigned()->nullable();
            $table->foreign('producto_id')->references('id')->on('claro_pymes_productos')->onDelete(NULL);

            $table->string('id_contacto');

            $table->string('nombre');
            $table->string('identificacion');
            $table->string('ordenpatronal')->nullable();
            $table->string('representantelegal')->nullable();
            $table->string('email');
            $table->string('telefono_a_llamar');

            $table->string('provincia');
            $table->string('canton');
            $table->string('distrito');
            $table->string('barrio');
            $table->string('detalle_direccion');

            $table->string('tipo_venta')->nullable();

            $table->bigInteger('plan_id')->index()->unsigned()->nullable();
            $table->foreign('plan_id')->references('id')->on('claro_pymes_planes')->onDelete(NULL);

            $table->string('precio_plan')->nullable();
            $table->string('cantidad')->nullable();
            $table->string('equipo')->nullable();
            $table->string('cordenadas')->nullable();
            $table->string('portabilidad')->nullable();

            $table->string('observaciones')->nullable();

            $table->bigInteger('supervisor_id')->index()->unsigned()->nullable();
            $table->foreign('supervisor_id')->references('id')->on('personal')->onDelete(NULL);
            $table->bigInteger('coordinador_id')->index()->unsigned()->nullable();
            $table->foreign('coordinador_id')->references('id')->on('personal')->onDelete(NULL);
            $table->bigInteger('personal_id')->index()->unsigned()->nullable();
            $table->foreign('personal_id')->references('id')->on('personal')->onDelete(NULL);

            $table->bigInteger('user_id')->index()->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete(NULL);

            $table->bigInteger('estatus_id')->index()->unsigned()->nullable();
            $table->foreign('estatus_id')->references('id')->on('claro_pymes_estatus')->onDelete(NULL);

            $table->timestamps();
        });

        Schema::create('claro_pymes_auditoria', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('claro_pymes_ventas_id')->index()->unsigned()->nullable();
            $table->foreign('claro_pymes_ventas_id')->references('id')->on('claro_pymes_ventas')->onDelete('cascade');
            $table->bigInteger('user_id')->index()->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete(NULL);
            $table->text('observaciones')->nullable();
            $table->text('enviado')->nullable();
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
        Schema::dropIfExists('claro_pymes_auditoria');
        Schema::dropIfExists('claro_pymes_ventas');
        Schema::dropIfExists('claro_pymes_planes');
        Schema::dropIfExists('claro_pymes_estatus');
        Schema::dropIfExists('claro_pymes_productos');
    }
}
