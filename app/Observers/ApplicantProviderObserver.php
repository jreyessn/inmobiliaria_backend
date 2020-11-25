<?php

namespace App\Observers;

use App\Models\ApplicantProvider;
use App\Models\Provider\AuditStatusProvider;

class ApplicantProviderObserver
{
    /**
     * Handle the provider document "updated" event.
     *
     * @param  \App\Models\ApplicantProvider  $applicantProvider
     * @return void
     */
    public function updated(ApplicantProvider $applicantProvider)
    {
    
        if($applicantProvider->status != $applicantProvider->getOriginal('status'))
            AuditStatusProvider::create([
                'model_id' => $applicantProvider->id,
                'model_type' => ApplicantProvider::class,
                'action' => 'Aprobación/Rechazo de Alta',
                'status_before' => $applicantProvider->getOriginal('status'),
                'status_after' => $applicantProvider->status,
                'note' => $applicantProvider->note,
                'user_id' => request()->user()->id
            ]);
    }
}
