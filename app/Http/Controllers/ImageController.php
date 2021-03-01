<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * route api
     */
    function save(Request $request){
        $name = $request->upload->getClientOriginalName();
        $name = rand(0,100000).'-'.$name;

        $imageSaved = Storage::disk("public")->putFileAs('images', $request->upload, $name);

        return [
            "url" => asset("storage/".$imageSaved)
        ];
    }


}
