<?php

namespace Acraviz\Http\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials()
    {
    }

    public function getEmail()
    {
        return null;
    }

    public function getId()
    {
        return $this->data['id'];
    }

    /**
     * @inheritdoc
     */
    public function getPassword()
    {
        return $this->data['password'];
    }

    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        return array();
    }

    /**
     * @inheritdoc
     */
    public function getSalt()
    {
        return;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return $this->data['username'];
    }
}
