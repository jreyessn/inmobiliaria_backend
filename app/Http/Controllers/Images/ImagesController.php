<?php

namespace App\Http\Controllers\Images;

use App\Http\Controllers\Controller;
use App\Repositories\Images\ImageRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{

    public function image($name){

        if (Storage::disk('local')->exists($name)) {
            return Storage::response($name);
        }

        abort(404);
    }

}
