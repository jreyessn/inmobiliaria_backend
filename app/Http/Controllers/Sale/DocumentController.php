<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Repositories\Sale\DocumentRepositoryEloquent;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    private $DocumentRepositoryEloquent;

    function __construct(
        DocumentRepositoryEloquent $DocumentRepositoryEloquent
    )
    {
        $this->DocumentRepositoryEloquent = $DocumentRepositoryEloquent;
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

        return $this->DocumentRepositoryEloquent->paginate($perPage);
    }
       
}
