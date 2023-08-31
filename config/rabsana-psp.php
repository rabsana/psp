<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rabsana-psp Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where Rabsana-psp will be accessible from. If the
    | setting is null, Rabsana-psp will reside under the same domain as the
    | application. Otherwise, this value will be used as the subdomain.
    |
    */

    'domain' => env('RABSANA_PSP_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | Rabsana-psp Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Rabsana-psp will be accessible from. Feel free
    | to change this path to anything you like. Note that the URI will not
    | affect the paths of its internal API that aren't exposed to users.
    |
    */

    'path' => env('RABSANA_PSP_PATH', 'rabsana-psp'),

    /*
    |--------------------------------------------------------------------------
    | Rabsana-psp Admin Api middleware
    |--------------------------------------------------------------------------
    |
    | Here you can add the middlewares for public and private routes.
    | for example you can set the auth:api middleware to private routes to check
    | the user is authenticated or not
    */

    'adminApiMiddlewares' => [
        'group'  => 'web', // web or api
        'public' => [],
        'private' => [],
        'merchant' => [],
        'invoice'  => []
    ],

    /*
    |--------------------------------------------------------------------------
    | Rabsana-psp  Api middleware
    |--------------------------------------------------------------------------
    |
    | Here you can add the middlewares for public and private routes.
    | for example you can set the auth:api middleware to private routes to check
    | the user is authenticated or not
    */

    'apiMiddlewares' => [
        'group' => 'api',  // web or api
        'public' => [],
        'private' => []
    ],

    /*
    |--------------------------------------------------------------------------
    | Rabsana-psp  web middleware
    |--------------------------------------------------------------------------
    |
    | Here you can add the middlewares for public and private routes.
    | for example you can set the web middleware to private routes to check
    | the user is authenticated or not
    */

    'webMiddlewares' => [
        'group' => 'web',  // web or api
        'public' => [],
        'private' => []
    ],

    /*
    |--------------------------------------------------------------------------
    | Rabsana-psp  views config
    |--------------------------------------------------------------------------
    |
    | for showing the views you can determine these configs or you can publish
    | the package views
    |
    */

    'views' => [
        'admin' => [
            'extends'           => 'rabsana-psp::admin.master',
            'content-section'   => 'content',
            'title-section'     => 'title',
            'scripts-stack'     => 'scripts',
            'styles-stack'      => 'styles'
        ],
        'web' => [
            'extends'           => 'rabsana-psp::web.master',
            'content-section'   => 'content',
            'title-section'     => 'title',
            'scripts-stack'     => 'scripts',
            'styles-stack'      => 'styles'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rabsana-psp  database config
    |--------------------------------------------------------------------------
    |
    | here you can change some names and configs for database migrations
    |
    */

    'database' => [
        'merchants'         => [
            'table' => 'merchants', // table name
        ],
        'currency_merchant' => [
            'table' => 'currency_merchant', // table name
        ],
        'invoices' => [
            'table' => 'invoices', // table name
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Rabsana-psp  model relations config
    |--------------------------------------------------------------------------
    |
    | here you can define the models directory
    |
    | define the models here
    */

    'modelRelations' => [
        'user'          => 'App\\Models\\User',
        'currency'      => 'App\\Models\\Currency',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rabsana-psp  Invoice lifetime
    |--------------------------------------------------------------------------
    |
    |
    */
    'invoiceLifeTime' => 10,


    /*
    |--------------------------------------------------------------------------
    | Rabsana-psp  tasks
    |--------------------------------------------------------------------------
    | you can provide some data for the some parts of package
    | 
    |
    |
    */

    /**
     * Get all users with task
     *
     * @method run()
     */
    'getAllUsersTask'       => '',

    /**
     * Get all currencies with task
     *
     * @method run()
     */
    'getAllCurrenciesTask'  => '',

    /**
     * get user info with username, password
     *
     * @method run()
     */
    'getUserInfoWithUsernameAndPasswordTask'  => '',

    /**
     * get user balance with currency id
     *
     * @method run()
     */
    'getUserBalanceWithCurrencyIdTask'  => '',

    /**
     * define a task that pay the invoice
     *
     * @method run()
     */
    'payInvoiceTask'  => '',




];
