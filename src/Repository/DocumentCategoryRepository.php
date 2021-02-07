<?php

namespace App\Repository;

use App\Entity\DocumentCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class DocumentCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentCategory::class);
    }

    public function search(?array $params = null): Query
    {
        $dql = $this->createQueryBuilder('documentCategory');

        if (!empty($params['title'])) {
            $dql->andWhere('UNACCENT(LOWER(documentCategory.title)) LIKE UNACCENT(LOWER(:title))')
                ->setParameter('title', '%' . $params['title'] . '%');
        }

        return $dql->getQuery();
    }

    /**
     * @param array|null $params
     * @param int $page
     * @return Paginator|DocumentCategory[]
     */
    public function pagination(?array $params = null, int $page = 1): Paginator
    {
        $query = $this->search($params);

        $query->setMaxResults(10);
        $query->setFirstResult(($page - 1) * 10);

        return new Paginator($query);
    }
}