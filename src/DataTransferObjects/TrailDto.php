<?php

namespace Combindma\Trail\DataTransferObjects;

readonly class TrailDto
{
    public function __construct(
        public string $anonymousId,
        public ?string $userId,
        public ?string $email,
        public ?string $name,
        public ?string $landingPage,
        public ?string $exitPage,
        public ?string $lastActivity,
        public ?string $ipAddress,
        public ?string $language,
        public ?string $userAgent,
        public ?string $referrer,
        public ?string $referrerCode,
        public ?string $utmSource,
        public ?string $utmMedium,
        public ?string $utmCampaign,
        public ?string $utmTerm,
        public ?string $utmContent,
    ) {}
}
