<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdjuntoDeudaToClaroMovilVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas_claro_movil', function (Blueprint $table) {
            $table->string('adjunto_deudas')->after('adjunto_otros')->nullable();
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
            $table->dropColumn('adjunto_deudas');
        });
    }
}
