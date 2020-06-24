<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 26/08/16
 * Time: 12:06 PM
 */

namespace NS\SimpleUserBundle\Security;

use NS\SimpleUserBundle\Entity\User\User;
use NS\SimpleUserBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SecurityUserProvider implements UserProviderInterface
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * SecurityUserProvider constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByUsername($username)
    {
        /** @var User $user */
        $user = $this->userRepository->loadUserByUsername($username);

        return new SecurityUser(
            $user->getId(),
            $user->getName(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getSalt(),
            $user->getRoles()
        );
    }

    /**
     * @inheritDoc
     *
     * @param SecurityUser $user
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getEmail());
    }

    /**
     * @inheritDoc
     */
    public function supportsClass($class): bool
    {
        return $class === SecurityUser::class;
    }
}
