<?php

namespace App\Models\Provider;

use App\Observers\ProviderDocumentLogObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProviderDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_id',
        'provider_id',
        'name',
        'date',
        'approved',
        'note',
        'user_approver_id'
    ];

    protected $with = ['document'];

    protected $appends = ['approved_text'];

    private $status = ['En espera', 'Aprobado', 'Rechazado'];

    public function getApprovedTextAttribute(){
        return $this->status[$this->approved];
    }
    
    public function document(){
        return $this->belongsTo('App\Models\Document');
    }

    public function provider(){
        return $this->belongsTo('App\Models\Provider\Provider');
    }

    protected static function boot(){
        parent::boot();

        ProviderDocument::observe(ProviderDocumentLogObserver::class);
    }

}
