<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    /**
     * Ensure a named feature flag from config/mentor.php is enabled.
     *
     * Usage: middleware('feature:weekly_plans')
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (! config("mentor.features.{$feature}")) {
            abort(404);
        }

        return $next($request);
    }
}
