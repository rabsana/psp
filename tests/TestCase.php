<?php

namespace Rabsana\Psp\Tests;

use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as TestbenchTestCase;
use Rabsana\Psp\Providers\PspServiceProvider;

class TestCase extends TestbenchTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        Config::set(['PACKAGE_ENV' => 'testing']);
    }
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            PspServiceProvider::class
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'pspDB');
        $app['config']->set('database.connections.pspDB', [
            'driver'    => 'sqlite',
            'database'  => ':memory:'
        ]);
    }
}
