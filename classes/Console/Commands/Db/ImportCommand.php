<?php

namespace Acraviz\Console\Commands\Db;

use Acraviz\Console\Commands\BaseCommand;
use Acraviz\Support\Text;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends BaseCommand
{
    protected function onConfigure()
    {
        $this->setName('db:import');
        $this->setDescription('Imports given SQL file into the database');
        $this->addOption('file', 'F', InputOption::VALUE_REQUIRED);
    }

    protected function onExec(InputInterface $input, OutputInterface $output)
    {

        $file = $input->getOption('file');
        if (Text::is($file)) {
            $file = realpath($file);
            $sql = @file_get_contents($file);
            if (Text::is($sql)) {
                $output->write(sprintf('Importing file <comment>%s</comment>...', $file));
                try {
                    /** @var $db \Doctrine\DBAL\Connection */
                    $db = $this->container['db'];
                    $db->exec($sql);
                    $output->writeln('<info>Done</info>');
                } catch (\Exception $e) {
                    $output->writeln('<error>Failed</error>');
                    throw new \RuntimeException($e);
                }
            } else {
                $output->writeln('Unable to read any SQL commands from file');
            }
        } else {
            $output->writeln('Please specify a file to import SQL from');
        }
    }
}
