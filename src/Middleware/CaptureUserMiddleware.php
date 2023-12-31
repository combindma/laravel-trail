<?php

namespace Combindma\Trail\Middleware;

use Closure;
use Combindma\Trail\Trail;
use Illuminate\Http\Request;

class CaptureUserMiddleware
{
    protected Trail $trail;

    public function __construct(Trail $trail)
    {
        $this->trail = $trail;
    }

    public function handle(Request $request, Closure $next)
    {
        $this->trail->setUserCookie($request);

        return $next($request);
    }
}
