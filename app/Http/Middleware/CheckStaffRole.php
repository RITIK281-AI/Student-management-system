<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStaffRole
{
    /**
     * Handle an incoming request.
     * Only staff can perform CRUD operations.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'staff') {
            return redirect()->route('dashboard')->with('error', 'Only staff can perform this action');
        }

        return $next($request);
    }
}
