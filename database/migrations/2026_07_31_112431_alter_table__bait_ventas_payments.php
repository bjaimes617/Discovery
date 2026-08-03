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
        Schema::table('bait_ventas', function (Blueprint $table) {
            $table->date('pagada_el')->nullable()->after('autorizar');
        });

        Schema::table('bait_historico', function (Blueprint $table) {
            $table->date('pagada_el')->nullable()->after('bait_concentra_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bait_ventas', function (Blueprint $table) {
            $table->dropColumn('pagada_el');
        });

        Schema::table('bait_historico', function (Blueprint $table) {
            $table->dropColumn('pagada_el');
        });
    }
};
