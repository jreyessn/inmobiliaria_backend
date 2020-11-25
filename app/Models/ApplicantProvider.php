<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\ApplicantProviderObserver;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicantProvider extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reason',
        'type_provider',
        "tradename",
        "business_name",
        "name_contact",
        "phone_provider",
        "email_provider",
        "fullname_applicant",
        "email_applicant",
        "microbusiness",
        "authorization_file",
        'user_id',
        "status",
        "note",
        "approved_at",
        'approver_by_user_id',
    ];

    protected $appends = ['status_text'];

    private $texts = ['En espera', 'Aprobado', 'Rechazado'];

    public function getStatusTextAttribute(){
        $status = $this->status ?? 0;

        return $this->texts[$status];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_approver(){
        return $this->belongsTo('App\Models\User', 'approver_by_user_id');
    }

    protected static function boot(){
        parent::boot();

        ApplicantProvider::observe(ApplicantProviderObserver::class);
    }
}
