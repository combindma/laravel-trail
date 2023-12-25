<?php

use Combindma\Trail\Middleware\HandleUtmTagsMiddleware;
use Combindma\Trail\Middleware\TrailSetupMiddleware;
use Combindma\Trail\Trail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('trail.prefix', 'app_');
    Config::set('trail.cookie_duration', 30);
    Config::set('trail.enabled', true);
});

it('calls init method of Trail service', function () {
    $trailMock = Mockery::mock(Trail::class);
    $this->app->instance(Trail::class, $trailMock);
    $trailMock->shouldReceive('init')
        ->once()
        ->with(Mockery::type(Request::class));

    $request = Request::create('/test', 'GET');
    $middleware = new TrailSetupMiddleware($trailMock);
    $middleware->handle($request, function ($req) {
        return $req;
    });
    $trailMock->shouldHaveReceived('init');
});

it('calls setUtmCookies method of Trail service', function () {
    $trailMock = Mockery::mock(Trail::class);
    $this->app->instance(Trail::class, $trailMock);
    $trailMock->shouldReceive('setUtmCookies')
        ->once()
        ->with(Mockery::type(Request::class));

    $request = Request::create('/test', 'GET');
    $middleware = new HandleUtmTagsMiddleware($trailMock);
    $middleware->handle($request, function ($req) {
        return $req;
    });
    $trailMock->shouldHaveReceived('setUtmCookies');
});

it('initializes trail and sets cookies correctly using middleware', function () {
    $response = $this->get('/test-trail-init');
    $responseCookies = $response->headers->getCookies();
    $cookieNames = collect($responseCookies)->map(fn ($cookie) => $cookie->getName());
    $expectedCookies = [
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

it('sets UTM cookies correctly using middleware', function () {
    $utmParameters = [
        'utm_source' => 'google',
        'utm_medium' => 'cpc',
        'utm_campaign' => 'spring_sale',
    ];
    $response = $this->get('/test-trail-utm?utm_source=google&utm_medium=cpc&utm_campaign=spring_sale');
    $expectedCookies = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
    ];
    foreach ($expectedCookies as $expectedCookie) {
        $response->assertCookie('app_'.$expectedCookie, $utmParameters[$expectedCookie]);
    }
});

it('sets referrer cookies correctly using middleware', function () {
    $referrerParameters = [
        'referrer' => 'external_site',
        'referrer_code' => '12345',
    ];
    $response = $this->get('/test-trail-referrer?referrer=external_site&referrer_code=12345');
    $expectedCookies = [
        'referrer',
        'referrer_code',
    ];
    foreach ($expectedCookies as $expectedCookie) {
        $response->assertCookie('app_'.$expectedCookie, $referrerParameters[$expectedCookie]);
    }
});
