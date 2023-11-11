<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        // If the application is in maintenance mode
        // Show the user the maintenance mode page
        if (app()->isDownForMaintenance()) {
            return response()->view('maintenance');
        }

        return $next($request);
    }
}

class ExampleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $this->logRequest($request);

        return $next($request);
    }

    protected function logRequest($request)
    {
        $logger = new RequestLogger();
        $logger->log($request);
    }
}

class RequestLogger
{
    public function log($request)
    {
        // Get the information of the incoming request
        $ipAddress = $request->ip();
        $url = $request->fullUrl();
        $method = $request->method();
        $inputData = $request->all();

        // Logging operations are performed here
        // For example, writing $request data to a file
        $logMessage = "IP: $ipAddress, URL: $url, Method: $method, Input: " . json_encode($inputData);

        $this->writeToFile($logMessage);
    }

    protected function writeToFile($logMessage)
    {
        // For example, writing the log message to a file
        $logFilePath = storage_path('logs/request.log');
        file_put_contents($logFilePath, $logMessage . PHP_EOL, FILE_APPEND);
    }
}
