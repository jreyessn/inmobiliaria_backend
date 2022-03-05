<?php

namespace App\Http\Controllers\Country;

use App\Http\Controllers\Controller;
use App\Repositories\Country\CountryRepositoryEloquent;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    private $CountryRepositoryEloquent;

    function __construct(
        CountryRepositoryEloquent $CountryRepositoryEloquent
    )
    {
        $this->CountryRepositoryEloquent = $CountryRepositoryEloquent;
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

        return $this->CountryRepositoryEloquent->paginate($perPage);
    }
}
