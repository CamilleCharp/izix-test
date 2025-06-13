<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasSimulatorApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $simulatorApiKey = env('SIMULATOR_API_KEY');
        $givenKey = $request->headers->get('API-KEY');

        if (empty($givenKey) || $givenKey != $simulatorApiKey) {
            return response()->json('Invalid API Key', Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
