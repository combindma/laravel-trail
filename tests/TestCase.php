<?php

namespace Combindma\Trail\Tests;

use Combindma\Trail\TrailServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        /*Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Combindma\\Trail\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );*/
    }

    protected function getPackageProviders($app)
    {
        return [
            TrailServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        config()->set('app.locale', 'fr');
        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-trail_table.php.stub';
        $migration->up();
        */
    }
}
