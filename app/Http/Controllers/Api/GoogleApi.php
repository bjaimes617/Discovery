<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class GoogleApi extends Controller
{

    private $applicationName;
    private $credencials;
    private $spreadsheetId;
    private $range;

    public function __construct($applicationName = null, $credencials = null, $spreadsheetId = null, $range = 'Hoja1!')
    {
        $this->applicationName  = $applicationName;
        $this->credencials      = base_path($credencials);
        $this->spreadsheetId    = $spreadsheetId;
        $this->range            = $range;

        #ingsofware-dev@directagroupingsoftware.iam.gserviceaccount.com

    }

    private function SearchResources(): array
    {

        $client = new Client();
        $client->setApplicationName($this->applicationName);
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig($this->credencials); // Tu archivo JSON

        $service        = new Sheets($client);
        $spreadsheetId  = $this->spreadsheetId; // El ID de tu Hoja de Cálculo
        $range          = $this->range; // Rango a leer/escribir

        // Leer datos
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        return $values;
    }

    public function storeResourcesSheets($array)
    {

        $client = new Client();
        $client->setApplicationName($this->applicationName);
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig($this->credencials); // Tu archivo JSON

        $service        = new Sheets($client);
        $spreadsheetId  = $this->spreadsheetId; // El ID de tu Hoja de Cálculo
        $range          = $this->range; // Rango a leer/escribir

        $body = new \Google_Service_Sheets_ValueRange([
            'values' => $array
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        $result = $service->spreadsheets_values->append(
            $spreadsheetId,
            $range,
            $body,
            $params
        );

        return true;
    }

    /**
    public function show()
    {
        //PARA BUSCAR EN EL SHEET
        $googleApi = new GoogleApi("Discovery", "directagroupingsoftware-51fef28451b7.json", "1rLM202rK0t1ZY11fihY_-EYbMlUVPiGuyYAiFr_34d4", "Ventas Movil");
        $rs = $googleApi->SearchResources();
        dd($rs);
        //return GoogleApi::SearchResources("Discovery","directagroupingsoftware-51fef28451b7.json","1l7_GXDtORK3dFHB4kmnbKMEiTsFUUWoF6kLT1YGMWkM");
    }              
    public function store(Request $request)
    {
        $array = [
            ["11", "2025/01/15", "cc", "BraianWW", "jaimes", "rodriguez", "19888888", "SEMENGTADO B", "PORTABILIDAD", "CCS", "DD", "CAPITAL", "MI CASA", "2225558899", "BRAIAN.JAMESWW@DD.COM", "CONEXION 1", "SSSSSS", "DDDDDD", "BRSF", "232323", "ESPOSO", "ASDA", "22222", "QWE", "ASDASD", "2223123", "ASDASD", "PRUEBAS", "JUEDMY", "JUEDMY", "19888777", "BRAIAN JAIS"]
        ];

        $googleApi = new GoogleApi("Discovery", "directagroupingsoftware-51fef28451b7.json", "1rLM202rK0t1ZY11fihY_-EYbMlUVPiGuyYAiFr_34d4", "Ventas Movil");
        $rs = $googleApi->storeResourcesSheets($array);
        dd($rs);
    }**/
}
