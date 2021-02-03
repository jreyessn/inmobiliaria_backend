<?php

namespace App\Models\Person;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{

    use SoftDeletes, HasFactory;
    
    protected $table = 'persons';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'occupation',
        'street',
        'city',
        'country',
        'postcode',
        "image"
    ];


}
