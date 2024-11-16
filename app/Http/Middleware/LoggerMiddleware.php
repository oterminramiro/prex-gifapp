<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $contents = json_decode($response->getContent(), true, 512);
        
        $dt = new Carbon();
        $data = [
            'path'         => $request->getPathInfo(),
            'method'       => $request->getMethod(),
            'ip'           => $request->ip(),
            'timestamp'    => $dt->toDateTimeString(),
        ];

        if ($request->user()) {
            $data['user_id'] = $request->user()->id;
        }

        $data['request'] = $request->all();
        $data['response'] = $contents;

        $message     = str_replace('/', '_', trim($request->getPathInfo(), '/'));

        Log::info($message, $data);

        return $response;
    }
}