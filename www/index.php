<?php

require_once __DIR__ . '/../vendor/autoload.php';

(new Dotenv\Dotenv(__DIR__ . '/../'))->load();

date_default_timezone_set(getenv('APP_TIMEZONE'));

use Acraviz\Http\HttpApplication;
use Acraviz\Http\Providers\BootstrapServiceProvider;
use Acraviz\Http\Providers\ControllersServiceProvider;
use Acraviz\Http\Providers\RoutesServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\RememberMeServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

$app = new HttpApplication();

$app['debug'] = getenv('SECURITY_DEBUG') == 'true';

$app['dir.root'] = realpath(__DIR__ . '/../');

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../storage/logs/app.log'
));

$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'charset' => getenv('DB_CHARSET'),
        'dbname' => getenv('DB_NAME'),
        'driver' => getenv('DB_DRIVER'),
        'host' => getenv('DB_HOST'),
        'password' => getenv('DB_PASSWORD'),
        'port' => getenv('DB_PORT'),
        'user' => getenv('DB_USER')
    )
));

$app->register(new FormServiceProvider());

$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'login' => array(
            'anonymous' => true,
            'pattern' => '^/(api(/[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12})?|login)$',
        ),
        'admin' => array(
            'form' => array(
                'check_path' => '/login/check',
                'login_path' => '/login'
            ),
            'logout' => array(
                'logout_path' => '/logout',
                'target_url' => '/login?logout=true'
            ),
            'remember_me' => array('key' => getenv('APP_KEY')),
            'users' => $app->share(function () use ($app)
            {
                return new Acraviz\Http\Security\UserProvider($app);
            })
        )
    )
));

$app->register(new RememberMeServiceProvider());

$app->register(new ServiceControllerServiceProvider());

$app->register(new SessionServiceProvider());

$app->register(new TranslationServiceProvider(), array(
    'locale' => 'en',
    'locale_all' => array('en'),
    'locale_fallbacks' => array('en')
));

$app->register(new TwigServiceProvider(), array(
    'twig.options' => array(
        'cache' => __DIR__ . '/../storage/twig'
    ),
    'twig.path' => __DIR__ . '/../assets/twig'
));

$app->register(new UrlGeneratorServiceProvider());

$app->register(new ValidatorServiceProvider());

$app->register(new BootstrapServiceProvider());

$app->register(new ControllersServiceProvider());

$app->register(new RoutesServiceProvider());

$app->run();
