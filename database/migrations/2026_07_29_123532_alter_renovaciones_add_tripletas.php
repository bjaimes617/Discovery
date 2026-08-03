<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('renovaciones_ventas', function (Blueprint $table) {
            $table->string('tripleta')->nullable()->after('monto_plan_actual');
            $table->date('pagada_el')->nullable()->after('tripleta');
        });

        Schema::table('renovaciones_historico', function (Blueprint $table) {
            $table->string('tripleta')->nullable()->after('observaciones');
            $table->date('pagada_el')->nullable()->after('tripleta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('renovaciones_ventas', function (Blueprint $table) {
            $table->dropColumn('tripleta');
            $table->dropColumn('pagada_el');
        });

        Schema::table('renovaciones_historico', function (Blueprint $table) {
            $table->dropColumn('tripleta');
            $table->dropColumn('pagada_el');
        });
    }
};
