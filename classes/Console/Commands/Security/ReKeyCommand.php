<?php

namespace Acraviz\Console\Commands\Security;

use Acraviz\Console\Commands\BaseCommand;
use Acraviz\Support\Text;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReKeyCommand extends BaseCommand
{
    protected function onConfigure()
    {
        $this->setName('security:rekey');
        $this->setDescription('Resets application key to something random');
    }

    protected function onExec(InputInterface $input, OutputInterface $output)
    {
        $file = $this->container['root'] . '/.env';
        $contents = @file_get_contents($file);
        if (!Text::is($contents)) {
            $output->writeln(sprintf('Using to write <comment>%s</comment>...', $file));
        } else {
            $output->write(sprintf('Setting up key in <comment>%s</comment>...', $file));
            $lines = explode(PHP_EOL, $contents);
            for ($i = 0; $i < count($lines); ++$i) {
                $line = $lines[$i];
                if (Text::is($line) && (strpos($line, 'SECURITY_KEY') === 0)) {
                    /** @var $random \RandomLib\Generator */
                    $random = $this->container['random'];
                    $lines[$i] = sprintf('SECURITY_KEY=%s', $random->generateString(16));
                    break;
                }
            }
            if (file_put_contents($file, implode(PHP_EOL, $lines))) {
                $output->writeln('<info>Done</info>');
            } else {
                $output->writeln('<error>Failed</error>');
                $output->writeln(sprintf('Unable to write to write <comment>%s</comment>...', $file));
            }
        }
    }
}
