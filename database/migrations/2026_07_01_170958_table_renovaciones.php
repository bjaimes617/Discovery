<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\renovaciones\renovacionesEstatusModel;
use App\Models\renovaciones\renovacionesObservacionesModel;
use App\Models\Campania;
use jeremykenedy\LaravelRoles\Models\Permission;
use jeremykenedy\LaravelRoles\Models\Role;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $cambio = Campania::find(6);
        $cambio->nombre_campana = "Renovaciones";
        $cambio->estatus_id = 1;
        $cambio->save();

        Schema::create('renovaciones_estatus', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
        });

        Schema::create('renovaciones_observaciones', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion')->nullable();
            $table->bigInteger('estatus_id')->index()->unsigned()->nullable();
            $table->foreign('estatus_id')->references('id')->on('renovaciones_estatus')->onDelete(NULL);            
            $table->boolean('active')->default(1);
            $table->timestamps();
        });

        $estatus_concentra = [
            ['descripcion' => 'REGISTRADA', 'active' => '1'],
            ['descripcion' => 'APROBADA', 'active' => '1'],
            ['descripcion' => 'NO APROBADA', 'active' => '1'],
            ['descripcion' => 'DEVUELTA', 'active' => '1'],
            ['descripcion' => 'FACTURADO', 'active' => '1'],
            ['descripcion' => 'DEVUELTO', 'active' => '1'],
            ['descripcion' => 'LIQUIDADO / PAGO TOTAL', 'active' => '1'],
            ['descripcion' => 'NO ENTREGADO', 'active' => '1'],
            ['descripcion' => 'ENTREGADO/PENDIENTE POR FACTURAR', 'active' => '1'],
            ['descripcion' => 'ENVÍO EN PROCESO', 'active' => '1']
        ];

        foreach ($estatus_concentra as $estatus) {
            renovacionesEstatusModel::create($estatus);
        }

         $observaciones = [
            ['descripcion' => 'Cliente recibió el equipo y realizó pago de la primera cuota del equipo', 'estatus_id' => '5', 'active' => '1'],
            ['descripcion' => 'Cliente realizó devolución del equipo', 'estatus_id' => '6', 'active' => '1'],
            ['descripcion' => 'Se realizó pago total del equipo', 'estatus_id' => '7', 'active' => '1'],
            ['descripcion' => 'Orden Cancelada', 'estatus_id' => '8', 'active' => '1'],
            ['descripcion' => 'Equipo entregado a espera de primer pago del cliente', 'estatus_id' => '9', 'active' => '1'],
            ['descripcion' => 'Envío del equipo en proceso', 'estatus_id' => '10', 'active' => '1'],
            ['descripcion' => 'Desiste por Demora', 'estatus_id' => '5', 'active' => '1'],
            ['descripcion' => 'Desiste por Demora', 'estatus_id' => '8', 'active' => '1'],
            ['descripcion' => 'Desiste por Demora', 'estatus_id' => '10', 'active' => '1'],
            ['descripcion' => 'Aun Espera la Entrega', 'estatus_id' => '5', 'active' => '1'],
            ['descripcion' => 'Aun Espera la Entrega', 'estatus_id' => '8', 'active' => '1'],
            ['descripcion' => 'Aun Espera la Entrega', 'estatus_id' => '10', 'active' => '1'],
            ['descripcion' => 'No contecta', 'estatus_id' => '5', 'active' => '1'],
            ['descripcion' => 'No contecta', 'estatus_id' => '8', 'active' => '1'],
            ['descripcion' => 'No contecta', 'estatus_id' => '10', 'active' => '1']

        ];

        foreach ($observaciones as $observacion) {
            renovacionesObservacionesModel::create($observacion);
        }

        Schema::create('renovaciones_ventas', function (Blueprint $table) {
            $table->id();
            $table->string('dn');
            $table->string('nombre_cliente')->nullable();
            $table->string('equipo')->nullable();
            $table->string('plazo')->nullable();      
            $table->string('numero_orden_onix')->index()->unique();
            $table->decimal('precio_equipo', 10, 2)->nullable();
            $table->string('entrega_en')->nullable();
            $table->string('direccion_entrega')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->string('entre_calles')->nullable();
            $table->string('referencias')->nullable();

            $table->text('observaciones')->nullable();

            $table->string('estatus_concentra')->nullable();
            $table->string('llamada_bo')->nullable();
            $table->string('plan_anterior')->nullable();
            $table->string('plan_actual')->nullable();
            $table->decimal('monto_plan_anterior', 10, 2)->nullable();
            $table->decimal('monto_plan_actual', 10, 2)->nullable();

            $table->bigInteger('supervisor_id')->index()->unsigned()->nullable();
            $table->foreign('supervisor_id')->references('id')->on('personal')->onDelete(NULL);
            $table->bigInteger('coordinador_id')->index()->unsigned()->nullable();
            $table->foreign('coordinador_id')->references('id')->on('personal')->onDelete(NULL);
            $table->bigInteger('personal_id')->index()->unsigned()->nullable();
            $table->foreign('personal_id')->references('id')->on('personal')->onDelete(NULL);

            $table->bigInteger('user_id')->index()->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete(NULL);

            $table->bigInteger('estatus_id')->index()->unsigned()->nullable();
            $table->foreign('estatus_id')->references('id')->on('renovaciones_estatus')->onDelete(NULL);
            
            $table->bigInteger('observaciones_id')->index()->unsigned()->nullable();
            $table->foreign('observaciones_id')->references('id')->on('renovaciones_observaciones')->onDelete(NULL);          
            $table->timestamps();
        });

        Schema::create('renovaciones_historico', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('renovaciones_ventas_id')->index()->unsigned()->nullable();
            $table->foreign('renovaciones_ventas_id')->references('id')->on('renovaciones_ventas')->onDelete('cascade');
            $table->string('usuario');
            $table->bigInteger('estatus_id')->index()->unsigned()->nullable();
            $table->foreign('estatus_id')->references('id')->on('renovaciones_estatus')->onDelete(NULL);
            
            $table->bigInteger('observaciones_id')->index()->unsigned()->nullable();
            $table->foreign('observaciones_id')->references('id')->on('renovaciones_observaciones')->onDelete(NULL);

            $table->text('observaciones')->nullable();
            
            $table->string('estatus_concentra')->nullable();
            $table->string('llamada_bo')->nullable();
            $table->string('plan_anterior')->nullable();
            $table->string('plan_actual')->nullable();
            $table->decimal('monto_plan_anterior', 10, 2)->nullable();
            $table->decimal('monto_plan_actual', 10, 2)->nullable();
            $table->timestamps();
        });

        ##permisos renovaciones
        $permisos[]=[
        'id'=>'103',
        'name'=>'Modulo de Renovaciones',
        'slug'=>'renovaciones.module',
        'description'=>'Habiltia el modulo de Renovaciones',
        'model'=>'Renovaciones',
        'created_at'=>Carbon::now(),
        'updated_at'=>Carbon::now()];
        
        $permisos[]=[
        'id'=>'104',
        'name'=>'Renovaciones Listado de Ventas',
        'slug'=>'renovaciones.index',
        'description'=>'Muestra el listado de las ventas',
        'model'=>'Renovaciones',
        'created_at'=>Carbon::now(),
        'updated_at'=>Carbon::now()];
        
        $permisos[]=[
        'id'=>'105',
        'name'=>'Renovaciones Crear Venta',
        'slug'=>'renovaciones.create',
        'description'=>'habilita Registrar nuevas ventas',
        'model'=>'Renovaciones',
        'created_at'=>Carbon::now(),
        'updated_at'=>Carbon::now()];
        
        $permisos[]=[
        'id'=>'106',
        'name'=>'Renovaciones Editar Ventas',
        'slug'=>'renovaciones.edit',
        'description'=>'Habilita Editar las ventas registradas',
        'model'=>'Renovaciones',
        'created_at'=>Carbon::now(),
        'updated_at'=>Carbon::now()];
        
        $permisos[]=[
        'id'=>'107',
        'name'=>'Renovaciones Eliminar Ventas',
        'slug'=>'renovaciones.delete',
        'description'=>'Habilita la eliminancion de las Ventas',
        'model'=>'Renovaciones',
        'created_at'=>Carbon::now(),
        'updated_at'=>Carbon::now()];
        
        $permisos[]=[
        'id'=>'108',
        'name'=>'Renovaciones Cargar Seguimientos',
        'slug'=>'renovaciones.import',
        'description'=>'Habilita La carga masiva de Seguimientos',
        'model'=>'Renovaciones',
        'created_at'=>Carbon::now(),
        'updated_at'=>Carbon::now()];
        
        $permisos[]=[
        'id'=>'109',
        'name'=>'Renovaciones Reporteria',
        'slug'=>'renovaciones.export',
        'description'=>'Habilita la descarga de reportes',
        'model'=>'Renovaciones',
        'created_at'=>Carbon::now(),
        'updated_at'=>Carbon::now()];

        $role = Role::where('slug', 'admin')->first();

        foreach ($permisos as $permiso) {
            $id = Permission::create($permiso)->id;           
            $role->attachPermission($id);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::where('id', '>=', '103')->delete();
        Schema::dropIfExists('renovaciones_historico');
        Schema::dropIfExists('renovaciones_ventas');
        Schema::dropIfExists('renovaciones_observaciones');
        Schema::dropIfExists('renovaciones_estatus');        
    }
};
