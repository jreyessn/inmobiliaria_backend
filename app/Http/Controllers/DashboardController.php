<?php

namespace App\Http\Controllers;

use Carbon\CarbonInterval;
use App\Models\BusinessType;
use App\Models\Provider\Provider;
use Carbon\Carbon;
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

        $timePhase =  CarbonInterval::create(0,0,0,0,0,0, (int) $secondsPhase[0]->average);

        $data['phase1'] = [
            'name' => 'Promedio que compras acepta la solicitud de registro de usuarios para proveedores',
            'hours' => number_format($timePhase->totalHours, 2),
            'minutes' => $timePhase->totalMinutes,
            'seconds' => $timePhase->totalSeconds,
            'description' => CarbonInterval::seconds($secondsPhase[0]->average)->cascade()->forHumans(['parts' => 3, 'short' => true])
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
        $timePhase =  CarbonInterval::create(0,0,0,0,0,0, (int) $secondsPhase[0]->average);

        $data['phase2'] = [
            'name' => 'Promedio que proveedores dan de alta su información y documentos',
            'hours' => number_format($timePhase->totalHours, 2),
            'minutes' => $timePhase->totalMinutes,
            'seconds' => $timePhase->totalSeconds,
            'description' => CarbonInterval::seconds($secondsPhase[0]->average)->cascade()->forHumans(['parts' => 3, 'short' => true])
        ];

        /* 
        * Etapa 3.
        * Tiempo de aprobación de documentos
        */

       $secondsPhase = DB::select("SELECT 
                                        AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as average
                                    FROM(
                                    SELECT * FROM (
                                        SELECT 
                                            provider_id,
                                            min(approved) as approved,
                                            created_at,
                                            max(approved_at) as approved_at
                                        FROM provider_documents
                                        GROUP BY provider_id
                                        ) as tablita
                                        WHERE approved = 1
                                        ) as tablitaagrupada");

        $timePhase =  CarbonInterval::create(0,0,0,0,0,0, (int) $secondsPhase[0]->average);

        $data['phase3'] = [
            'name' => 'Promedio en que compras aprueba los documentos',
            'hours' => number_format($timePhase->totalHours, 2),
            'minutes' => $timePhase->totalMinutes,
            'seconds' => $timePhase->totalSeconds,
            'description' => CarbonInterval::seconds($secondsPhase[0]->average)->cascade()->forHumans(['parts' => 3, 'short' => true])
        ];

        /* 
        * Etapa 4.
        * Tiempo de aprobación de áreas
        */

       $secondsPhase = DB::select("SELECT 
                                        AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as average
                                    FROM(
                                    SELECT * FROM (
                                    SELECT 
                                        provider_sap_authorizations.provider_sap_id,
                                        min(provider_sap_authorizations.approved) as approved,
                                        provider_sap_authorizations.created_at,
                                        max(provider_sap_authorizations.approved_at) as approved_at
                                    FROM provider_sap_authorizations
                                    GROUP BY provider_sap_id
                                    ) as tablita
                                    WHERE approved = 1
                                    ) as tablitaagrupada");

        $timePhase =  CarbonInterval::create(0,0,0,0,0,0, (int) $secondsPhase[0]->average);

        $data['phase4'] = [
            'name' => 'Promedio en que las áreas autorizan a SAP',
            'hours' => number_format($timePhase->totalHours, 2),
            'minutes' => $timePhase->totalMinutes,
            'seconds' => $timePhase->totalSeconds,
            'description' => CarbonInterval::seconds($secondsPhase[0]->average)->cascade()->forHumans(['parts' => 3, 'short' => true])
        ];

        /* 
        * Total etapas.
        * Tiempo total
        */

        $sumSeconds = ((int) $data['phase1']['seconds'] + (int) $data['phase2']['seconds'] + (int) $data['phase3']['seconds'] + (int) $data['phase4']['seconds']);
        $timeTotal = CarbonInterval::create(0,0,0,0,0,0, (int) $sumSeconds);

        $data['phase5'] = [
            'name' => 'Total de etapas',
            'hours' => number_format($timeTotal->totalHours, 2),
            'minutes' => $timeTotal->totalMinutes,
            'seconds' => $timeTotal->totalSeconds,
            'description' => CarbonInterval::seconds($timeTotal->totalSeconds)->cascade()->forHumans(['parts' => 3, 'short' => true])
        ];

        $data['months'] = $this->timesAllMonths();

        return $data;
    }

    private function timesAllMonths()
    {

        /* Se busca el rango de meses que se han registrado los solicitantes */

        // $firstCreateRegister = DB::table('applicant_providers')->orderBy('created_at', 'asc')->limit(1)->first();

        $period = \Carbon\CarbonPeriod::create('2020-11-01', '1 month', Carbon::now());
        $months = [];

        foreach ($period as $dt) {
            array_push($months, [
                'month_year' => $dt->format("Y-m"),
                'first_day' => $dt->format("Y-m-01"),
                'end_day' =>  $dt->format("Y-m-{$dt->endOfMonth()->format('d')}"),
                'description' => ucwords("{$dt->year} - {$dt->monthName}")
            ]);
       }

       /* Se consulta por cada mes */

       foreach ($months as $key => $month) {

           $phaseRegisters = DB::select("SELECT 
                                    AVG(TIMESTAMPDIFF(SECOND, create_applicant, approved_applicant)) as average
                                FROM (
                                SELECT 
                                    applicant_providers.approved_at as approved_applicant, 
                                    applicant_providers.created_at as create_applicant
                                FROM `applicant_providers`
                                WHERE date(applicant_providers.created_at) BETWEEN '{$month['first_day']}' AND '{$month['end_day']}'
                                ) as tablita");

           $phaseRegisterDocuments = DB::select("SELECT 
                                    AVG(TIMESTAMPDIFF(SECOND, users_created, providers_created)) as average
                                FROM (
                                SELECT 
                                    users.created_at as users_created,
                                    providers.created_at as providers_created
                                FROM `users`
                                LEFT JOIN providers ON providers.user_id = users.id
                                WHERE date(users.created_at) BETWEEN '{$month['first_day']}' AND '{$month['end_day']}'
                                ) as tablita");
            
           $phaseAuthorizations = DB::select("SELECT 
                                        AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as average
                                    FROM(
                                    SELECT * FROM (
                                    SELECT 
                                        provider_sap_authorizations.provider_sap_id,
                                        min(provider_sap_authorizations.approved) as approved,
                                        provider_sap_authorizations.created_at,
                                        max(provider_sap_authorizations.approved_at) as approved_at
                                    FROM provider_sap_authorizations
                                    WHERE date(provider_sap_authorizations.created_at) BETWEEN '{$month['first_day']}' AND '{$month['end_day']}'
                                    GROUP BY provider_sap_id
                                    ) as tablita
                                    WHERE approved = 1
                                    ) as tablitaagrupada");

           $phaseDocuments = DB::select("SELECT 
                                            AVG(TIMESTAMPDIFF(SECOND, created_at, approved_at)) as average
                                        FROM(
                                        SELECT * FROM (
                                            SELECT 
                                               provider_id,
                                               min(approved) as approved,
                                               created_at,
                                               max(approved_at) as approved_at
                                            FROM provider_documents
                                            WHERE date(provider_documents.created_at) BETWEEN '{$month['first_day']}' AND '{$month['end_day']}'
                                            GROUP BY provider_id
                                            ) as tablita
                                            WHERE approved = 1
                                            ) as tablitaagrupada");
                                            

           $sumSeconds = ((int) $phaseRegisters[0]->average + (int) $phaseRegisterDocuments[0]->average + (int) $phaseAuthorizations[0]->average + (int) $phaseDocuments[0]->average) / 4;
           $timeTotal = CarbonInterval::create(0,0,0,0,0,0, (int) $sumSeconds);
           $months[$key]['total_hours'] = number_format($timeTotal->totalHours, 2, '.', '');
       }

       return $months;
    }

}
