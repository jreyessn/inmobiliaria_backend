<?php

namespace App\Http\Controllers;

use Illuminate\Http\File;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\File\FileStoreRequest;
use App\Http\Requests\File\FileUpdateRequest;
use App\Repositories\File\FileRepositoryEloquent;

class FileController extends Controller
{
    private $fileRepository;

    public function __construct(
        FileRepositoryEloquent $fileRepository
    ){
        $this->fileRepository = $fileRepository;
    }

    public function index()
    {
        return $this->fileRepository->customPaginate();
    }

    public function store(FileStoreRequest $request)
    {

        try {
            DB::beginTransaction();

            $data = $request->all();
            $file = $request->file("file");
            
            $data['name'] = basename(Storage::disk('local')->putFile($data['type'], new File($file)));
            
            $store = $this->fileRepository->create($data);
            
            DB::commit();

            return response()->json([
                "message" => "Registro éxitoso",
                "data" => $store
            ], 201);
        } 
        catch (\Exception $th) {
            DB::rollback();
                
            return response()->json(['message' => $th->getMessage()], 500);
        }

    }

    public function show($id, $download = null)
    {
        $file = $this->fileRepository->find($id);

        if(!$download)
            return $file;
        return Storage::disk('local')->download($file['type'].'/'.$file->name, $file->name);
    }

    public function showTerminos($download = null){
        $term = $this->fileRepository->where(['type' => 'terminos'])->first();

        if(!$download)
            return $term;
        return Storage::disk('local')->download('terminos/'.$term->name, $term->name);

    }

 
    public function update(FileUpdateRequest $request)
    {

        DB::beginTransaction();

        try {

            $data = $request->all();
            $file = $request->file('file');
            $currentFile = $this->fileRepository->find($data['id']);

            if(!is_null($file)){

                Storage::disk('local')->delete($currentFile->path);

                $data['name'] = basename(Storage::disk('local')->putFile($currentFile->type, new File($file)));
            }

            $this->fileRepository->update($data, $data['id']);

            DB::commit();  

            return response()->json([
                "message" => "Actualización exitosa",
                "data" => []
            ], 200);

        } catch (QueryException $th) {
            DB::rollback();

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $file = $this->fileRepository->find($id);
        $file->delete();

        return response()->json(null, 204);
    }

    
}
