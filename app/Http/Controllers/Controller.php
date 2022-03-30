<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Country\City;
use App\Models\Country\Country;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Actualiza los valores de configuración general de la app
     */
    public function putConfig(Request $request)
    {
        $request->validate([
            "country_id" => "nullable|exists:countries,id",
            "city_id"    => "nullable|exists:cities,id",
            "logo_white" => "file|mimes:jpg,jpeg,png|dimensions:max_width=900,max_height=700",
            "logo_dark"  => "file|mimes:jpg,jpeg,png|dimensions:max_width=900,max_height=700"
        ]);
        $configs = Configuration::get();

        foreach (sanitize_null($request->all()) as $key => $value) {
            $configFound = $configs->firstWhere("key", $key);
  
            if($key == "logo_white"){
                $file    = $request->file("logo_white");
                $value   = Storage::disk('public')->putFileAs("logos", $file, "logo_white-{$file->hashName()}"); 
            }

            if($key == "logo_dark"){
                $file    = $request->file("logo_dark");
                $value   = Storage::disk('public')->putFileAs("logos", $file, "logo_dark-{$file->hashName()}"); 
            }

            if($configFound){
                $configFound->value = $value;
                $configFound->save();
            }

        }

    }

    /**
     * Obtiene los valores de configuración general
     */
    public function config()
    {
        $configs = Configuration::get()->flatMap(function($item){
            $value = $item->value;

            if($item->key == "logo_dark" || $item->key == "logo_white"){
                $value = url("/storage/". $item->value);
            }

            return [ $item->key => $value ];
        });

        foreach ($configs as $key => $configValue) {
            if($key == "city_id"){
                $configs["city"] = City::find($configValue);
            }
            if($key == "country_id"){
                $configs["country"] = Country::find($configValue);
            }
        }

        return $configs;
    }

    /**
     * Manejador de Artisan desde petición GET
     * 
     */
    public function handlerArtisan(){
        // Sentencia php artisan
        $sentence = request()->get("sentence", null);
        // Sentencia password de la base de datos (para evitar exponer demasiado la ruta)
        $password = request()->get("password", null);

        $passwordEnv = getenv("DB_PASSWORD");

        if($password == $passwordEnv && $sentence == "reset"){
            Artisan::call("migrate:fresh", ["--force" => true, "--seed"]);
        }

        if($password == $passwordEnv && $sentence == "migrate"){
            Artisan::call("migrate", ["--force" => true]);
        }

        if($password == $passwordEnv && $sentence == "passport"){
            Artisan::call("passport:install", ["--force" => true]);
        }

    }

}
