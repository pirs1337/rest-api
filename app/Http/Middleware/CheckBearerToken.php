<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class CheckBearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
            $user = new User;
            $userByToken = $user::getUserByBearerToken($request);
            if (!$userByToken || !$userByToken->api_token) {
                $response = new Controller;
                return $response->sendUnauthorize();
            }

            return $next($request);
    }
}
