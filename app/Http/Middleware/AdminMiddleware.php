<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


    //  if (!Auth::check()) {
    //         return redirect('/login');
    //     }


         if (Auth::user()->role !== 'admin') {
            abort(403, 'You are not authorized to access this page.');
        }
        return $next($request);
    }
}
