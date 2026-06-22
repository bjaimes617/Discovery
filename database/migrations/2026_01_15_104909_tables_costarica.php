<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TablesCostarica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claro_masivo_estatus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion');
            $table->boolean('active')->default(1);
        });

        Schema::create('claro_masivo_productos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });

        Schema::create('claro_masivo_planes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion');
            $table->boolean('active')->default(1);
            $table->bigInteger('producto_id')->index()->unsigned()->nullable();
            $table->foreign('producto_id')->references('id')->on('claro_masivo_productos')->onDelete(NULL);
            $table->timestamps();
        });

        Schema::create('claro_masivo_equipos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });

        Schema::create('claro_masivo_parentescos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });

        Schema::create('claro_masivo_ventas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('producto_id')->index()->unsigned()->nullable();
            $table->foreign('producto_id')->references('id')->on('claro_masivo_productos')->onDelete(NULL);
            $table->string('id_contacto');
            $table->string('tipo_venta')->nullable();
            $table->string('nombre');
            $table->string('apellido_1');
            $table->string('apellido_2');
            $table->string('identificacion');
            $table->string('segmento');
            $table->bigInteger('plan_id')->index()->unsigned()->nullable();
            $table->foreign('plan_id')->references('id')->on('claro_masivo_planes')->onDelete(NULL);
            $table->string('provincia');
            $table->string('canton');
            $table->string('distrito');
            $table->string('detalle_direccion');
            $table->string('telefono_a_llamar');
            $table->string('email');
            $table->bigInteger('equipo_id')->index()->unsigned()->nullable();
            $table->foreign('equipo_id')->references('id')->on('claro_masivo_equipos')->onDelete(NULL);
            $table->string('numero_portar');
            $table->string('anticipo');
            $table->string('nombre_refencia_1')->nullable();
            $table->string('telefono_refencia_1')->nullable();
            $table->string('parentesco_refencia_1')->nullable();

            $table->string('nombre_refencia_2')->nullable();
            $table->string('telefono_refencia_2')->nullable();
            $table->string('parentesco_refencia_2')->nullable();

            $table->string('nombre_refencia_3')->nullable();
            $table->string('telefono_refencia_3')->nullable();
            $table->string('parentesco_refencia_3')->nullable();
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
            $table->foreign('estatus_id')->references('id')->on('claro_masivo_estatus')->onDelete(NULL);

            $table->timestamps();
        });

        Schema::create('claro_masivo_auditoria', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('claro_masivo_ventas_id')->index()->unsigned()->nullable();
            $table->foreign('claro_masivo_ventas_id')->references('id')->on('claro_masivo_ventas')->onDelete('cascade');
            $table->bigInteger('user_id')->index()->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete(NULL);
            $table->boolean('enviado')->default(false);
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
        Schema::dropIfExists('claro_masivo_auditoria');
        Schema::dropIfExists('claro_masivo_ventas');
        Schema::dropIfExists('claro_masivo_planes');
        Schema::dropIfExists('claro_masivo_parentescos');
        Schema::dropIfExists('claro_masivo_equipos');
        Schema::dropIfExists('claro_masivo_estatus');
        Schema::dropIfExists('claro_masivo_productos');
    }
}
