<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Admin
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
        // Check if the user is not authenticated
        if (!Auth::check()) {
            Log::warning('Unauthenticated access attempt to admin route.');
            return redirect()->route('dashboard')->with('error', 'Please log in to access admin features.');
        }

        // Check if the authenticated user is not an admin
        if (Auth::user()->usertype !== 'admin') {
            Log::warning('Unauthorized access attempt to admin route', [
                'user_id' => Auth::id(),
                'usertype' => Auth::user()->usertype,
            ]);
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Log admin access
        Log::info('Admin access granted', [
            'user_id' => Auth::id(),
            'usertype' => Auth::user()->usertype,
        ]);

        // Allow the request to proceed
        return $next($request);
    }
}
