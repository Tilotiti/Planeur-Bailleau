<?php

namespace App\Repository;

use App\Entity\Page;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method User      findOneByEmail(string $email)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param string $role
     * @return array
     */
    public function findByRole(string $role): array
    {
        $dql = $this->createQueryBuilder('user');

        $dql->andWhere('CAST(user.roles AS text) LIKE :role')
            ->setParameter('role', '%' . $role . '%');

        return $dql->getQuery()->getResult();
    }

    /**
     * @param string $code
     * @return User|null
     */
    public function findByInvitationCode(string $code): ?User
    {
        $dql = $this->createQueryBuilder('user');

        $dql->andWhere("MD5(user.email) = :code");
        $dql->setParameter('code', $code);

        $dql->setMaxResults(1);

        $query = $dql->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param array|null $params
     * @return Query
     */
    public function search(?array $params = null): Query {
        $dql = $this->createQueryBuilder('user');

        if(!empty($params['search'])) {
            $dql->andWhere('
                UNACCENT(LOWER(user.firstname)) LIKE UNACCENT(LOWER(:search))
                OR UNACCENT(LOWER(user.lastname)) LIKE UNACCENT(LOWER(:search))
                OR UNACCENT(LOWER(user.email)) LIKE UNACCENT(LOWER(:search))
                ')
                ->setParameter('search', '%'.$params['search'].'%');
        }

        return $dql->getQuery();
    }

    /**
     * @param array|null $params
     * @param int $page
     * @return Paginator|User[]
     */
    public function pagination(?array $params = null, int $page = 1): Paginator {
        $query = $this->search($params);

        $query->setMaxResults(10);
        $query->setFirstResult(($page - 1) * 10);

        return new Paginator($query);
    }
}
