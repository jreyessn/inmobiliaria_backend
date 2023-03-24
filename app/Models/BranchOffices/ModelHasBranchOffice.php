<?php

namespace App\Models\BranchOffices;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasBranchOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        "branch_office_id",
        "model_type",
        "model_id",
    ];

    public $timestamps = false;
}
