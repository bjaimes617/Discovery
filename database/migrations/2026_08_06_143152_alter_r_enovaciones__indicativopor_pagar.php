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
            $table->string('pagable', 2)->nullable()->after('tripleta');
        });

        Schema::table('renovaciones_historico', function (Blueprint $table) {
            $table->string('pagable', 2)->nullable()->after('tripleta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('renovaciones_ventas', function (Blueprint $table) {
            $table->dropColumn('pagable');
        });

        Schema::table('renovaciones_historico', function (Blueprint $table) {
            $table->dropColumn('pagable');
        });
    }
};
