<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Services\CategoriesServiceRepositoryEloquent;

class CategoriesServicesController extends Controller
{

    private $CategoriesServiceRepositoryEloquent;

    function __construct(
        CategoriesServiceRepositoryEloquent $CategoriesServiceRepositoryEloquent
    )
    {
        $this->CategoriesServiceRepositoryEloquent = $CategoriesServiceRepositoryEloquent;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'perPage'       =>  'nullable|integer',
            'page'          =>  'nullable|integer',
            'search'        =>  'nullable|string',
            'orderBy'       =>  'nullable|string',
            'sortBy'        =>  'nullable|in:desc,asc',
        ]);
        
        $perPage = $request->get('perPage', config('repository.pagination.limit'));

        return $this->CategoriesServiceRepositoryEloquent->paginate($perPage);
    }



}
