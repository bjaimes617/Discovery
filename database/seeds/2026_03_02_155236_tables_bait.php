<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
    
    public function up(): void
    {
        Schema::create('bait_estatus', function (Blueprint $table) {
            $table->id();
        });

        Schema::create('bait_tiendas', function (Blueprint $table) {
            $table->id();
        });

        Schema::create('bait_ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_portar', 10);
            $table->string('nombre_apellido');
            $table->date('fecha_nacimiento');
            $table->string('imei')->nullable();
            $table->string('codigo_nip')->nullable();
            $table->date('fecha_vigencia')->nullable();
            $table->string('email');
            $table->string('telefono_contacto');
            $table->integer('fvc', 2);

            $table->string('observaciones')->nullable();

            $table->bigInteger('supervisor_id')->index()->unsigned()->nullable();
            $table->foreign('supervisor_id')->references('id')->on('personal')->onDelete(NULL);
            $table->bigInteger('coordinador_id')->index()->unsigned()->nullable();
            $table->foreign('coordinador_id')->references('id')->on('personal')->onDelete(NULL);
            $table->bigInteger('personal_id')->index()->unsigned()->nullable();
            $table->foreign('personal_id')->references('id')->on('personal')->onDelete(NULL);

            $table->bigInteger('user_id')->index()->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete(NULL);

            $table->string('ultimo_estatus_intelix')->nullable();
            $table->timestamps();
        });

        Schema::create('bait_auditoria', function (Blueprint $table) {
            $table->id();
            $table->string('estatus_intelix')->nullable();
            $table->string('folio_intelix')->nullable();
            $table->string('observaciones')->nullable();
            $table->bigInteger('user_id')->index()->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete(NULL);
            $table->timestamps();
        });

        Schema::create('bait_ciclos_contactos', function (Blueprint $table) {
            $table->id();
        });
    }

   
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
