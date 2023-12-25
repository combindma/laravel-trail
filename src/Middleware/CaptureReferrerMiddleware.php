<?php

namespace Combindma\Trail\Middleware;

use Closure;
use Combindma\Trail\Trail;
use Illuminate\Http\Request;

class CaptureReferrerMiddleware
{
    protected Trail $trail;

    public function __construct(Trail $trail)
    {
        $this->trail = $trail;
    }

    public function handle(Request $request, Closure $next)
    {
        $this->trail->setReferrerCookies($request);

        return $next($request);
    }
}
