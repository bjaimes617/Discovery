<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\pymes\AuditoriaModel;
use App\Models\pymes\VentasModel;
use App\Models\pymes\PymesHistoricosModel;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('claro_pymes_ventas', function (Blueprint $table) {
            $table->boolean('recuperable')->default(0)->after('estatus_id');
            $table->json('enviado')->nullable()->after('recuperable');
        });

        Schema::create('claro_pymes_recaudos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('producto_id')->index()->unsigned()->nullable();
            $table->foreign('producto_id')->references('id')->on('claro_pymes_productos')->onDelete(NULL);
            $table->string('documento');
            $table->boolean('active');
        });

        Schema::create('claro_pymes_historico', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('claro_pymes_ventas_id')->index()->unsigned()->nullable();
            $table->foreign('claro_pymes_ventas_id')->references('id')->on('claro_pymes_ventas')->onDelete('cascade');
            $table->string('usuario');
            $table->bigInteger('estatus_id')->index()->unsigned()->nullable();
            $table->foreign('estatus_id')->references('id')->on('claro_pymes_estatus')->onDelete(NULL);
            $table->json('document_checks_partials');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
        try {
            $auditoria = AuditoriaModel::all();
            foreach ($auditoria as $key => $value) {

                $venta = VentasModel::find($value->claro_pymes_ventas_id);
                $venta->enviado = $value->enviado;
                $venta->save();

                $historico = new PymesHistoricosModel();
                $historico->claro_pymes_ventas_id = $value->claro_pymes_ventas_id;
                $historico->usuario         = $value->relationAuditUser->nombre_apellido;
                $historico->observaciones   = $value->observaciones;
                $historico->document_checks_partials = json_encode(["" => ""]);
                $historico->estatus_id = $venta->estatus_id;
                $historico->save();
            }
        } catch (Exception $e) {
            dd($e, $value);
        }

        Schema::dropIfExists('claro_pymes_auditoria');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::dropIfExists('claro_pymes_recaudos');
        schema::dropIfExists('claro_pymes_historico');

        Schema::table('claro_pymes_ventas', function (Blueprint $table) {
            $table->dropColumn('recuperable');
        });

        schema::table('claro_pymes_auditoria', function (Blueprint $table) {
            $table->dropColumn('document_checks_finish');
        });
    }
};
