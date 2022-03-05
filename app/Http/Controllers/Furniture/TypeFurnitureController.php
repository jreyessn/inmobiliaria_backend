<?php

namespace App\Http\Controllers\Furniture;

use App\Http\Controllers\Controller;
use App\Repositories\Furniture\TypeFurnitureRepositoryEloquent;
use Illuminate\Http\Request;

class TypeFurnitureController extends Controller
{
    private $TypeFurnitureRepositoryEloquent;

    function __construct(
        TypeFurnitureRepositoryEloquent $TypeFurnitureRepositoryEloquent
    )
    {
        $this->TypeFurnitureRepositoryEloquent = $TypeFurnitureRepositoryEloquent;
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

        return $this->TypeFurnitureRepositoryEloquent->paginate($perPage);
    }
}
