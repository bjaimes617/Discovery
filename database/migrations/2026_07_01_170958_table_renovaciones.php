<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\renovaciones\renovacionesEstatusModel;
use App\Models\Campania;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $cambio = Campania::find(6);
        $cambio->nombre_campana = "Renovaciones";
        $cambio->estatus_id = 1;
        $cambio->save();

        Schema::create('renovaciones_estatus', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
        });

        $estatus_concentra = [
            ['descripcion' => 'REGISTRADA', 'active' => '1'],
            ['descripcion' => 'APROBADA', 'active' => '1'],
            ['descripcion' => 'NO APROBADA', 'active' => '1'],
            ['descripcion' => 'DEVUELTA', 'active' => '1'],
            ['descripcion' => 'FACTURADO', 'active' => '1'],
            ['descripcion' => 'DEVUELTO', 'active' => '1'],
            ['descripcion' => 'LIQUIDADO / PAGO TOTAL', 'active' => '1'],
            ['descripcion' => 'NO ENTREGADO', 'active' => '1'],
            ['descripcion' => 'ENTREGADO/PENDIENTE POR FACTURAR', 'active' => '1'],
            ['descripcion' => 'ENVÍO EN PROCESO', 'active' => '1']
        ];

        foreach ($estatus_concentra as $estatus) {
            renovacionesEstatusModel::create($estatus);
        }
        Schema::create('renovaciones_ventas', function (Blueprint $table) {
            $table->id();
            $table->string('dn')->unique();
            $table->string('nombre_cliente')->nullable();
            $table->string('equipo')->nullable();
            $table->string('plazo')->nullable();      
            $table->string('numero_orden_onix')->index()->nullable();
            $table->decimal('precio_equipo', 10, 2)->nullable();
            $table->string('entrega_en')->nullable();
            $table->string('direccion_entrega')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->string('entre_calles')->nullable();
            $table->string('referencias')->nullable();

            $table->text('observaciones')->nullable();

            $table->bigInteger('supervisor_id')->index()->unsigned()->nullable();
            $table->foreign('supervisor_id')->references('id')->on('personal')->onDelete(NULL);
            $table->bigInteger('coordinador_id')->index()->unsigned()->nullable();
            $table->foreign('coordinador_id')->references('id')->on('personal')->onDelete(NULL);
            $table->bigInteger('personal_id')->index()->unsigned()->nullable();
            $table->foreign('personal_id')->references('id')->on('personal')->onDelete(NULL);

            $table->bigInteger('user_id')->index()->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete(NULL);

            $table->bigInteger('estatus_id')->index()->unsigned()->nullable();
            $table->foreign('estatus_id')->references('id')->on('renovaciones_estatus')->onDelete(NULL);
            $table->timestamps();
        });

        Schema::create('renovaciones_historico', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('renovaciones_ventas_id')->index()->unsigned()->nullable();
            $table->foreign('renovaciones_ventas_id')->references('id')->on('renovaciones_ventas')->onDelete('cascade');
            $table->string('usuario');
            $table->bigInteger('estatus_id')->index()->unsigned()->nullable();
            $table->foreign('estatus_id')->references('id')->on('renovaciones_estatus')->onDelete(NULL);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renovaciones_historico');
        Schema::dropIfExists('renovaciones_ventas');
        Schema::dropIfExists('renovaciones_estatus');
    }
};
