<?php

namespace Rabsana\Psp\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Rabsana\Psp\Http\Middleware\CheckMerchantTokenMiddleware;
use Rabsana\Psp\Console\Commands\ExpireInvoiceCommand;

class PspServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->registerPublishes();
        $this->registerResources();
        $this->registerRouteMiddlewares();
    }

    public function register()
    {
        $this->loadCommands();
    }

    protected function loadCommands()
    {

        $this->registerSingletons()
            ->registerBinds();

        $this->commands([
            ExpireInvoiceCommand::class
        ]);
    }

    protected function registerPublishes(): PspServiceProvider
    {
        $this->publishConfigs()
            ->publishMigrations()
            ->publishAssets()
            ->publishLangs()
            ->publishViews()
            ->publishAll();

        return $this;
    }

    protected function publishConfigs(): PspServiceProvider
    {
        $this->publishes([
            __DIR__ . "/../../config/rabsana-psp.php" => config_path('rabsana-psp.php')
        ], 'rabsana-psp-config');

        return $this;
    }

    protected function publishMigrations(): PspServiceProvider
    {
        $this->publishes([
            __DIR__ . "/../../database/migrations/2021_12_08_110431_create_rabsana_merchants_table.php"                                             => database_path('migrations/2021_12_08_110431_create_rabsana_merchants_table.php'),
            __DIR__ . "/../../database/migrations/2021_12_10_110431_create_rabsana_currency_merchant_pivot_table.php"                               => database_path('migrations/2021_12_10_110431_create_rabsana_currency_merchant_pivot_table.php'),
            __DIR__ . "/../../database/migrations/2021_12_20_110431_create_rabsana_invoices_table.php"                                              => database_path('migrations/2021_12_20_110431_create_rabsana_invoices_table.php'),
        ], 'rabsana-psp-migrations');

        return $this;
    }

    protected function publishAssets(): PspServiceProvider
    {
        // $this->publishes([
        //     __DIR__ . "/../../assets/" => public_path('vendor/rabsana/psp')
        // ], 'rabsana-psp-assets');

        return $this;
    }

    protected function publishLangs(): PspServiceProvider
    {
        $this->publishes([
            __DIR__ . "/../../resources/lang" => resource_path("lang/psp")
        ], 'rabsana-psp-langs');

        return $this;
    }

    protected function publishViews(): PspServiceProvider
    {
        $this->publishes([
            __DIR__ . "/../../resources/views" => resource_path("views/vendor/rabsana-psp")
        ], 'rabsana-psp-views');

        return $this;
    }

    protected function publishAll(): PspServiceProvider
    {
        $this->publishes(self::$publishes[PspServiceProvider::class], 'rabsana-psp-publish-all');

        return $this;
    }

    protected function registerResources(): PspServiceProvider
    {
        $this->registerMigrations()
            ->registerTranslations()
            ->registerViews()
            ->registerApiRoutes()
            ->registerAdminApiRoutes()
            ->registerWebRoutes();


        return $this;
    }

    protected function registerRouteMiddlewares(): PspServiceProvider
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('checkMerchantToken', CheckMerchantTokenMiddleware::class);

        return $this;
    }

    protected function registerSingletons(): PspServiceProvider
    {
        // $this->app->singleton(Psp::class, function ($app) {
        //     return new PspClass();
        // });

        return $this;
    }

    protected function registerBinds(): PspServiceProvider
    {
        // $this->app->bind(Psp::class, function ($app) {
        //     return new PspClass();
        // });

        return $this;
    }

    protected function registerMigrations(): PspServiceProvider
    {
        $this->loadMigrationsFrom(__DIR__ . "/../../database/migrations");
        return $this;
    }

    protected function registerTranslations(): PspServiceProvider
    {
        $this->loadTranslationsFrom(__DIR__ . "/../../resources/lang", 'rabsana-psp');
        return $this;
    }

    protected function registerViews(): PspServiceProvider
    {
        $this->loadViewsFrom(__DIR__ . "/../../resources/views", 'rabsana-psp');
        return $this;
    }

    protected function registerApiRoutes(): PspServiceProvider
    {
        Route::group($this->apiRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . "/../../routes/api.php");
        });
        return $this;
    }

    protected function registerAdminApiRoutes(): PspServiceProvider
    {
        Route::group($this->adminApiRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . "/../../routes/admin-api.php");
        });
        return $this;
    }

    protected function registerWebRoutes(): PspServiceProvider
    {
        Route::group($this->webRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . "/../../routes/web.php");
        });

        return $this;
    }

    protected function apiRouteConfiguration(): array
    {
        return [
            'domain'        => config('rabsana-psp.domain', null),
            'namespace'     => NULL,
            'prefix'        => config('rabsana-psp.path', 'rabsana-psp'),
            'as'            => 'rabsana-psp.',
            'middleware'    => config('rabsana-psp.apiMiddlewares.group', 'api'),
        ];
    }

    protected function adminApiRouteConfiguration(): array
    {
        return [
            'domain'        => config('rabsana-psp.domain', null),
            'namespace'     => NULL,
            'prefix'        => config('rabsana-psp.path', 'rabsana-psp'),
            'as'            => 'rabsana-psp.',
            'middleware'    =>  config('rabsana-psp.adminApiMiddlewares.group', 'web'),
        ];
    }

    protected function webRouteConfiguration(): array
    {
        return [
            'domain'        => config('rabsana-psp.domain', null),
            'namespace'     => NULL,
            'prefix'        => config('rabsana-psp.path', 'rabsana-psp'),
            'as'            => 'rabsana-psp.',
            'middleware'    =>  config('rabsana-psp.webMiddlewares.group', 'web'),
        ];
    }
}
