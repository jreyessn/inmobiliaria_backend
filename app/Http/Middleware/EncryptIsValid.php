<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EncryptIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            
            Crypt::decrypt($request->id);

            return $next($request);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'ID cifrada es invalida'
            ], 403);
        }

    }
}
