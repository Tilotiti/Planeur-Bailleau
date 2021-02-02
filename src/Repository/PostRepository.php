<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function search(?array $params = null): Query {
        $dql = $this->createQueryBuilder('post');
        $dql->select('DISTINCT post');

        if(!empty($params['title'])) {
            $dql->andWhere('UNACCENT(LOWER(post.title)) LIKE UNACCENT(LOWER(:title))')
                ->setParameter('title', '%'.$params['title'].'%');
        }

        if(!empty($params['locale'])) {
            $dql->andWhere('post.locale = :locale')
                ->setParameter('locale', $params['local']);
        }

        $dql->orderBy('post.createdAt', 'DESC');

        return $dql->getQuery();
    }

    /**
     * @param array|null $params
     * @param int $post
     * @return Paginator|Post[]
     */
    public function pagination(?array $params = null, int $post = 1): Paginator {
        $query = $this->search($params);

        $query->setMaxResults(10);
        $query->setFirstResult(($post - 1) * 10);

        return new Paginator($query);
    }
}