<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\DocumentUpdateRequest;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Repositories\Document\DocumentRepositoryEloquent;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    private $repository;

    function __construct(
        DocumentRepositoryEloquent $repository
    ){
        $this->repository = $repository;
    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->repository->all();

        return compact('data');
    }

    public function show(Request $request, $id, $download = null){
        $document = $this->repository->find($id);

        if(!$download)
            return $document;

        try {

            return Storage::disk('local')->download('examples_documents/'.$document->example, $document->example);

        } catch (\Throwable $th) {
            return response()->json(null, 404);
        }
        
            
    }

    public function update(DocumentUpdateRequest $request){

        $file = $request->file("file");
        $document = $this->repository->find($request->id);

        try {

            if(!is_null($file)){

                $this->removeFile($document);
                $document->example = basename(Storage::disk('local')->putFile('examples_documents/', new File($file)));
           
            }
            
            $document->description = $request->description;
            $document->save();
            
            return response()->json(['message' => 'Guardado con éxito']);

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->message], 500);
        }
    }

    // no elimina el registro, solo limpia el archivo
    
    public function destroy($id){
        $document = $this->repository->find($id);
        
        $this->removeFile($document);
        $document->example = null;
        $document->save();

        return response()->json(null, 204);
    }

    private function removeFile($document){
        if(!is_null($document->example))
             Storage::disk('local')->delete('examples_documents/'.$document->example); 
    }

}
