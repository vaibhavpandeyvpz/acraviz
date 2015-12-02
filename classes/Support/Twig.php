<?php

namespace Acraviz\Support;

class Twig extends \Twig_Extension
{
    /**
     * @var \Pimple
     */
    protected $container;

    /**
     * Command constructor.
     * @param \Pimple $container
     */
    function __construct(\Pimple $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('url_decode', 'urldecode')
        );
    }

    /**
     * @param string $asset
     * @return string
     */
    public function getFuncAsset($asset)
    {
        return sprintf('%s/%s', $this->container['request']->getBasePath(), ltrim($asset, '/'));
    }

    public function getFuncDate()
    {
        return call_user_func_array('date', func_get_args());
    }

    public function getFuncInput($field)
    {
        return $this->container['request']->get($field);
    }

    public function getFuncLines($text)
    {
        return explode("\n", $text);
    }

    public function getFuncNvp($text)
    {
        $nvps = [];
        $lines = $this->getFuncLines($text);
        foreach ($lines as $l) {
            if (Text::is($l) && (strpos($l, '=') !== false)) {
                $nvps[] = explode('=', $l, 2);
            }
        }
        return $nvps;
    }

    public function getFuncUser()
    {
        /** @var $token \Symfony\Component\Security\Core\Authentication\Token\TokenInterface */
        $token = $this->container['security.token_storage']->getToken();
        return $token !== null ? $token->getUser() : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('asset', array($this, 'getFuncAsset')),
            new \Twig_SimpleFunction('date', array($this, 'getFuncDate')),
            new \Twig_SimpleFunction('input', array($this, 'getFuncInput')),
            new \Twig_SimpleFunction('lines', array($this, 'getFuncLines')),
            new \Twig_SimpleFunction('nvp', array($this, 'getFuncNvp')),
            new \Twig_SimpleFunction('user', array($this, 'getFuncUser'))
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'acraviz';
    }
}
