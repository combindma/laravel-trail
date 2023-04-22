<?php

namespace Combindma\Trail;

use Illuminate\Support\Traits\Macroable;

class Trail
{
    use Macroable;

    public readonly string $prefix;

    public readonly int $cookieDuration;

    public bool $enabled;

    public readonly string $utmSource;

    public readonly string $utmMedium;

    public readonly string $utmCampaign;

    public readonly string $utmTerm;

    public readonly string $utmContent;

    public readonly string $referrer;

    public readonly string $referrerCode;

    public readonly string $landingPage;

    private string $exitPage;

    private int $sessionDuration;

    private int $pageViews;

    private string $device;

    private string $browser;

    private string $operatingSystem;

    private string $screenResolution;

    private string $ipAddress;

    private DateTime $conversionDate;

    private DateTime $lastActivity;

    private string $userId;

    private string $userAgent;

    private string $language;

    public function __construct()
    {
        $this->prefix = config('trail.prefix');
        $this->cookieDuration = config('trail.cookie_duration');
        $this->enabled = config('trail.enabled');
        $this->utmSource = $this->prefix.'utm_source';
        $this->utmMedium = $this->prefix.'utm_medium';
        $this->utmCampaign = $this->prefix.'utm_campaign';
        $this->utmTerm = $this->prefix.'utm_term';
        $this->utmContent = $this->prefix.'utm_content';
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
}
