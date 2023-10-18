<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Api-Token');

        if ($token !== env('PYTHON_BOT_API_TOKEN')) {
            return response()->json([
                'message' => 'Invalid api token',
            ], 403);
        }

        return $next($request);
    }
}
