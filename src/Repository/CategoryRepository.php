<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function save(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Every category with its number of published novels and the total likes on them, keyed by id.
     */
    public function findAllWithPublishedStats(): array
    {
        $rows = $this->createQueryBuilder('c')
            ->select('c.id, c.name, c.icon, c.description, COUNT(DISTINCT n.id) AS novelCount')
            ->leftJoin('c.novel', 'n', 'WITH', 'n.publishedAt IS NOT NULL')
            ->groupBy('c.id')
            ->getQuery()
            ->getArrayResult();

        $likes = $this->createQueryBuilder('c')
            ->select('c.id, COUNT(l.id) AS likesCount')
            ->join('c.novel', 'n')
            ->join('n.likes', 'l')
            ->where('n.publishedAt IS NOT NULL')
            ->groupBy('c.id')
            ->getQuery()
            ->getArrayResult();
        $likesById = array_column($likes, 'likesCount', 'id');

        $result = [];
        foreach ($rows as $row) {
            $row['novelCount'] = (int) $row['novelCount'];
            $row['likesCount'] = (int) ($likesById[$row['id']] ?? 0);
            $result[$row['id']] = $row;
        }
        return $result;
    }

    /**
     * Categories that most often share published novels with the given one.
     */
    public function findRelated(int $categoryId, int $limit): array
    {
        return $this->createQueryBuilder('c2')
            ->select('c2.id, COUNT(DISTINCT n.id) AS shared')
            ->join('c2.novel', 'n')
            ->join('n.categories', 'c1')
            ->where('c1.id = :id')
            ->andWhere('c2.id <> :id')
            ->andWhere('n.publishedAt IS NOT NULL')
            ->setParameter('id', $categoryId)
            ->groupBy('c2.id')
            ->orderBy('shared', 'DESC')
            ->addOrderBy('c2.id', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
