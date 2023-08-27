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
            ($currentUserRole == 'admin' || $currentUserRole == 'co_admin') &&
            $user_type != 'organizer'
        ) {
            return $next($request);
        } elseif (
            $currentUserRole == 'organizer' &&
            ($user_type == 'organizer' || $user_type == 'attendee')
        ) {
            return $next($request);
        } elseif (
            $currentUserRole == 'attendee' &&
            $user_type == 'attendee'
        ) {
            return $next($request);
        }

        abort(401);
    }
}
