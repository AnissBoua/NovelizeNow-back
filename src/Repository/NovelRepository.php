<?php

namespace App\Repository;

use App\Entity\Novel;
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


   public function findMostLikedAndCommentedNovels($value): array
   {
        return $this->createQueryBuilder('n')
        ->join('n.likes', 'l')
        ->join('n.comments', 'c')
        ->groupBy('n.id')
        ->orderBy('COUNT(l.id) + COUNT(c.id)', 'DESC')
        ->setMaxResults($value)
        ->getQuery()
        ->getResult();
   }

//    public function findBestCategoriesNovels($value): array
//    {
    
//         return $this->createQueryBuilder('c')
//         ->join('c.novels', 'n')
//         ->groupBy('c.id')
//         ->orderBy('COUNT(n.id)', 'DESC')
//         ->setMaxResults($value)
//         ->getQuery()
//         ->getResult();
//    }

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
