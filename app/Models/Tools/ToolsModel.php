<?php

namespace App\Models\Tools;

use App\Models\Area\Area;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolsModel extends Model
{
    use SoftDeletes;

    protected $table = "tools_model";

    public $timestamps = false;

    protected $fillable = [
        "quantity",
        "tool_id",
        "model_type",
        "model_id",
    ];

    protected $appends = [
        "entity"
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function getEntityAttribute()
    {
        switch ($this->model_type) {
            case Area::class:
                return "area";
            break;
            
            default:
                return "user";
            break;
        }
    }

    // Partenaire and Enseignant models:
    public function relation()
    {
        return $this->model();
    }

    public function tool(){
        return $this->belongsTo(Tool::class);
    }

}
