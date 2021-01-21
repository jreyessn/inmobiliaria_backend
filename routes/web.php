<?php

use App\Exports\FrequencyVisitsReport;
use App\Exports\VisitReport;
use App\Models\Visit\Visit;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', 'Controller');

Route::get('/', function () {
   return view('welcome');
});

Route::get('report', function () {
   // return view('exports.visits');
   $visit = Visit::with('questions', 'mortalities')->find(1);

   return Excel::download(new VisitReport($visit), 'invoices.xlsx');
});

// Route::get('frequency', 'Reports\ReportsController@frequencySupervisor');
// Route::get('supervisor_month', 'Reports\ReportsController@supervisorMonth');
Route::get('farm_month', 'Reports\ReportsController@farmsMonth');


