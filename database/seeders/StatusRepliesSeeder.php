<?php

namespace Database\Seeders;

use App\Models\StatusReply;
use Illuminate\Database\Seeder;

class StatusRepliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusReply::create([
            "description" => "Atendido",
            "background_color" => "#0ee494",
            "border_color" => "#0CC27E",
            "color" => "#021a11",
        ]);
        
        StatusReply::create([
            "description" => "Has contestado",
            "background_color" => "#0ee494",
            "border_color" => "#0CC27E",
            "color" => "#021a11",
        ]);

        StatusReply::create([
            "description" => "Cliente ha contestado",
            "background_color" => "#33cae5",
            "border_color" => "#1CBCD8",
            "color" => "#000",
        ]);

        StatusReply::create([
            "description" => "Soporte ha contestado",
            "background_color" => "#33cae5",
            "border_color" => "#1CBCD8",
            "color" => "#000",
        ]);

        StatusReply::create([
            "description" => "No definido",
            "background_color" => "#ffa784",
            "border_color" => "#FF8D60",
            "color" => "#ad3100",
            "show_in_list" => 0
        ]);

        StatusReply::create([
            "description" => "Cliente ha abierto el Ticket",
            "background_color" => "#33cae5",
            "border_color" => "#1CBCD8",
            "color" => "#000",
        ]);

        StatusReply::create([
            "description" => "Soporte ha abierto el Ticket",
            "background_color" => "#33cae5",
            "border_color" => "#1CBCD8",
            "color" => "#000",
        ]);

        StatusReply::create([
            "description" => "Esperando respuesta",
            "background_color" => "#33cae5",
            "border_color" => "#1CBCD8",
            "color" => "#000",
            "show_in_list" => 0
        ]);

    }
}
