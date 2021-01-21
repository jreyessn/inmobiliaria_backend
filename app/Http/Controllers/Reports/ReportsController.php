<?php

namespace App\Http\Controllers\Reports;

use App\Exports\FrequencyVisitsReport;
use App\Exports\SupervisorMonthReport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    
    public function frequencySupervisor(Request $request){

        $request->validate([
            'manager_id' => 'nullable',
            'supervisor_id' => 'nullable', 
            'farm_id' => 'nullable',
            'from' => 'required|date',
            'to' => 'required|date',
            'format' => 'required'
        ], [
            'from.required' => 'Fecha Desde es requerida',
            'to.required' => 'Fecha Hasta es requerida',
            'format.required' => 'El formato para obtener resultados es requerido',
        ]);

        $managers = $request->input('manager_id', null);
        $supervisors = $request->input('supervisor_id', null);
        $farms = $request->input('farm_id', null);
        
        $subQuery = DB::table('visits')
                      ->selectRaw("
                        users.name,
                        cost_center,
                        farms.nombre_supervisor,
                        farms.nombre_gerente,
                        ((SELECT sum(max_score) FROM questions) / sum(visits_questions.score)) as result,
                        farms.nombre_centro
                      ")
                      ->join('visits_questions', 'visits_questions.visit_id', '=', 'visits.id')
                      ->join('farms', 'farms.id', '=', 'visits.farm_id', 'left')
                      ->join('users', 'users.id', '=', 'visits.user_id', 'left')
                      ->where('visits.deleted_at', null)
                      ->whereBetween('visits.date', [$request->from, $request->to])
                      ->when(!is_null($supervisors), function($query) use ($supervisors){

                        $supervisors = explode(',', $supervisors);

                        $query->whereIn('visits.user_id', $supervisors);
                        
                      })
                      ->when(!is_null($farms), function($query) use ($farms){

                        $farms = explode(',', $farms);

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

    public function supervisorMonth(Request $request){
        $request->validate([
          'supervisor_id' => 'nullable', 
          'farm_id' => 'nullable',
          'month' => 'required',
          'year' => 'required',
          'format' => 'required'
      ], [
          'month.required' => 'El Mes es requerido',
          'year.required' => 'El Año es requerido',
          'format.required' => 'El formato para obtener resultados es requerido',
      ]);

      
      $subQuery = DB::table('visits')
                      ->selectRaw("
                        users.name,
                        ((SELECT sum(max_score) FROM questions) / sum(visits_questions.score)) as result
                      ")
                      ->join('visits_questions', 'visits_questions.visit_id', '=', 'visits.id')
                      ->join('users', 'users.id', '=', 'visits.user_id', 'left')
                      ->groupBy('visits.id')
                      ->orderBy('result');

      $result = DB::table(DB::raw("({$subQuery->toSql()}) as tableresult"))
                      ->mergeBindings($subQuery)
                      ->selectRaw("
                        name,
                        avg(result) as average_result
                      ")
                      ->groupBy("name")
                      ->get();

      switch ($request->format) {
        case 'excel':
            return Excel::download(new SupervisorMonthReport($result), 'export.xlsx');
        break;
        case 'pdf':
          return Excel::download(new SupervisorMonthReport($result), 'export.pdf');
        break;
        default:
          return $result;
        break;
    }

    }
}
