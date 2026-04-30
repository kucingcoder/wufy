<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            \App\Models\Visitor::firstOrCreate(
                [
                    'ip_address' => $request->ip(),
                    'date' => now()->toDateString(),
                ],
                [
                    'user_agent' => $request->userAgent(),
                ]
            );
        } catch (\Exception $e) {
            // Ignore if error occurs (e.g., db not migrated yet)
        }

        return $next($request);
    }
}
