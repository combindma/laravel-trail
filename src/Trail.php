<?php

namespace Combindma\Trail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Traits\Macroable;

class Trail
{
    use Macroable;

    public readonly string $prefix;

    public readonly int $cookieDuration;

    public bool $enabled;

    public function __construct()
    {
        $this->prefix = config('trail.prefix');
        $this->cookieDuration = config('trail.cookie_duration');
        $this->enabled = config('trail.enabled');
    }

    public function setTrailCookie(string $name, mixed $value): void
    {
        Cookie::queue(Cookie::make($this->prefix.$name, $value, $this->cookieDuration));
    }

    public function getTrailCookie(Request $request, string $name): array|string|null
    {
        return $request->cookie($this->prefix.$name);
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function isDisabled(): bool
    {
        return ! $this->enabled;
    }

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function init(Request $request): void
    {
        if ($this->isDisabled()) {
            return;
        }
        $this->setTrailCookie('exit_page', $request->url());
        $this->setTrailCookie('last_activity', now());
        $this->setTrailCookie('user_agent', $request->userAgent());
        $this->setTrailCookie('ip_address', $request->ip());
        $this->setTrailCookie('language', substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2));

        if (empty($request->cookie('landing_page'))) {
            $this->setTrailCookie('landing_page', $request->url());
        }
    }

    public function setUtmCookies(Request $request): void
    {
        if ($this->isDisabled()) {
            return;
        }

        $utmParameters = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];

        foreach ($utmParameters as $param) {
            if ($request->has($param)) {
                $this->setTrailCookie($param, $request->input($param));
            }
        }
    }

    public function setReferrerCookies(Request $request): void
    {
        if ($this->isDisabled()) {
            return;
        }

        $utmParameters = ['referrer', 'referrer_code'];

        foreach ($utmParameters as $param) {
            if ($request->has($param)) {
                $this->setTrailCookie($param, $request->input($param));
            }
        }
    }
}
