<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role = null): Response
    {
        Log::debug('Authenticated : ' . Auth::check());
        Log::debug('Request : ' . $request->getUri());
        Log::debug('ID de session : ' . session()->getId());
        Log::debug('Username : ' . Auth::user());
        Log::debug('Detected requested role : ' . $role);
        // Vérifie si l'utilisateur est connecté
        if (Auth::check()) {
            Log::debug('User Authenticated.');
            $userRole = Auth::user()->role;
            Log::debug('User Role: ' . $userRole);
            if ($userRole && $userRole->name === $role) {
                Log::debug('Matching role!!');
                return $next($request);
            } else {
                if ($role){
                    // Protected path
                    return redirect('/')->with('error', 'Access denied');
                }
            }
        }

        // Continue vers la page d'accueil
        return $next($request);
    }
}
