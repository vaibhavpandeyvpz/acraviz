<?php

namespace Acraviz\Http\Providers;

use Acraviz\Http\Controllers\ApplicationsController;
use Acraviz\Http\Controllers\ApiController;
use Acraviz\Http\Controllers\DashboardController;
use Acraviz\Http\Controllers\DatatablesController;
use Acraviz\Http\Controllers\DeleteController;
use Acraviz\Http\Controllers\HideController;
use Acraviz\Http\Controllers\LoginController;
use Acraviz\Http\Controllers\ReportsController;
use Acraviz\Http\Controllers\SearchController;
use Acraviz\Http\Controllers\SettingsController;
use Silex\Application;
use Silex\ServiceProviderInterface;

class ControllersServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app['ApiController'] = $app->share(function () use ($app)
        {
            return new ApiController($app);
        });
        $app['ApplicationsController'] = $app->share(function () use ($app)
        {
            return new ApplicationsController($app);
        });
        $app['DashboardController'] = $app->share(function () use ($app)
        {
            return new DashboardController($app);
        });
        $app['DatatablesController'] = $app->share(function () use ($app)
        {
            return new DatatablesController($app);
        });
        $app['DeleteController'] = $app->share(function () use ($app)
        {
            return new DeleteController($app);
        });
        $app['HideController'] = $app->share(function () use ($app)
        {
            return new HideController($app);
        });
        $app['LoginController'] = $app->share(function () use ($app)
        {
            return new LoginController($app);
        });
        $app['ReportsController'] = $app->share(function () use ($app)
        {
            return new ReportsController($app);
        });
        $app['SearchController'] = $app->share(function () use ($app)
        {
            return new SearchController($app);
        });
        $app['SettingsController'] = $app->share(function () use ($app)
        {
            return new SettingsController($app);
        });
    }

    /**
     * @inheritdoc
     */
    public function boot(Application $app)
    {
    }
}
