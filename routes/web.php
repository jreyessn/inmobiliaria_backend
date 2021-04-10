<?php

use App\Exports\FrequencyVisitsReport;
use App\Exports\VisitReport;
use App\Models\User;
use App\Models\Visit\Visit;
use App\Notifications\Plagas;
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
   User::find(1)->notify(new Plagas);
});



