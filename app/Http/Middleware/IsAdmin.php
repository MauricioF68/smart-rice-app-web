<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Revisa si el usuario ha iniciado sesión Y si su rol es 'admin'
        if (auth()->check() && auth()->user()->rol === 'admin') {
            // Si cumple, déjalo pasar a la página solicitada
            return $next($request);
        }

        // Si no es admin, redirígelo al dashboard con un mensaje de error.
        return redirect('/dashboard')->with('status', 'Error: No tienes permiso para acceder a esta sección.');
    }
}
