<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function search(?array $params = null): Query
    {
        $dql = $this->createQueryBuilder('document');

        if (!empty($params['title'])) {
            $dql->andWhere('unaccent(LOWER(document.title)) LIKE unaccent(LOWER(:title))')
                ->setParameter('title', '%' . $params['title'] . '%');
        }

        if (!empty($params['documentCategory'])) {
            $dql->andWhere('document.documentCategory = :documentCategory')
                ->setParameter('documentCategory', $params['documentCategory']);
        }

        if (!empty($params['aircraft'])) {
            $dql->andWhere('document.aircraft = :aircraft')
                ->setParameter('aircraft', $params['aircraft']);
        }

        return $dql->getQuery();
    }

    /**
     * @param array|null $params
     * @param int $page
     * @return Paginator|Document[]
     */
    public function pagination(?array $params = null, int $page = 1): Paginator
    {
        $query = $this->search($params);

        $query->setMaxResults(10);
        $query->setFirstResult(($page - 1) * 10);

        return new Paginator($query);
    }
}