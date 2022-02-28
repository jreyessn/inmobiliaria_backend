<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Vehicle\LicensePlate;
use App\Models\Vehicle\PermissionsVehicle;
use App\Models\Vehicle\Vehicle;
use App\Notifications\Vehicles\LicenseExpired;
use App\Notifications\Vehicles\PermissionExpired;
use App\Notifications\Vehicles\VehicleDateLimit;
use Illuminate\Console\Command;

class CheckVehicles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:check_vehicles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica las fechas de expiración de algunas entidades del sistema de flotillas';

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
     * Notifica a los administradores las licencias vencidas. 
     * Cada 3 días se hará el recordatorio al registro especificamente
     *
     * @return int
     */
    public function handle()
    {
        $closureCriteria = function($query){
            $query->where("last_notification_expired_at", "<", now()->addDay(-3));
            $query->orWhere("last_notification_expired_at", null);
        };

        $vehiclesExpired = Vehicle::where("maintenance_limit_at", "<", now())
                                    ->where($closureCriteria)
                                    ->get();
        
        $permissions     = PermissionsVehicle::where("expiration_at", "<", now())
                                            ->where($closureCriteria)
                                            ->get();
        
        $licenses        = LicensePlate::where("expiration_at", "<", now())
                                            ->where($closureCriteria)
                                            ->get();
                                            
        $admins          = User::whereHas("roles", function($query){
                                $query->where("name", "Administrador");
                            })->get();

        if($vehiclesExpired->count() > 0){
            foreach ($vehiclesExpired as $vehicle) {
                foreach ($admins as $admin) {
                    $admin->notify(new VehicleDateLimit([
                        "vehicle_label" => $vehicle->label,
                        "expired_at"    => $vehicle->maintenance_limit_at->format("d/m/Y"),
                        "id"            => $vehicle->id,
                    ]));
                }

                $vehicle->last_notification_expired_at = now();
                $vehicle->save();
            }
        }

        if($permissions->count() > 0){
            foreach ($permissions as $permission) {
                foreach ($admins as $admin) {
                    $admin->notify(new PermissionExpired([
                        "vehicle_label" => $permission->vehicle->label,
                        "concept"       => $permission->concept,
                        "name"          => $permission->vehicle->user->name,
                        "expired_at"    => $permission->expiration_at->format("d/m/Y"),
                        "id"            => $permission->id,
                    ]));
                }

                $permission->last_notification_expired_at = now();
                $permission->save();
            }
        }

        if($licenses->count() > 0){
            foreach ($licenses as $license) {
                foreach ($admins as $admin) {
                    $admin->notify(new LicenseExpired([
                        "vehicle_label" => $license->vehicle->label,
                        "name"          => $license->vehicle->user->name,
                        "expired_at"    => $license->expiration_at->format("d/m/Y"),
                        "id"            => $license->id,
                    ]));
                }

                $license->last_notification_expired_at = now();
                $license->save();
            }
        }

        return 0;
    }
}
