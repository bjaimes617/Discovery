<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\masivos\AuditoriaModel;
use App\Models\masivos\MavisoHistoricosModel;
use App\Models\masivos\VentasModel;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::table('claro_masivo_ventas', function (Blueprint $table) {
            $table->boolean('recuperable')->default(false)->after('estatus_id');
            $table->json('enviado')->nullable()->after('recuperable');
        });

        Schema::create('claro_masivo_recaudos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('producto_id')->index()->unsigned()->nullable();
            $table->foreign('producto_id')->references('id')->on('claro_masivo_productos')->onDelete(NULL);
            $table->string('documento');
            $table->boolean('active');
        });

        Schema::create('claro_masivo_historico', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('claro_masivo_ventas_id')->index()->unsigned()->nullable();
            $table->foreign('claro_masivo_ventas_id')->references('id')->on('claro_masivo_ventas')->onDelete('cascade');
            $table->string('usuario');
            $table->bigInteger('estatus_id')->index()->unsigned()->nullable();
            $table->foreign('estatus_id')->references('id')->on('claro_masivo_estatus')->onDelete(NULL);
            $table->json('document_checks_partials');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        $auditoria = AuditoriaModel::all();
        foreach ($auditoria as $key => $value) {

            $venta = VentasModel::find($value->claro_masivo_ventas_id);
            $venta->enviado = $value->enviado;
            $venta->save();

            $historico = new MavisoHistoricosModel();
            $historico->claro_masivo_ventas_id = $value->claro_masivo_ventas_id;
            $historico->usuario         = $value->relationAuditUser->nombre_apellido;
            $historico->observaciones   = $value->observaciones;
            $historico->document_checks_partials = json_encode(["" => ""]);
            $historico->estatus_id = $venta->estatus_id;
            $historico->save();
        }

        Schema::dropIfExists('claro_masivo_auditoria');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        schema::dropIfExists('claro_masivo_recaudos');
        schema::dropIfExists('claro_masivo_historico');

        Schema::table('claro_masivo_ventas', function (Blueprint $table) {
            $table->dropColumn('recuperable');
        });

        schema::table('claro_masivo_auditoria', function (Blueprint $table) {
            $table->dropColumn('document_checks_finish');
        });
    }
};
