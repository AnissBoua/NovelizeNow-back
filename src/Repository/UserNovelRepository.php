<?php

namespace App\Repository;

use App\Entity\UserNovel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserNovel>
 *
 * @method UserNovel|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserNovel|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserNovel[]    findAll()
 * @method UserNovel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserNovelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserNovel::class);
    }

    public function save(UserNovel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserNovel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNovelsByUser($user_id) {
        $userNovels = $this->createQueryBuilder('user_novel')
                        ->join('user_novel.novel', 'novel')
                        ->where('user_novel.user = :user_id')
                        ->setParameter('user_id', $user_id)
                        ->orderBy('novel.id', 'DESC')
                        ->select('user_novel', 'novel')
                        ->getQuery()
                        ->getResult();
        return $userNovels;
    }

//    /**
//     * @return UserNovel[] Returns an array of UserNovel objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserNovel
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
