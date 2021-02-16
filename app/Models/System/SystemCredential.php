<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SystemCredential extends Model
{
    use HasFactory;

    protected $table = 'systems_credentials';

    protected $fillable = [
        'description',
        'server',
        'username',
        'password',
        'system_id',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Crypt::encryptString($password);
    }
    
    public function getPasswordAttribute($password)
    {
        return Crypt::decryptString($password);
    }


}
