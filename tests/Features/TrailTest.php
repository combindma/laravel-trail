<?php

use Combindma\Trail\Trail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

beforeEach(function () {
    Config::set('trail.prefix', 'app_');
    Config::set('trail.cookie_duration', 30);
    Config::set('trail.enabled', true);
});

it('sets the configuration values', function () {
    $trail = new Trail();
    expect($trail->prefix)->toBe('app_')
        ->and($trail->cookieDuration)->toBe(30)
        ->and($trail->enabled)->toBeTrue();
});

it('can enabled or disabled the trail package', function () {
    $trail = new Trail();
    expect($trail->isEnabled())->toBeTrue()->and($trail->isDisabled())->toBeFalse();
    $trail->disable();
    expect($trail->isEnabled())->toBeFalse()->and($trail->isDisabled())->toBeTrue();
    $trail->enable();
    expect($trail->isEnabled())->toBeTrue()->and($trail->isDisabled())->toBeFalse();
});

it('sets a trail cookie correctly', function () {
    $trail = new Trail();
    $name = 'cookie_name';
    $value = 'testValue';
    $trail->setTrailCookie($name, $value);
    $response = $this->get('/test-trail');

    $response->assertCookie($trail->prefix.$name, $value);
});

it('retrieves the correct trail cookie value', function () {
    $trail = new Trail();
    $name = 'cookie_name';
    $value = 'testValue';
    $request = Request::create('/some-route');
    $request->cookies->set($trail->prefix.$name, $value);
    $retrievedValue = $trail->getTrailCookie($request, $name);

    expect($retrievedValue)->toEqual($value);
});

it('does not set cookies when feature is disabled', function () {
    $trail = new Trail();
    $trail->disable();
    $trail->setTrailCookie('cookie_name', 'testValue');
    expect(Cookie::getQueuedCookies())->toBeEmpty();
});

it('initializes trail and sets cookies correctly', function () {
    $trail = new Trail();
    $request = Request::create('/test-url');
    $trail->init($request);
    $response = $this->get('/test-trail');
    $responseCookies = $response->headers->getCookies();
    $cookieNames = collect($responseCookies)->map(fn ($cookie) => $cookie->getName());
    $expectedCookies = [
        'app_anonymous_id',
        'app_exit_page',
        'app_last_activity',
        'app_user_agent',
        'app_ip_address',
        'app_language',
        'app_landing_page',
    ];
    foreach ($expectedCookies as $expectedCookie) {
        expect($cookieNames)->toContain($expectedCookie);
    }
});

it('sets UTM cookies correctly', function () {
    $trail = new Trail();

    $utmParameters = [
        'utm_source' => 'google',
        'utm_medium' => 'cpc',
        'utm_campaign' => 'spring_sale',
        'utm_term' => 'running+shoes',
        'utm_content' => 'ad_content',
    ];
    $request = Request::create('/test-url', 'GET', $utmParameters);
    $trail->setUtmCookies($request);
    $response = $this->get('/test-trail');

    foreach ($utmParameters as $name => $value) {
        $response->assertCookie($trail->prefix.$name, $value);
    }
});

it('sets referrer cookies correctly', function () {
    $trail = new Trail();

    $referrerParameters = [
        'referrer' => 'external_site',
        'referrer_code' => '12345',
    ];
    $request = Request::create('/test-url', 'GET', $referrerParameters);
    $trail->setReferrerCookies($request);
    $response = $this->get('/test-trail');

    foreach ($referrerParameters as $name => $value) {
        $response->assertCookie($trail->prefix.$name, $value);
    }
});

it('generates and sets a new anonymous ID if not present in cookies', function () {
    $trail = new Trail();
    $request = Request::create('/test-url');
    $anonymousId = $trail->getAnonymousId($request);
    $cookie = collect(Cookie::getQueuedCookies())->first(fn ($cookie) => $cookie->getName() === 'app_anonymous_id');

    expect($anonymousId)->not->toBeNull()
        ->and($cookie)->not->toBeNull()
        ->and($cookie->getValue())->toEqual($anonymousId);
});

it('sets cookies for user identification', function () {
    $trail = new Trail();

    $userId = 'user123';
    $email = 'user@example.com';
    $name = 'John Doe';

    $trail->identify($userId, $email, $name);

    $queuedCookies = Cookie::getQueuedCookies();
    $userIdCookie = collect($queuedCookies)->first(fn ($cookie) => $cookie->getName() === 'app_user_id');
    $emailCookie = collect($queuedCookies)->first(fn ($cookie) => $cookie->getName() === 'app_email');
    $nameCookie = collect($queuedCookies)->first(fn ($cookie) => $cookie->getName() === 'app_name');

    expect($userIdCookie)->not->toBeNull()
        ->and($userIdCookie->getValue())->toEqual($userId)
        ->and($emailCookie)->not->toBeNull()
        ->and($emailCookie->getValue())->toEqual($email)
        ->and($nameCookie)->not->toBeNull()
        ->and($nameCookie->getValue())->toEqual($name);

});

it('sets only provided identification cookies', function () {
    $userId = 'user123';
    \Combindma\Trail\Facades\Trail::identify($userId);

    $queuedCookies = Cookie::getQueuedCookies();
    $userIdCookie = collect($queuedCookies)->first(fn ($cookie) => $cookie->getName() === 'app_user_id');
    $emailCookie = collect($queuedCookies)->first(fn ($cookie) => $cookie->getName() === 'app_email');
    $nameCookie = collect($queuedCookies)->first(fn ($cookie) => $cookie->getName() === 'app_name');

    expect($userIdCookie)->not->toBeNull()
        ->and($userIdCookie->getValue())->toEqual($userId)
        ->and($emailCookie)->toBeNull()
        ->and($nameCookie)->toBeNull();

});

it('correctly constructs TrailDto with custom request data', function () {
    $cookieData = [
        'userId' => '123',
        'email' => 'user@example.com',
        'name' => 'John Doe',
        'landingPage' => '/home',
        'exitPage' => '/exit',
        'lastActivity' => 'recent',
        'ipAddress' => '127.0.0.1',
        'language' => 'en',
        'userAgent' => 'Mozilla',
        'referrer' => 'external',
        'referrerCode' => 'abcd',
        'utmSource' => 'google',
        'utmMedium' => 'cpc',
        'utmCampaign' => 'summer_sale',
        'utmTerm' => 'shoes',
        'utmContent' => 'ad_content',
    ];

    $request = Request::create('/test');
    foreach ($cookieData as $key => $value) {
        $request->cookies->set('app_'.Str::snake($key), $value);
    }
    $trailDto = \Combindma\Trail\Facades\Trail::data($request);
    foreach ($cookieData as $key => $value) {
        expect($trailDto->{$key})->toEqual($value);
    }
    expect($trailDto->anonymousId)->not->toBeNull();
});
