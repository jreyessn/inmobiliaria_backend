<?php

namespace App\Repositories\Provider;

use App\Models\Provider\ProviderSap;
use App\Models\Provider\ProviderSapAuthorization;
use App\Models\Provider\ProviderSapCompaniesParticipate;
use App\Models\Provider\ProviderSapOrganization;
use App\Models\Provider\ProviderSapSociety;
use App\Models\Provider\ProviderSapToleranceGroup;
use App\Models\Society;
use App\Repositories\AppRepository;
use Carbon\Carbon;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class ProviderSapRepositoryEloquent.
 *
 * @package namespace App\Repositories\Provider;
 */
class ProviderSapRepositoryEloquent extends AppRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */

    private $roleIdAuthorizators = [3,4,5,6,7];

    public function model()
    {
        return ProviderSap::class;
    }

    public function save($data){
        $providerSap = new ProviderSap();
        $providerSap->fill($data);
        $providerSap->save();

        $this->saveTolerances($providerSap, $data['tolerances']);
        $this->saveOrganizations($providerSap, $data['organizations']);
        $this->saveCompaniesParticipates($providerSap, $data['companies_participates']);
        $this->saveSocieties($providerSap, $data['societies']);
        $this->createAuthorizators($providerSap);

        return $providerSap;
    }

    public function saveUpdate($data){
        $providerSap = $this->find($data['id']);
        $providerSap->fill($data);
        $providerSap->save();

        ProviderSapToleranceGroup::where('provider_sap_id', $providerSap->id)->delete();
        ProviderSapOrganization::where('provider_sap_id', $providerSap->id)->delete();
        ProviderSapCompaniesParticipate::where('provider_sap_id', $providerSap->id)->delete();
        ProviderSapSociety::where('provider_sap_id', $providerSap->id)->delete();

        $this->saveTolerances($providerSap, $data['tolerances']);
        $this->saveOrganizations($providerSap, $data['organizations']);
        $this->saveCompaniesParticipates($providerSap, $data['companies_participates']);
        $this->saveSocieties($providerSap, $data['societies']);

        return $providerSap;
    }

    public function saveTolerances($providerSap, array $tolerances){
        foreach ($tolerances as $item) {
                ProviderSapToleranceGroup::create([ 
                    'tolerance_group_id' => $item,
                    'provider_sap_id' => $providerSap->id
                ]);
        }
    }
    
    public function saveOrganizations($providerSap, array $organizations){
        foreach ($organizations as $item) {
            ProviderSapOrganization::create([ 
                'organization_id' => $item,
                'provider_sap_id' => $providerSap->id
            ]);
        }
    }
    
    public function saveCompaniesParticipates($providerSap, array $companies){
        foreach ($companies as $item) {
            ProviderSapCompaniesParticipate::create([ 
                'society_id' => $item,
                'provider_sap_id' => $providerSap->id
            ]);
        }
    }
    
    public function saveSocieties($providerSap, array $societies){
        foreach ($societies as $item) {
            ProviderSapSociety::create([ 
                'society_id' => $item,
                'provider_sap_id' => $providerSap->id
            ]);
        }
    }

    public function createAuthorizators($providerSap){

        foreach ($this->roleIdAuthorizators as $role_id) {

            ProviderSapAuthorization::updateOrCreate(
                [
                   'provider_sap_id' => $providerSap->id,
                   'role_id' => $role_id  
                ],
                [
                    'note' => null,
                    'user_id' => null,
                    'role_id' => $role_id,
                    'approved' => 0
                ]
            );

        }
    }
    
    public function getSapFormatExcelData($id = null){
        
        if($id){
            $sap = $this->find($id);
            
            return [ $this->mapSapFormatExcel($sap) ];
        }
        
        return [];
    }

    private function mapSapFormatExcel($item){
        $data = array();
        $societiesMap = Society::orderBy('orden', 'asc')->get()->pluck('description')->toArray();
        $data[] = Carbon::parse($item->created_at)->format('d/m/yy');   
        $data[] = '';//$item->provider_id;
        $data[] = $item->societies->pluck('code')->implode(',') ?? '';

        $data[] = $item->organizations->pluck('code')->implode(',') ?? '';
        $data[] = $item->accounts_group->code;
        $data[] = $item->treatment->description;
        $data[] = $item->provider->business_name;
        $data[] = ''; // para abono en cuenta??
        $data[] = $item->provider->business_name;
        $data[] = $item->provider->street_address;
        $data[] = $item->provider->street_number;
        $data[] = $item->provider->colony;
        $data[] = $item->provider->zip_code;
        $data[] = $item->provider->city->name ?? ''; 
        $data[] = $item->provider->country->iso2;
        $data[] = $item->provider->state->name ?? '';
        $data[] = $item->provider->phone;
        $data[] = ''; // fax ??
        $data[] = $item->provider->rfc;
        $data[] = $item->curp;
        $data[] = $item->provider->natural_person? 'X' : '';
        $data[] = $item->alba;
        $data[] = $item->provider->account_banks->pluck('bank_country')->pluck('description')->implode(',') ?? '';
        $data[] = $item->provider->account_banks->pluck('bank')->pluck('code')->implode(',') ?? '';
        $data[] = $item->provider->account_banks->pluck('account_number')->implode(',') ?? '';
        $data[] = $item->provider->account_banks->pluck('account_holder')[0] ?? '';
        $data[] = $item->type_bank_interlocutor->description;
        $data[] = $item->reference_bank;
        $data[] = $item->number_account_alternative;
        $data[] = $item->treasury_group->code;
        $data[] = $item->associated_account->code;
        $data[] = $item->clave_clasific;
        $data[] = $item->previous_account_number;
        $data[] = $item->payment_condition->code;
        $data[] = $item->verif_fra_dob? 'X' : '';
        $data[] = $item->payment_method->code;
        $data[] = $item->block_payment? 'X' : '';
        $data[] = $item->tolerance_groups->pluck('code')->implode(',') ?? '';
        $data[] = $item->currency->code;
        $data[] = $item->incoterms;
        $data[] = $item->description_incoterms;
        $data[] = $item->group_schema_pro;
        $data[] = $item->verif_invoice_base_em? 'X' : '';
        $data[] = $item->verif_invoice_relac_service? 'X' : '';
        $data[] = $item->order_automatic_permitted? 'X' : '';
        $data[] = $item->conc_bonus_specie;
        $data[] = $item->group_purchase;
        $data[] = $item->term_delivery_prev;
        $data[] = $item->provider->retention_country;
        $data[] = $item->provider->retention_types->pluck('code')->implode(',') ?? '';
        $data[] = $item->provider->retention_indicators->pluck('type')->implode(',') ?? '';
        $data[] = $item->subject;

        foreach ($societiesMap as $society) {
            $foundSociety = $item->companies_participates->where('description', $society)->first();
            
            $data[] = $foundSociety? 'X' : '';
        }

        $data[] = ''; // observacion??
        $data[] = $item->applicant;
        $data[] = $item->purchase;
        
        return $data;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
