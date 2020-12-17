<?php

namespace App\Repositories\Provider;

use App\Models\Document;
use Illuminate\Http\File;

use App\Models\Provider\Provider;
use App\Repositories\AppRepository;
use Illuminate\Support\Facades\Storage;
use App\Models\Provider\ProviderDocument;
use App\Models\Provider\ProviderReference;
use App\Models\Provider\ProviderAccountBank;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class ProviderRepositoryEloquent.
 *
 * @package namespace App\Repositories\Provider;
 */
class ProviderRepositoryEloquent extends AppRepository
{

    protected $fieldSearchable = [
        'applicant_name' => 'like',
        'business_name' => 'like',
        'business_type.description' => 'like',
        'rfc' => 'like',
    ];

    private $file_to_date = [
        'constancia_situacion_fiscal_file' => 'constancia_situacion_fiscal_date',
        'formato_32d_file' => 'formato_32d_date',
        'estado_cuenta_file' => 'estado_cuenta_date',
        'comprobante_domicilio_file' => 'comprobante_domicilio_date',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Provider::class;
    }

    public function list(){
        
        $this->when(current_role('id') == 2, function($query){
            $query->where('user_id', request()->user()->id);
        });

        return $this->customPaginate();
    }

    public function save($data){
        
        $data['user_id'] = request()->user()->id;

        $store = $this->create($data);
        
        $data['provider_id'] = $store->id;
        
        $store->retention_types()->sync($data['retention_type_id']);
        $store->retention_indicators()->sync($data['retention_indicator_id']);

        $this->saveReferences($data);
        $this->saveAccountBanks($data);
        $this->saveDocuments($data);

        return $store;
    }    

    public function saveUpdate($data){
        $data['provider_id'] = $data['id'];

        $update = $this->find($data['provider_id']);
        $update->can_edit = 0;
        $update->fill($data);
        $update->save();

        // cuando se actualiza, las autorizaciones pasan a estar en espera nuevamente (en caso de que hayan rechazadas)
        // se asume que el proveedor actualizó su info en funcion de los rechazos 

        foreach($update->authorizations as $authorization){
            if($authorization->approved == 2)
                $authorization->update([
                    'approved' => 0,
                    'note' => null
                ]);
        }

        $update->retention_types()->sync($data['retention_type_id']);
        $update->retention_indicators()->sync($data['retention_indicator_id']);

        ProviderReference::where('provider_id', $data['provider_id'])->delete();
        ProviderAccountBank::where('provider_id', $data['provider_id'])->delete();

        $this->saveReferences($data);
        $this->saveAccountBanks($data);
        $this->saveDocuments($data);
    }

    public function saveAccountBanks($data){
        $accounts = array();

        foreach($data['account_holder'] as $key => $value) {
            array_push($accounts, [
                'provider_id' => $data['provider_id'],
                'account_holder' => $data['account_holder'][$key],
                "account_number" => $data['account_number'][$key],
                "account_clabe" => $data['account_clabe'][$key],
                "swift_code" => $data['swift_code'][$key],
                "routing_aba_number" => $data['routing_aba_number'][$key],
                "bank_name" => $data['bank_name'][$key],
                "bank_address" => $data['bank_address'][$key],
                "bank_country_id" => $data['bank_country_id'][$key],
                "bank_id" => $data['bank_id'][$key]
            ]);
        }

        ProviderAccountBank::insert($accounts);
    }

    public function saveReferences($data){
        $references = array();

        foreach($data['reference_provider_name'] as $key => $value) {
            array_push($references, [
                'provider_id' => $data['provider_id'],
                'business_name' => $data['reference_provider_name'][$key],
                'contact' => $data['reference_contact'][$key],
                'phone' => $data['reference_phone'][$key],
                'email' => $data['reference_email'][$key],
            ]);
        }

        ProviderReference::insert($references);
    }

    public function saveDocuments($data){
        $provider_id = $data['provider_id'];
        $documents = Document::all();

        foreach($documents as $document){
            $currentFile = $data[ $document->file_input_name ] ?? null;
            $dateKey = $this->file_to_date[$document->file_input_name] ?? null;
            $date = $data[$dateKey] ?? null;
            
            /*  
            * Condición ocurre cuando se edita una fecha pero no se adjunta ningún documento
            */
            if(!is_null($date) && is_null($currentFile)){
                $providerDocument = ProviderDocument::where([
                    'provider_id' => $provider_id,
                    'document_id' => $document->id
                ]);

                if(!is_null($providerDocument))
                    $providerDocument->update(['date' => $date]);

            }

            if(is_null($currentFile))
                continue;

                $documentProvider = ProviderDocument::updateOrCreate(
                    [
                        'provider_id' => $provider_id,
                        'document_id' => $document->id
                    ],
                    [
                        'provider_id'         => $provider_id,
                        'document_id'         => $document->id,
                        'date'                => $date,
                        'name'                => 'temp',
                        'approved'            => 0,
                        'note'                => null,
                        'approver_by_user_id' => null,
                    ]
                );

                $file =  new File($currentFile);

                $name =  basename(Storage::disk('local')->putFileAs($document->folder, $file, "{$documentProvider->id}-{$file->hashName()}"));

                $documentProvider->name = $name;
                $documentProvider->save();
            
        }


    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
