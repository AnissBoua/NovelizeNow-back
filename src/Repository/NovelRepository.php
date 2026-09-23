<?php

namespace App\Repository;

use App\Entity\Novel;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Novel>
 *
 * @method Novel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Novel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Novel[]    findAll()
 * @method Novel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NovelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Novel::class);
    }

    public function save(Novel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Novel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createPublishedInCategoryQuery(int $categoryId, string $progress = 'all', bool $freeOnly = false): QueryBuilder
    {
        $qb = $this->createQueryBuilder('n')
            ->join('n.categories', 'cat')
            ->leftJoin('n.likes', 'l')
            ->leftJoin('n.chapters', 'ch', 'WITH', "ch.status = 'published'")
            ->where('cat.id = :categoryId')
            ->andWhere('n.publishedAt IS NOT NULL')
            ->setParameter('categoryId', $categoryId)
            ->groupBy('n.id');

        if (in_array($progress, ['ongoing', 'completed'], true)) {
            $qb->andWhere('n.progress = :progress')->setParameter('progress', $progress);
        }
        // Only the first published chapter is free, so a free start just means at least one published chapter.
        if ($freeOnly) {
            $qb->having('COUNT(DISTINCT ch.id) > 0');
        }

        return $qb;
    }

    public function findPublishedByAuthor(int $authorId): array
    {
        return $this->createQueryBuilder('n')
            ->join('n.userNovels', 'un', 'WITH', "un.relation = 'author'")
            ->where('un.user = :author')
            ->andWhere('n.publishedAt IS NOT NULL')
            ->setParameter('author', $authorId)
            ->getQuery()
            ->getResult();
    }

    public function findTopLikedInCategory(int $categoryId, int $limit): array
    {
        return $this->createPublishedInCategoryQuery($categoryId)
            ->addSelect('COUNT(DISTINCT l.id) AS HIDDEN likeCount')
            ->orderBy('likeCount', 'DESC')
            ->addOrderBy('n.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

   public function findMostLikedAndCommentedNovels($value): array
   {
        return $this->createQueryBuilder('n')
        ->join('n.likes', 'l')
        ->join('n.comments', 'c')
        ->groupBy('n.id')
        ->orderBy('COUNT(l.id) + COUNT(c.id)', 'DESC')
        ->where('n.publishedAt IS NOT NULL')
        ->setMaxResults($value)
        ->getQuery()
        ->getResult();
   }

   public function search($value): array
   {
        return $this->createQueryBuilder('n')
        ->join('n.userNovels', 'un', 'WITH', "un.relation = 'author'")
        ->join('un.user', 'u')
        ->where('n.title LIKE :val OR CONCAT(CONCAT(u.name, \' \'), u.lastname) LIKE :val OR u.username LIKE :val')
        ->andWhere('n.publishedAt IS NOT NULL')
        ->setParameter('val', '%'.$value.'%')
        ->setMaxResults(5)
        ->getQuery()
        ->getResult();
   }

//    public function findOneBySomeField($value): ?Novel
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
