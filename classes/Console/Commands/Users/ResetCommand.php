<?php

namespace Acraviz\Console\Commands\Users;

use Acraviz\Console\Commands\BaseCommand;
use Acraviz\Support\Text;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetCommand extends BaseCommand
{
    protected function onConfigure()
    {
        $this->setName('users:reset');
        $this->setDescription('Resets password for an existing user in database');
        $this->addOption('password', 'P', InputOption::VALUE_REQUIRED);
        $this->addOption('username', 'U', InputOption::VALUE_REQUIRED);
    }

    protected function onExec(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getOption('username');
        $password = $input->getOption('password');
        if (!Text::is($username)) {
            $output->writeln('Please specify a username using via <error>-U or --username</error>');
        } else if (!Text::is($password)) {
            $output->writeln('Please specify a password using via <error>-P or --password</error>');
        } else  {
            $output->write(sprintf('Resetting password for <comment>%s</comment>...', $username));
            try {
                /** @var $db \Doctrine\DBAL\Connection */
                /** @var $encoder \Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder */
                $db = $this->container['db'];
                $encoder = $this->container['encoder'];
                $qb = $db->createQueryBuilder();
                $qb->update('users')
                    ->set('password', '?')
                    ->where($qb->expr()->eq('username', '?'))
                    ->setParameter(0, $encoder->encodePassword($password, null))
                    ->setParameter(1, $username);
                if ($qb->execute() == 1) {
                    $output->writeln('<info>Done</info>');
                    $output->writeln(sprintf('New password for <comment>%s</comment> is <info>%s</info>', $username, $password));
                } else {
                    $output->writeln('<error>Failed</error>');
                }
            } catch (\Exception $e) {
                $output->writeln('<error>Failed</error>');
                throw new \RuntimeException($e);
            }
        }
    }
}
