<?php


namespace NS\SimpleUserBundle\Repository;

use NS\SimpleUserBundle\Entity\User\User;
use Doctrine\ORM\Query;
use Doctrine\ORM\UnexpectedResultException;
use Doctrine\Persistence\ManagerRegistry;
use NS\AdminBundle\Repository\AbstractAdminManagedRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserRepository extends AbstractAdminManagedRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * UserProviderInterface
     *
     * @param string $username
     *
     * @return string
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {
        try
        {
            return $this->createQueryBuilder('u')
                        ->andWhere('u.email = :email')
                        ->setParameter('email', $username)
                        ->getQuery()->getSingleResult();

        }
        catch(UnexpectedResultException $exception)
        {
            throw new UsernameNotFoundException(sprintf('No record found for user %s', $username), null, $exception);
        }
    }

    public function findByToken($token)
    {
        return $this->createQueryBuilder('u')
                    ->andWhere('u.confirmationToken = :token')
                    ->setParameter('token', $token)
                    ->getQuery()->getSingleResult();
    }

    /**
     * @param string $token
     *
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByResetToken($token)
    {
        return $this->createQueryBuilder('u')
                    ->andWhere('u.resetToken = :token')
                    ->setParameter('token', $token)
                    ->getQuery()->getSingleResult();
    }

    /**
     * @param null $order_by
     *
     * @return Query
     */
    public function getListQuery($order_by = null, $order=false): Query
    {
        return parent::getListQuery('e.name', 'asc');
    }
}
