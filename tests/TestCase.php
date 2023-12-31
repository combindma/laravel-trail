<?php

namespace Combindma\Trail\Tests;

use Combindma\Trail\Middleware\CaptureReferrerMiddleware;
use Combindma\Trail\Middleware\CaptureUserMiddleware;
use Combindma\Trail\Middleware\HandleUtmTagsMiddleware;
use Combindma\Trail\Middleware\TrailSetupMiddleware;
use Combindma\Trail\TrailServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            TrailServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('app.locale', 'en');
    }

    protected function defineRoutes($router): void
    {
        Route::group(['middleware' => ['web']], function () {
            Route::get('/test-trail', function (Request $request) {
                return $request;
            });
        });

        Route::group(['middleware' => ['web', TrailSetupMiddleware::class]], function () {
            Route::get('/test-trail-init', function (Request $request) {
                return $request;
            });
        });

        Route::group(['middleware' => ['web', HandleUtmTagsMiddleware::class]], function () {
            Route::get('/test-trail-utm', function (Request $request) {
                return $request;
            });
        });

        Route::group(['middleware' => ['web', CaptureReferrerMiddleware::class]], function () {
            Route::get('/test-trail-referrer', function (Request $request) {
                return $request;
            });
        });

        Route::group(['middleware' => ['web', CaptureUserMiddleware::class]], function () {
            Route::get('/test-trail-user', function (Request $request) {
                return $request;
            });
        });
    }
}
