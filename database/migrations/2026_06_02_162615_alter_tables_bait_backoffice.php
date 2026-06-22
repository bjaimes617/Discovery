<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\bait\BaitEstatusIntelix;
use App\Models\bait\BaitEstatusConcentra;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bait_estatus_concentra', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion');
            $table->boolean('port_uso')->default(0);
            $table->boolean('port_in')->default(0);
            $table->boolean('active')->default(1);
        });

        Schema::create('bait_estatus_intelix', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion');
            $table->string('grupo')->nullable();
            $table->boolean('active')->default(1);
        });

        $estatus_concentra = [
            ['descripcion' => 'ALTA',       'port_uso' => 0, 'port_in' => 0, 'active' => '1'],
            ['descripcion' => 'RECHAZADA',  'port_uso' => 0, 'port_in' => 0, 'active' => '1'],
            ['descripcion' => 'EXITOSA',    'port_uso' => 0, 'port_in' => 0, 'active' => '1'],
            ['descripcion' => 'INGRESADA',  'port_uso' => 0, 'port_in' => 0, 'active' => '1']
        ];

        foreach ($estatus_concentra as $estatus) {
            BaitEstatusConcentra::create($estatus);
        }

        $estatus_intelix = [
            ['descripcion' => 'VENTA NO CARGADA INTELIX', 'grupo' => 'a', 'active' => '1'],
            ['descripcion' => 'IMEI DUPLICADO', 'grupo' => 'a', 'active' => '1'],
            ['descripcion' => 'LISTA NEGRA', 'grupo' => 'a', 'active' => '1'],
            ['descripcion' => 'CARGA 48 HRS', 'grupo' => 'a', 'active' => '1'],
            ['descripcion' => 'INCONGRUENCIA DE DATOS', 'grupo' => 'a', 'active' => '1'],
            ['descripcion' => 'VENTA DUPLICADA', 'grupo' => 'a', 'active' => '1'],
            ['descripcion' => 'CARGADA POR OTRO PROVEEDOR', 'grupo' => 'a', 'active' => '1'],
            ['descripcion' => 'ARROJA ERROR INTELIX', 'grupo' => 'a', 'active' => '1'],
            ['descripcion' => 'ALTA', 'grupo' => 'b', 'active' => '1'],
            ['descripcion' => 'RECHAZADA', 'grupo' => 'b', 'active' => '1'],
            ['descripcion' => 'EXITOSA', 'grupo' => 'b', 'active' => '1'],
            ['descripcion' => 'IRRECUPERABLE', 'grupo' => 'b', 'active' => '1'],
            ['descripcion' => 'INGRESADA', 'grupo' => 'b', 'active' => '1']
        ];

        foreach ($estatus_intelix as $estatus) {
            BaitEstatusIntelix::create($estatus);
        }

        Schema::table('bait_ventas', function (Blueprint $table) {
            $table->boolean('autorizar')->nullable()->after('estatus_id'); //edilia
            $table->string('sns')->nullable()->after('autorizar'); //walmar | sin señal
            $table->string('estatus_backoffice')->nullable()->after('sns'); //alta|fvc|pendiente|rechazo
            $table->string('validador_alta')->nullable()->after('estatus_backoffice'); // respont | concentra
            $table->bigInteger('bait_concentra_id')->index()->unsigned()->nullable()->after('validador_alta');
            $table->foreign('bait_concentra_id')->references('id')->on('bait_estatus_concentra')->onDelete(NULL);
        });

        Schema::table('bait_historico', function (Blueprint $table) {
            $table->string('sns')->nullable()->after('estatus_id'); //walmar | sin señal
            $table->string('estatus_backoffice')->nullable()->after('sns'); //walmar | sin señal
            $table->string('validador_alta')->nullable()->after('estatus_backoffice'); // respont | concentra
            $table->bigInteger('bait_concentra_id')->index()->unsigned()->nullable()->after('validador_alta');
            $table->foreign('bait_concentra_id')->references('id')->on('bait_estatus_concentra')->onDelete(NULL);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('bait_ventas', function (Blueprint $table) {
            $table->dropColumn('autorizar');
            $table->dropColumn('sns');
            $table->dropColumn('estatus_backoffice');
            $table->dropColumn('validador_alta');
            $table->dropForeign(['bait_concentra_id']);
            $table->dropColumn('bait_concentra_id');
        });

        Schema::table('bait_historico', function (Blueprint $table) {
            $table->dropColumn('sns');
            $table->dropColumn('estatus_backoffice');
            $table->dropColumn('validador_alta');
            $table->dropForeign(['bait_concentra_id']);
            $table->dropColumn('bait_concentra_id');
        });

        Schema::dropIfExists('bait_estatus_concentra');
        Schema::dropIfExists('bait_estatus_intelix');
    }
};
