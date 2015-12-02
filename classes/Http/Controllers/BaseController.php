<?php

namespace Acraviz\Http\Controllers;

abstract class BaseController
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
}
