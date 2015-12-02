<?php

namespace Acraviz\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{
    /**
     * @var \Pimple
     */
    protected $container;

    /**
     * Command constructor.
     * @param \Pimple $container
     * @param null $name
     */
    function __construct(\Pimple $container, $name = null)
    {
        $this->container = $container;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->onConfigure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->onExec($input, $output);
    }

    abstract protected function onConfigure();

    abstract protected function onExec(InputInterface $input, OutputInterface $output);
}
