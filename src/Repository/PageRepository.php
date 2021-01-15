<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class PageRepository
 * @package App\Repository
 * @method Page findOneByCode(string $code)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function search(?array $params = null): Query {
        $dql = $this->createQueryBuilder('page');
        $dql->select('DISTINCT page');

        $dql->leftJoin('page.translations', 'translation');

        if(!empty($params['title'])) {
            $dql->andWhere('LOWER(translation.title) LIKE LOWER(:title)')
                ->setParameter('title', '%'.$params['title'].'%');
        }

        if(!empty($params['menu'])) {
            $dql->andWhere('page.menu = :menu')
                ->setParameter('menu', $params['menu']);
        }

        return $dql->getQuery();
    }

    /**
     * @param array|null $params
     * @param int $page
     * @return Paginator|Page[]
     */
    public function pagination(?array $params = null, int $page = 1): Paginator {
        $query = $this->search($params);

        $query->setMaxResults(10);
        $query->setFirstResult(($page - 1) * 10);

        return new Paginator($query);
    }
}