<?php

namespace App\Http\Controllers\Furniture;

use App\Http\Controllers\Controller;
use App\Repositories\Furniture\MeasureUnitRepositoryEloquent;
use Illuminate\Http\Request;

class MeasureUnitController extends Controller
{
    private $MeasureUnitRepositoryEloquent;

    function __construct(
        MeasureUnitRepositoryEloquent $MeasureUnitRepositoryEloquent
    )
    {
        $this->MeasureUnitRepositoryEloquent = $MeasureUnitRepositoryEloquent;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'perPage'       =>  'nullable|integer',
            'page'          =>  'nullable|integer',
            'search'        =>  'nullable|string',
            'orderBy'       =>  'nullable|string',
            'sortBy'        =>  'nullable|in:desc,asc',
        ]);
        
        $perPage = $request->get('perPage', config('repository.pagination.limit'));

        return $this->MeasureUnitRepositoryEloquent->paginate($perPage);
    }
}
