<?php

namespace Acraviz\Http\Providers;

use Acraviz\Support\Twig as TwigExtension;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use RandomLib\Factory as Random;
use SecurityLib\Strength as RandomStrength;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Silex\Translator;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder as Encoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Translation\Loader\PhpFileLoader;

class BootstrapServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app['random'] = $app->share(function ()
        {
            return (new Random())->getGenerator(new RandomStrength(RandomStrength::MEDIUM));
        });
        $app['security.encoder_factory'] = $app->share(function ()
        {
            return new EncoderFactory(array('Acraviz\Http\Security\User' => new Encoder((int) getenv('SECURITY_COST'))));
        });
    }

    /**
     * @inheritdoc
     */
    public function boot(Application $app)
    {
        $app['monolog'] = $app->share($app->extend('monolog', function (Logger $logger, Application $app)
        {
            $logger->setHandlers(array(new RotatingFileHandler($app['monolog.logfile'], 7)));
            return $logger;
        }));
        $app['translator'] = $app->share($app->extend('translator', function (Translator $translator, Application $app)
        {
            $translator->addLoader('php', new PhpFileLoader());
            $translator->addResource('php', sprintf('%s/assets/locales/en.php', $app['dir.root'], 'en'), 'en');
            return $translator;
        }));
        $app['twig'] = $app->share($app->extend('twig', function (\Twig_Environment $twig, Application $app)
        {
            $twig->addExtension(new TwigExtension($app));
            return $twig;
        }));
    }
}
