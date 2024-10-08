<?php

namespace Combindma\Trail;

use Combindma\Trail\DataTransferObjects\TrailDto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
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
        if ($this->isDisabled()) {
            return;
        }
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
        $this->setTrailCookie('language', substr($request->server('HTTP_ACCEPT_LANGUAGE') ?? 'fr', 0, 2));

        if (empty($request->cookie($this->prefix.'landing_page'))) {
            $this->setTrailCookie('landing_page', $request->url());
        }

        if (empty($request->cookie($this->prefix.'anonymous_id'))) {
            $anonymousId = Str::uuid()->toString();
            $this->setTrailCookie('anonymous_id', $anonymousId);
        }
    }

    public function getAnonymousId(Request $request): string
    {
        if ($this->isDisabled()) {
            return Str::uuid()->toString();
        }

        $anonymousId = $request->cookie($this->prefix.'anonymous_id');

        if (empty($anonymousId)) {
            $anonymousId = Str::uuid()->toString();
            $this->setTrailCookie('anonymous_id', $anonymousId);
        }

        return $anonymousId;
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

    public function setUserCookie(Request $request): void
    {
        if ($this->isDisabled()) {
            return;
        }

        if ($request->has('user_id')) {
            $this->setTrailCookie('user_id', $request->input('user_id'));
        }

        if ($request->has('email')) {
            $this->setTrailCookie('email', $request->input('email'));
        }
    }

    public function identify(string $userId, ?string $email = null, ?string $name = null): void
    {
        if ($this->isDisabled()) {
            return;
        }
        $this->setTrailCookie('user_id', $userId);

        if ($email !== null) {
            $this->setTrailCookie('email', $email);
        }

        if ($name !== null) {
            $this->setTrailCookie('name', $name);
        }
    }

    public function data(?Request $request = null): TrailDto
    {
        if ($request === null) {
            $request = request();
        }

        return new TrailDto(
            $this->getAnonymousId($request),
            $request->cookie($this->prefix.'user_id'),
            $request->cookie($this->prefix.'email'),
            $request->cookie($this->prefix.'name'),
            $request->cookie($this->prefix.'landing_page'),
            $request->cookie($this->prefix.'exit_page'),
            $request->cookie($this->prefix.'last_activity'),
            $request->cookie($this->prefix.'ip_address'),
            $request->cookie($this->prefix.'language'),
            $request->cookie($this->prefix.'user_agent'),
            $request->cookie($this->prefix.'referrer'),
            $request->cookie($this->prefix.'referrer_code'),
            $request->cookie($this->prefix.'utm_source'),
            $request->cookie($this->prefix.'utm_medium'),
            $request->cookie($this->prefix.'utm_campaign'),
            $request->cookie($this->prefix.'utm_term'),
            $request->cookie($this->prefix.'utm_content'),
        );
    }
}
