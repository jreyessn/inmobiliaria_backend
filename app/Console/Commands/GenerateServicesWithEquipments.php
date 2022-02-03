<?php

namespace App\Console\Commands;

use App\Http\Controllers\Equipments\EquipmentsController;
use Illuminate\Console\Command;

class GenerateServicesWithEquipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera los servicios siguientes automaticamente según los parametros de cada equipo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $controller = app()->make(EquipmentsController::class);
        
        app()->call([$controller, 'scheduleServices']);
        
        return 0;
    }
}
