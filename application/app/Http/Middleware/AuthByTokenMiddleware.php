<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthByTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasHeader('x-token')) {
            throw new AccessDeniedHttpException('Access denied. Please, provide an x-token');
        }

        $token = Token::where('token', $request->header('x-token'))->first();
        if ($token) {
            Auth::loginUsingId($token->id);

            return $next($request);
        } else {
            throw new AccessDeniedHttpException('x-token invalid. Please check your token or generate a new one');
        }
    }
}
