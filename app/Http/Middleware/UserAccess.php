<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $user_type): Response
    {
        $currentUserRole = auth()->user()->role;

        if (
            ($currentUserRole == 'Admin' || $currentUserRole == 'Co-Admin') &&
            $user_type != 'Organizer'
        ) {
            return $next($request);
        } elseif (
            $currentUserRole == 'Organizer' &&
            ($user_type == 'Organizer' || $user_type == 'User')
        ) {
            return $next($request);
        } elseif (
            $currentUserRole == 'User' &&
            $user_type == 'User'
        ) {
            return $next($request);
        }

        abort(401);
    }
}
