<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogApiRequest
{
    public function handle(Request $request, Closure $next)
    {
        // Before processing the request
        $startTime = microtime(true);

        $response = $next($request);

        // After processing the request
        $endTime = microtime(true);

        $duration = $endTime - $startTime; // Duration in seconds
        $statusCode = $response->status();
        $method = $request->getMethod();
        $uri = $request->getRequestUri();
        $ip = $request->ip();
        $logMessage = "[$statusCode] $method $uri | IP: $ip | Duração: {$duration} segundos";

        // Register the log message
        Log::channel('daily')->info($logMessage);

        return $response;
    }
}
