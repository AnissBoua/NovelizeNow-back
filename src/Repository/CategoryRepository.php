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

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
   public function findBestCategoriesNovels($value): array
   {
        $categories = $this->createQueryBuilder('c')
            ->join('c.novel', 'n')
            ->groupBy('c.id')
            ->orderBy('COUNT(n.id)', 'DESC')
            ->where('n.status = :status')
            ->setParameter('status', 'published')
            ->setMaxResults($value)
            ->getQuery()
            ->getResult();

        foreach ($categories as $key => $categorie) {
            foreach ($categorie->getNovel()->toArray() as $novel) {
                if ($novel->getStatus() !== 'published') {
                    $categorie->removeNovel($novel);
                }
            }

            while (count($categorie->getNovel()->toArray()) > 4) {
                $last = $categorie->getNovel()->last();
                $categorie->removeNovel($last);
            }

            $categorie->setNovel(array_values($categorie->getNovel()->toArray()));
            $categories[$key] = $categorie;
        }
        return $categories;
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
