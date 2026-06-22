<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoSucursalToClaroMovilVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas_claro_movil', function (Blueprint $table) {
            $table->string('tipo_sucursal')->after('sucursal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas_claro_movil', function (Blueprint $table) {
            $table->dropColumn('tipo_sucursal');
        });
    }
}
