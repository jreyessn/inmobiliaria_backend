<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Repositories\Provider\ProviderSapAuthoRepositoryEloquent;
use App\Repositories\Provider\ProviderSapRepositoryEloquent;
use Illuminate\Http\Request;
use App\Exports\SapExport;
use App\Notifications\Providers\RejectAuthorization;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ProviderSapAuthorizationController extends Controller
{

    protected $sapRepository;
    protected $sapAuthoRepository;
    
    function __construct(
        ProviderSapRepositoryEloquent $sapRepository, 
        ProviderSapAuthoRepositoryEloquent $sapAuthoRepository
    )
    {
        $this->sapRepository = $sapRepository;    
        $this->sapAuthoRepository = $sapAuthoRepository;    
    }

    public function store(Request $request){

        if(!$request->user()->hasPermissionTo('authorize providers sap'))
            return response()->json(['message' => 'No cuenta con el permiso necesario para autorizar'], 400);

        try {

            $data = $request->all();
            $data['user_id'] = $request->user()->id;
            $data['role_id'] = $request->user()->roles[0]->id;
            $data['approved_at'] = Carbon::now();

            $found = $this->sapAuthoRepository->where([
                'provider_sap_id' => $data['provider_sap_id'],
                'role_id' => $data['role_id']
            ])->first();
            $found->fill($data);
            $found->save();

            if($data['approved'] == 2){
                $found->provider_sap->provider->update(['can_edit' => 1]);
                $found->provider_sap->provider->user->notify(new RejectAuthorization($data['note']));
            }

            return response()->json(['message' => 'Se ha cambiado el estado de autorización éxitosamente'], 200);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Ocurrió un error al autorizar. Contactar con soporte.'], 500);
        }
    }

    public function show($id){
        $providerSap = $this->sapRepository->with('authorizations')->find($id);

        return $providerSap;
    }

    public function downloadExcelSap($id){
        $data = $this->sapRepository->getSapFormatExcelData($id);

        return Excel::download(new SapExport($data), 'users.xlsx');
    }

    public function sendSap($id){

        $data = $this->sapRepository->find($id);

        dd($data);
    }

}
