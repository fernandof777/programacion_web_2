<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitRequests
{
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decaySeconds = 60): Response
    {
        $identity = $request->user()?->getAuthIdentifier() ?? $request->ip();
        $route = $request->route()?->getName() ?? $request->path();
        $key = "http:{$maxAttempts}:{$route}:{$identity}";

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            return response('Demasiadas solicitudes. Inténtalo nuevamente más tarde.', 429)
                ->header('Retry-After', (string) $retryAfter);
        }

        RateLimiter::hit($key, $decaySeconds);
        $response = $next($request);
        $response->headers->set('X-RateLimit-Limit', (string) $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', (string) RateLimiter::remaining($key, $maxAttempts));

        return $response;
    }
}
