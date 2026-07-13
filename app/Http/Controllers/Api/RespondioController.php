<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Models\bait\BaitRespondio;
use App\Models\bait\BaitVentas;
use App\Models\bait\BaitHistoricos;

use App\Models\concentra\ConcentraRespondioModel;

use Carbon\Carbon;


class RespondioController extends Controller
{
////288747912
/*
{"idcontacto": $contact.id,
"numero_portabilidad": "$contact.numero_portabilidad",
"ciclo_de_vida": "$contact.lifecycle",
"usuario": "$contact.vendedor", "anuncio": "$contact.campana ",
 "numero_contacto": "$contact.phone ",
"nameworkspace":"Bait"
 }
*/
    public function store(Request $request)
    {
        Log::info("Solicitud recibida en Respondio", [
            'Data' => $request->all()
        ]);

        try {           
            switch ($request["nameworkspace"]) {
                case 'Bait':
                    $this->BaitStorage($request);                 
                    break;
                case 'Concentra': 
                    $this->ConcentraStorage($request);
                break;  
                default:
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Workspace no encontrado'
                    ], 404);             
                break;             
            } 
            return response()->json(['status' => 'success'], 200);
           
        } catch (\Exception $e) {
            // Registramos el error con nivel 'error' y detalles adicionales
            Log::error("Error Respondio: " . $e->getMessage(), [
                'idcontacto' => $request["idcontacto"],
                'usuario'    => $request["usuario"],
                'dn_cliente' => $request["numero_portabilidad"],
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Ocurrió un error al procesar la solicitud'
            ], 500);
        }
    }

    private function BaitStorage($request)
    {
        $datarecive = new BaitRespondio();
        $datarecive->idcontacto     = $request["idcontacto"];
        $datarecive->numero_portar  = $request["numero_portabilidad"] == "null" ? null : $request["numero_portabilidad"];
        $datarecive->ciclo_de_vida  = $request["ciclo_de_vida"];
        $datarecive->usuario        = $request["usuario"] == "null" ? null : $request["usuario"];
        $datarecive->numero_contacto = $request["numero_contacto"] == "null" ? null : $request["numero_contacto"];
        $datarecive->anuncio        = $request["anuncio"] == "null" ? null : $request["anuncio"];
        $datarecive->save();

        // Usamos first() directamente para evitar dos consultas (exists + first)
        $venta = BaitVentas::where('idcontacto', $request["idcontacto"])->first();

        if ($venta) {
            $venta->ciclo_vida = ucwords(strtolower($request["ciclo_de_vida"]));
            $venta->save();

            $historico = new BaitHistoricos();
            $historico->bait_ventas_id  = $venta->id;
            $historico->usuario         = $request["usuario"];
            $historico->estatus_intelix = $venta->estatus_intelix;
            $historico->save();
        }
        return true;
    }

    private function ConcentraStorage($request)
    {
        $datarecive = new ConcentraRespondioModel();
        $datarecive->workspace      = $request["identworkspace"];    
        $datarecive->idcontacto     = $request["idcontacto"];
        $datarecive->numero_portar  = $request["numero_portabilidad"] == "null" ? null : $request["numero_portabilidad"];
        $datarecive->ciclo_de_vida  = $request["ciclo_de_vida"];
        $datarecive->usuario        = $request["usuario"] == "null" ? null : $request["usuario"];
        $datarecive->numero_contacto = $request["numero_contacto"] == "null" ? null : $request["numero_contacto"];
        $datarecive->anuncio         = $request["anuncio"] == "null" ? null : $request["anuncio"];
        $datarecive->save();        
        return true;
    }
}
