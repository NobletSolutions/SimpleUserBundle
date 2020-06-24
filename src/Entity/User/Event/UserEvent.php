<?php
namespace NS\SimpleUserBundle\Entity\User\Event;

use NS\SimpleUserBundle\Entity\User\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserEvent extends Event
{
    const PASSWORD_UPDATED = 'user.password_updated';
    const USER_UPDATED = 'user.updated';
    const USER_REGISTERED = 'user.registered';
    const LAST_LOGIN_UPDATED = 'user.last_login.updated';
    const USER_DELETED = 'user.deleted';
    const USER_EDITED = 'user.edited';
    const USER_CREATED = 'user.created';
    const PASSWORD_RESET = 'user.password_reset';

    /**
     * @var User
     */
    private $user;

    /**
     * UserEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}

