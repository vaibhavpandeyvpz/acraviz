<?php

namespace Acraviz\Http\Providers;

use Silex\Application;
use Silex\ServiceProviderInterface;

class RoutesServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->get('/', 'DashboardController:index')
            ->bind('dashboard');

        $app->post('/api', 'ApiController:save');

        $app->get('/applications', 'ApplicationsController:index')
            ->bind('applications');

        $app->post('/applications/add', 'ApplicationsController:index')
            ->bind('applications_add');

        $app->get('/login', 'LoginController:index')
            ->bind('login');

        $app->get('/reports', 'ReportsController:index')
            ->bind('reports');

        $app->get('/reports/{id}', 'ReportsController:view')
            ->bind('reports_view');

        $app->get('/search/{query}', 'SearchController:query')
            ->bind('search');

        $app->get('/settings', 'SettingsController:index')
            ->bind('settings');

        $app->post('/settings/update', 'SettingsController:index')
            ->bind('settings_update');

        /** var $datatables \Silex\ControllerCollection */
        $datatables = $app['controllers_factory'];

        $datatables->post('/applications', 'DatatablesController:applications')
            ->bind('datatables_applications');

        $datatables->post('/reports', 'DatatablesController:reports')
            ->bind('datatables_reports');

        $app->mount('/datatables', $datatables);

        /** var $delete \Silex\ControllerCollection */
        $delete = $app['controllers_factory'];

        $delete->post('/applications', 'DeleteController:applications')
            ->bind('delete_applications');

        $delete->post('/reports', 'DeleteController:reports')
            ->bind('delete_reports');

        $app->mount('/delete', $delete);
    }

    /**
     * @inheritdoc
     */
    public function boot(Application $app)
    {
    }
}
