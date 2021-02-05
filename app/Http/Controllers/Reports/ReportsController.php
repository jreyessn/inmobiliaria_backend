<?php

namespace App\Http\Controllers\Reports;

use App\Exports\FarmsMonthSheet;
use App\Exports\FrequencyVisitsReport;
use App\Exports\SupervisorMonthReport;
use App\Exports\SupervisorMonthSheet;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    
  /**
   * Reporte de frecuencia
   */
    public function frequencySupervisor(Request $request){

        $request->validate([
            'manager_id' => 'required',
            'supervisor_id' => 'nullable', 
            'farm_id' => 'nullable',
            'from' => 'required|date',
            'to' => 'required|date',
            'format' => 'required'
        ], [
            'from.required' => 'Fecha Desde es requerida',
            'to.required' => 'Fecha Hasta es requerida',
            'format.required' => 'El formato para obtener resultados es requerido',
            'manager_id.required' => 'El Gerente es requerido',
        ]);

        $managers = $request->input('manager_id', null);
        $supervisors = $request->input('supervisor_id', []);
        $farms = $request->input('farm_id', []);

        $subQuery = DB::table('visits')
                      ->selectRaw("
                        users.name,
                        farms.nombre_supervisor,
                        farms.nombre_gerente,
                        ((SELECT sum(max_score) FROM questions) / sum(visits_questions.score)) as result,
                        farms.nombre_centro
                      ")
                      ->join('visits_questions', 'visits_questions.visit_id', '=', 'visits.id')
                      ->join('farms', 'farms.id', '=', 'visits.farm_id', 'left')
                      ->join('farms_users', 'farms_users.farm_id', '=', 'visits.farm_id', 'left')
                      ->join('users', 'users.id', '=', 'visits.user_id', 'left')
                      ->where('visits.deleted_at', null)
                      ->whereBetween('visits.date', [$request->from, $request->to])
                      ->when(count($supervisors) > 0, function($query) use ($supervisors){

                        $query->whereIn('visits.user_id', $supervisors);
                        
                      })
                      ->when(!is_null($managers), function($query) use ($managers){

                        $managers = explode(',', $managers);

                        $query->whereIn("farms_users.user_id", $managers);
                        
                      })
                      ->when(count($farms) > 0, function($query) use ($farms){

                        $query->whereIn('visits.farm_id', $farms);

                      })
                      ->groupBy('visits.id')
                      ->orderBy('result');

        $result = DB::table(DB::raw("({$subQuery->toSql()}) as tableresult"))
                      ->mergeBindings($subQuery)
                      ->selectRaw("
                        name,
                        count(name) as visits_completed,
                        (result / count(nombre_supervisor)) as average,
                        min(result) as result_min,
                        nombre_centro
                      ")
                      ->groupBy("name")
                      ->get();
                      

        if(!is_null($managers)){
            $managers = explode(',', $managers);

            $managers = User::whereIn('id', $managers)->first();
        }

        $frequency['data'] = $result;
        $frequency['manager'] = $managers;
        $frequency['from'] = $request->from;
        $frequency['to'] = $request->to;
        $frequency['year'] = Carbon::parse($request->to)->format('Y');

        switch ($request->format) {
            case 'excel':
                return Excel::download(new FrequencyVisitsReport($frequency), 'export.xlsx');
            break;
            case 'pdf':
              return Excel::download(new FrequencyVisitsReport($frequency), 'export.pdf');
            break;
            default:
              return $frequency;
            break;
        }


    }

    /**
     * Reporte de promedio de supervisores por mes
     */
    public function supervisorMonth(Request $request){
        $request->validate([
          'supervisor_id' => 'nullable', 
          'month' => 'required',
          'year' => 'required|numeric|min:2000',
          'format' => 'required'
      ], [
          'month.required' => 'El Mes es requerido',
          'year.required' => 'El Año es requerido',
          'format.required' => 'El formato para obtener resultados es requerido',
      ]);

      $month = $request->get('month', Carbon::now()->format('m'));
      $year  = $request->get('year', Carbon::now()->format('Y'));
      $supervisors = $request->input('supervisor_id', []);

      /**
       * Se tiene pensado mostrar las hojas por cada mes. El problema es que los gráficos no se ajustan al contexto
       * de cada hoja y de momento esto debe trabajar solo con la consulta de un mes 
       */
      $result = array_map(function($month) use ($year, $supervisors){

          $carbonDate = Carbon::createFromDate($year, $month, 01);
          $carbonDate->setLocale('es');

          $date_init = $carbonDate->format('Y-m-d');

          $date_end  = Carbon::createFromDate($year, $month, $carbonDate->daysInMonth)->format('Y-m-d');
          
          $month = ucwords($carbonDate->monthName);
            
          $subQuery = DB::table('visits')
                      ->selectRaw("
                      users.name,
                      ((SELECT sum(max_score) FROM questions) / sum(visits_questions.score)) as result
                      ")
                      ->join('visits_questions', 'visits_questions.visit_id', '=', 'visits.id')
                      ->join('users', 'users.id', '=', 'visits.user_id', 'left')
                      ->where('visits.deleted_at', null)
                      ->when(count($supervisors) > 0, function($query) use ($supervisors){

                        $query->whereIn('visits.user_id', $supervisors);
                        
                      })
                      ->whereBetween('date', [$date_init, $date_end])
                      ->groupBy('visits.id')
                      ->orderBy('result');
                      
          $data = DB::table(DB::raw("({$subQuery->toSql()}) as tableresult"))
                        ->mergeBindings($subQuery)
                        ->selectRaw("
                          name,
                          avg(result) as average_result
                        ")
                        ->groupBy("name")
                        ->get();

            return compact('date_init', 'date_end', 'month', 'year', 'data');

        }, explode(',',$month));

      switch ($request->format) {
        case 'excel':
            return Excel::download(new SupervisorMonthSheet(reset($result)), 'export.xlsx');
        break;
        case 'pdf':
          return Excel::download(new SupervisorMonthSheet(reset($result)), 'export.pdf');
        break;
        default:
          return $result;
        break;
    }

    }
    /**
     * Reporte de promedio de puntajes granjas al mes
     */
    public function farmsMonth(Request $request){
        $request->validate([
          'farm_id' => 'nullable',
          'month' => 'required',
          'year' => 'required|numeric|min:2000',
          'format' => 'required'
      ], [
          'month.required' => 'El Mes es requerido',
          'year.required' => 'El Año es requerido',
          'format.required' => 'El formato para obtener resultados es requerido',
      ]);

      $month = $request->get('month', Carbon::now()->format('m'));
      $year  = $request->get('year', Carbon::now()->format('Y'));
      $farms = $request->input('farm_id', []);

      /**
       * Se tiene pensado mostrar las hojas por cada mes. El problema es que los gráficos no se ajustan al contexto
       * de cada hoja y de momento esto debe trabajar solo con la consulta de un mes 
       */
      $result = array_map(function($month) use ($year, $farms){

          $carbonDate = Carbon::createFromDate($year, $month, 01);

          $carbonDate->setLocale('es');

          $date_init = $carbonDate->format('Y-m-d');

          $date_end  = Carbon::createFromDate($year, $month, $carbonDate->daysInMonth)->format('Y-m-d');
          
          $month = ucwords($carbonDate->monthName);

          $subQuery = DB::table('visits')
                      ->selectRaw("
                         farms.nombre_centro as description,
                         ((SELECT sum(max_score) FROM questions) / sum(visits_questions.score)) as result
                      ")
                      ->join('visits_questions', 'visits_questions.visit_id', '=', 'visits.id')
                      ->join('users', 'users.id', '=', 'visits.user_id', 'left')
                      ->join('farms', 'farms.id', '=', 'visits.farm_id', 'left')
                      ->where('visits.deleted_at', null)
                      ->when(count($farms) > 0, function($query) use ($farms){

                        $query->whereIn('visits.farm_id', $farms);
                        
                      })
                      ->whereBetween('date', [$date_init, $date_end])
                      ->groupBy('visits.id')
                      ->orderBy('result');
                      
          $data = DB::table(DB::raw("({$subQuery->toSql()}) as tableresult"))
                        ->mergeBindings($subQuery)
                        ->selectRaw("
                           description,
                           avg(result) as average_result
                        ")
                        ->groupBy("description")
                        ->get();

            return compact('date_init', 'date_end', 'month', 'year', 'data');

        }, explode(',',$month));

      switch ($request->format) {
        case 'excel':
            return Excel::download(new FarmsMonthSheet(reset($result)), 'export.xlsx');
        break;
        case 'pdf':
          return Excel::download(new FarmsMonthSheet(reset($result)), 'export.pdf');
        break;
        default:
          return $result;
        break;
    }

    }
}
