<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /*public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::guest()) {
            return redirect()->guest('login');
        }

        return $next($request);
    }*/

     public function handle($request, Closure $next, ...$guards)
    {
    if (Auth::check()) {
        return redirect('/dashboard'); // Asegúrate que sea la misma ruta
    }
    return $next($request);
    }
}