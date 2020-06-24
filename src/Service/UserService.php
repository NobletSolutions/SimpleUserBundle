<?php


namespace NS\SimpleUserBundle\Service;


use NS\AdminBundle\Service\AdminServiceInterface;
use NS\SimpleUserBundle\Entity\User\Event\UserEvent;
use NS\SimpleUserBundle\Entity\User\User;
use NS\SimpleUserBundle\Entity\User\Exception\UserNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnexpectedResultException;
use NS\AdminBundle\Repository\AbstractAdminManagedRepository;
use NS\AdminBundle\Service\AdminService;
use NS\SimpleUserBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UserService extends AdminService
{
    /**
     * @var EncoderFactoryInterface
     */
    protected $encoder_factory;

    /**
     * UserService constructor.
     *
     * @param EntityManagerInterface         $entity_manager
     * @param EventDispatcherInterface       $dispatcher
     * @param EncoderFactoryInterface        $encoder_factory
     */
    public function __construct(EntityManagerInterface $entity_manager, EventDispatcherInterface $dispatcher, EncoderFactoryInterface $encoder_factory)
    {
        parent::__construct($entity_manager, $dispatcher);
        $this->encoder_factory = $encoder_factory;
    }

    /**
     * @param $username
     *
     * @return mixed
     */
    public function findByUsername($username)
    {
        try
        {
            return $this->repository->loadUserByUsername($username);
        }
        catch (UsernameNotFoundException $e)
        {
            throw new UserNotFoundException('User not found');
        }
    }

    /**
     * @param $token
     *
     * @return mixed
     */
    public function findByResetToken($token)
    {
        try
        {
            return $this->repository->findByResetToken($token);
        }
        catch (UnexpectedResultException $e)
        {
            throw new UserNotFoundException('User not found');
        }
    }

    /**
     * @param $username
     */
    public function issuePasswordReset($username): void
    {
        /**
         * @var User $user
         */
        $user = $this->findByUsername($username);

        if (!$user) {
            throw new UserNotFoundException('User '.$username.' not found.');
        }

        $user->setResetToken(sha1($user->getCreatedAt()->format('U').uniqid('', true)));
        $user->setPassword(null);
        $this->entity_manager->persist($user);
        $this->entity_manager->flush();

        $this->dispatcher->dispatch(new UserEvent($user), UserEvent::PASSWORD_RESET);
    }

    /**
     * @param string $username
     * @param string $password
     */
    public function updateUserPassword(string $username, string $password): void
    {
        /**
         * @var User $user
         */
        $user = $user = $this->findByUsername($username);
        if (!$user) {
            throw new UserNotFoundException('User '.$username.' not found.');
        }

        $encoder = $this->encoder_factory->getEncoder($user);
        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
        $this->entity_manager->persist($user);
        $this->entity_manager->flush();
        $this->dispatcher->dispatch(new UserEvent($user), UserEvent::PASSWORD_UPDATED);
    }

    /**
     * @param string $username
     * @param string $password
     * @param array  $roles
     */
    public function createUser(string $username, string $password, array $roles): void
    {
        $user = new User();
        $user->setEmail($username);
        $user->setName($username);
        $encoder = $this->encoder_factory->getEncoder($user);
        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
        $user->setRoles($roles);

        $this->_create($user);
    }

    public function getClass(): string
    {
        return User::class;
    }
}
