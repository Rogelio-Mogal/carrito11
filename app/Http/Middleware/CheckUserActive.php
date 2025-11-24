<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Utiliza el guard 'web' para llamar al método logout
        $user = Auth::guard('web')->user();

        if ($user && $user->activo == 0) {
            Auth::guard('web')->logout();
            return redirect()->route('login')->withErrors(['email' => 'Tu cuenta está inactiva.']);
        }
    
        return $next($request);
    }
}
