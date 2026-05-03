<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->hasSelectedRole()) {
            // Auto-assign tourist role if user hasn't selected one yet
            $request->user()->update([
                'role' => 'tourist',
                'role_selected_at' => now(),
            ]);
        }

        return $next($request);
    }
}
