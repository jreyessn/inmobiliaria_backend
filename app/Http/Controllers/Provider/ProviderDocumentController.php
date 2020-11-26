<?php

namespace App\Http\Controllers\Provider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\File;

use App\Models\Provider\ProviderDocument;
use App\Notifications\Providers\RejectDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ProviderDocumentController extends Controller
{

    public function changeStatus(Request $request){
        $providerDocument = ProviderDocument::findOrFail($request->provider_document_id);
     
        if(!$request->user()->hasPermissionTo('approve documents'))
            return response()->json(['message' => 'No cuenta con el permiso necesario para aprobar documentos'], 400);

        try {
            
            // reject document
            
            if($request->status == 2){
                $provider = $providerDocument->provider;
                $document = $providerDocument->document;

                $provider->user->notify(new RejectDocument($provider, $document, $request->note));
            }

            $providerDocument->approved = $request->status;
            $providerDocument->approved_at = Carbon::now();
            $providerDocument->note = $request->note;
            $providerDocument->approver_by_user_id = $request->user()->id;
            $providerDocument->save();

            return response()->json(['message' => 'Se ha cambiado el estado del documento con éxito'], 200);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Ocurrió un error al guardar. Contactar con soporte.'], 500);
        }
        
    }

    public function updateDocument(Request $request){
        $providerDocument = ProviderDocument::findOrFail($request->id);

        if(!is_null($request->file)){
            $file =  $request->file('file');

            $name =  basename(Storage::disk('local')->putFileAs($providerDocument->document->folder, $file, "{$providerDocument->id}-{$file->hashName()}"));
        
            $providerDocument->name = $name;
            $providerDocument->approved_at = null;
            $providerDocument->approved = 0;
            $providerDocument->note = null;
            $providerDocument->approver_by_user_id = null;
            $providerDocument->save();
        }

        return response()->json(['message' => 'Se ha cargado el documento correctamente'], 200);
        
    }

}
