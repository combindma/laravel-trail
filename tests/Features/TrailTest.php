<?php

use Combindma\Trail\Trail;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('trail.prefix', 'test_');
    Config::set('trail.cookie_duration', 30);
    Config::set('trail.enabled', true);
});

it('can enabled or disabled the trail package', function () {
    $trail = new Trail();
    expect($trail->isEnabled())->toBeTrue()->and($trail->isDisabled())->toBeFalse();
    $trail->disable();
    expect($trail->isEnabled())->toBeFalse()->and($trail->isDisabled())->toBeTrue();
    $trail->enable();
    expect($trail->isEnabled())->toBeTrue()->and($trail->isDisabled())->toBeFalse();
});

it('has correct configuration values', function () {
    $trail = new Trail();
    expect($trail->prefix)->toBe('test_')
        ->and($trail->cookieDuration)->toBe(30)
        ->and($trail->enabled)->toBeTrue()
        ->and($trail->utmSource)->toBe('test_utm_source')
        ->and($trail->utmMedium)->toBe('test_utm_medium')
        ->and($trail->utmCampaign)->toBe('test_utm_campaign');
});
