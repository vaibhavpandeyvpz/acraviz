<?php

namespace Acraviz\Http\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
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
     * @inheritdoc
     */
    public function loadUserByUsername($username)
    {
        /** @var $db \Doctrine\DBAL\Connection */
        $db = $this->container['db'];
        $qb = $db->createQueryBuilder();
        $user = $qb->select('*')
            ->from('users')
            ->where($qb->expr()->eq('username', '?'))
            ->setParameter(0, $username)
            ->execute();
        if ($user->rowCount() !== 1) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }
        return new User($user->fetch(\PDO::FETCH_ASSOC));
    }

    /**
     * @inheritdoc
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        return $class === 'Acraviz\Http\Security\User';
    }
}
