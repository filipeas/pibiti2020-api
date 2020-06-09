<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;

class IsPatient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->is_patient == 1) {
            return $next($request);
        }

        return Response::json(array(
            'success' => false,
            'message' => 'You don\'t have patient access.',
        ));
    }
}
