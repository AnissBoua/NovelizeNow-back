<?php

namespace App\Repository;

use App\Entity\Chapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chapter>
 *
 * @method Chapter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chapter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chapter[]    findAll()
 * @method Chapter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChapterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chapter::class);
    }

    public function save(Chapter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Chapter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastChapters($limit): array
    {
        /*
        SELECT chapter.id,chapter.title, chapter.status, chapter.novel_id FROM `chapter`
        JOIN novel ON novel.id = chapter.novel_id
        JOIN (
            SELECT MAX(chapter.id) as last_id, chapter.novel_id as last_novel_id
            FROM chapter
            WHERE status = 'published'
            GROUP BY last_novel_id
        ) AS last_chapters ON last_chapters.last_id = chapter.id
        WHERE chapter.status = 'published'
        AND novel.status = 'published'
        ORDER BY chapter.id DESC;
        */
        $lastChapters = $this->createQueryBuilder('c')
            ->select('MAX(c.id) AS last_chapter_id, n.id AS novel_id')
            ->join('c.novel', 'n')
            ->andWhere('c.status = :status')
            ->andWhere('n.status = :status')
            ->setParameter('status', 'published')
            ->groupBy('novel_id')
            ->orderBy('last_chapter_id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $chapters = [];
        foreach ($lastChapters as $key => $chapter) {
            $chapterData = $this->createQueryBuilder('c')
                ->select('c.id, c.title, c.status, n.id as novel_id')
                ->join('c.novel', 'n')
                ->andWhere('c.id = :id')
                ->andWhere('c.status = :status')
                ->andWhere('n.status = :status')
                ->setParameter('id', $chapter['last_chapter_id'])
                ->setParameter('status', 'published')
                ->getQuery()
                ->getOneOrNullResult();
            array_push($chapters, $chapterData);
        }
        return $chapters;
    }

//    /**
//     * @return Chapter[] Returns an array of Chapter objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Chapter
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
