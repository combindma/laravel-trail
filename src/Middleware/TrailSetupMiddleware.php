<?php

namespace Combindma\Trail\Middleware;

use Closure;
use Combindma\Trail\Trail;
use Illuminate\Http\Request;

class TrailSetupMiddleware
{
    protected Trail $trail;

    public function __construct(Trail $trail)
    {
        $this->trail = $trail;
    }

    public function handle(Request $request, Closure $next)
    {
        $this->trail->init($request);

        return $next($request);
    }
}
