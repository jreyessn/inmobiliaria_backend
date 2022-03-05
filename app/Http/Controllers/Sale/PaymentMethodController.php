<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Repositories\Sale\PaymentMethodRepositoryEloquent;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    private $PaymentMethodRepositoryEloquent;

    function __construct(
        PaymentMethodRepositoryEloquent $PaymentMethodRepositoryEloquent
    )
    {
        $this->PaymentMethodRepositoryEloquent = $PaymentMethodRepositoryEloquent;
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

        return $this->PaymentMethodRepositoryEloquent->paginate($perPage);
    }
}
