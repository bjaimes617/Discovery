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
        Schema::create('concentra_respondio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('workspace', 100)->index();
            $table->string('idcontacto', 30)->index();
            $table->string('numero_portar', 25)->index()->nullable();
            $table->string('ciclo_de_vida');
            $table->string('anuncio')->nullable();
            $table->string('numero_contacto')->nullable();
            $table->string('usuario')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concentra_respondio');
    }
};
