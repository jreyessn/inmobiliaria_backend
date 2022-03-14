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

    private $ImageRepositoryEloquent;

    function __construct(
        ImageRepositoryEloquent $ImageRepositoryEloquent 
    )
    {
        $this->ImageRepositoryEloquent = $ImageRepositoryEloquent;
    }

    public function image($name){

        if (Storage::disk('local')->exists($name) && $this->ImageRepositoryEloquent->existsWithName($name)) {
            return Storage::response($name);
        }

        abort(404);
    }

    public function destroy($name){

        if (Storage::disk('local')->exists($name) && $this->ImageRepositoryEloquent->existsWithName($name)) {

            $this->ImageRepositoryEloquent->destroyWithName($name);
            
            return response()->json(null, 204); 
        }

        abort(404);
    }

}
