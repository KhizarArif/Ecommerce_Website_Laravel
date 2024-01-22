<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::check()) {
            // Check if the user is an admin
            if (auth()->user()->is_admin == 1) {
                // User is an admin, proceed to the next middleware or route
                return $next($request);
            } else {
                // User is not an admin, redirect to the login route
                return redirect()->route('admin.login')->with('error', 'You are not authorized to access this site.');
            }
        } else {
            // User is not authenticated, redirect to the login route
            return redirect()->route('admin.login');
        }
    }
}
