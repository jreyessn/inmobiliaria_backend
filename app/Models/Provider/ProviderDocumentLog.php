<?php

namespace App\Models\Provider;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderDocumentLog extends Model
{
    protected $fillable = [
        'provider_document_id',
        'status_before',
        'status_after',
        'note',
        'approver_by_user_id',
    ];
}
