<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has the required role
        if (Auth::user()->role !== $role) {
            // If user is admin trying to access user routes, redirect to admin dashboard
            if (Auth::user()->role === 'admin' && $role === 'user') {
                return redirect()->route('admin.dashboard');
            }

            // If user is regular user trying to access admin routes, redirect to home
            if (Auth::user()->role === 'user' && $role === 'admin') {
                return redirect()->route('home')->with('error', 'Access denied. Admin privileges required.');
            }

            // For any other role mismatch, redirect to appropriate dashboard
            return redirect()->route('home')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}
