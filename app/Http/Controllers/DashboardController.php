<?php

namespace App\Http\Controllers;

use Carbon\CarbonInterval;
use App\Models\BusinessType;
use App\Models\Provider\Provider;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
    function index(){
        
        $data = [];

        $data['business_types'] = BusinessType::select('description')->withCount('providers')->get()->toArray();

        $data['providers_all'] = Provider::count();

        $data['providers_pendings'] = Provider::has('provider_sap', '<', 1)->count();
                
        $data['providers_sap_register'] = Provider::whereHas('authorizations', function($query){
            $query->where('provider_sap_authorizations.approved', '!=', 1);
        })->count();
        
        $data['providers_contracteds'] = Provider::where('contracted', 1)->count();
        $data['providers_no_contracteds'] = Provider::where('contracted', 2)->count();
        $data['providers_inactivated'] = Provider::whereNotNull('inactivated_at')->count();

        $data['averages'] = $this->getAveragesPhases();
        return $data;
    }

    private function getAveragesPhases()
    {
        $data = [];

        /* 
         * Etapa 1.
         * Tiempo que tarda compras en aceptar la solicitud de registro de usuario del proveedor 
        */
        $secondsPhase = DB::select("SELECT AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as average 
                                      FROM (SELECT approved_at, created_at FROM `applicant_providers`) as tablita");

        $data['phase1'] = [
            'name' => 'Promedio que compras acepta la solicitud de registro de usuarios para proveedores',
            'minutes' => (int) (((int) $secondsPhase[0]->average) / 60),
            'description' => CarbonInterval::seconds($secondsPhase[0]->average)->cascade()->forHumans()
        ];
        
        /* 
        * Etapa 2.
        * Tiempo que tarda el proveedor en enviar su informacion y documentos
        */
        
        $secondsPhase = DB::select("SELECT AVG(TIMESTAMPDIFF(SECOND, users_created_at, providers_created_at)) as average 
                                    FROM (
                                        SELECT 
                                        users.created_at as users_created_at,  
                                        providers.created_at as providers_created_at 
                                        FROM `providers` LEFT JOIN users ON users.id = providers.user_id) as tablita");

        $data['phase2'] = [
            'name' => 'Promedio que proveedores dan de alta su información y documentos',
            'minutes' => (int) (((int) $secondsPhase[0]->average) / 60),
            'description' => CarbonInterval::seconds($secondsPhase[0]->average)->cascade()->forHumans()
        ];

        /* 
        * Etapa 3.
        * Tiempo de aprobación de documentos
        */

       $secondsPhase = DB::select("SELECT AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as average 
                                   FROM (SELECT created_at, approved_at FROM `provider_documents` where approved = 1) as tablita");

        $data['phase3'] = [
            'name' => 'Promedio en que compras aprueba los documentos',
            'minutes' => (int) (((int) $secondsPhase[0]->average) / 60),
            'description' => CarbonInterval::seconds($secondsPhase[0]->average)->cascade()->forHumans()
        ];

        /* 
        * Etapa 4.
        * Tiempo de aprobación de áreas
        */

       $secondsPhase = DB::select("SELECT AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as average 
                                   FROM (SELECT created_at, approved_at FROM `provider_sap_authorizations` where approved = 1) as tablita");

        $data['phase4'] = [
            'name' => 'Promedio en que las áreas autorizan a SAP',
            'minutes' => (int) (((int) $secondsPhase[0]->average) / 60),
            'description' => CarbonInterval::seconds($secondsPhase[0]->average)->cascade()->forHumans()
        ];

        return $data;
    }

}
