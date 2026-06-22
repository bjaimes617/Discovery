<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SactunTokenGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'santum:usertoken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera los token de usuarios';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $userx = $this->ask('====> Ingrese el Nombre de Usuario');

        if (!User::where('usuario', $userx)->exists()) {
            $this->error('====> usuario no Encontrado. Adios.');
            return    Command::FAILURE;
        } else {
            $user = User::where('usuario', $userx)->first();

            $accion = $this->choice(
                'Que desea hacer?',
                ['Crear Token', 'Eliminar Todos los Token', 'Salir'],
                3
            );

            switch ($accion) {
                case "Crear Token":
                    $habilidad = $this->ask('====> Ingrese el nombre de la Habilidad a asignar al Token');
                    $nombre = $this->ask('====> Ingrese el nombre que se le dara al Token Personal');
                    $token = $user->createToken($nombre, [$habilidad])->plainTextToken;
                    $this->info("Bearer Token:" . $token);
                    $iden = explode("|", $token);

                    DB::table('personal_access_tokens')->where('id', $iden[0])->update(["encrypt" => $token]);
                    break;
                case 'Eliminar Todos los Token':
                    if ($this->confirm('Se Eliminaran todos los Token de acceso de este usuario, Esta Seguro en continuar?')) {
                        $user->tokens()->delete();
                        $this->info("Todos los Tokens han sido eliminados");
                    } else {
                    }
                    break;
            }

            return Command::SUCCESS;
        }
    }
}
