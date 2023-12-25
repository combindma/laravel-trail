<?php

namespace Combindma\Trail\Middleware;

use Closure;
use Combindma\Trail\Trail;
use Illuminate\Http\Request;

class HandleUtmTagsMiddleware
{
    protected Trail $trail;

    public function __construct(Trail $trail)
    {
        $this->trail = $trail;
    }

    public function handle(Request $request, Closure $next)
    {
        $this->trail->setUtmCookies($request);

        return $next($request);
    }
}
