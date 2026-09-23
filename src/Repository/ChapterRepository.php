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

    public function findRecentPublishedByAuthor(int $authorId, int $limit): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.novel', 'n')
            ->join('n.userNovels', 'un', 'WITH', "un.relation = 'author'")
            ->where('un.user = :author')
            ->andWhere('n.publishedAt IS NOT NULL')
            ->andWhere("c.status = 'published'")
            ->setParameter('author', $authorId)
            ->orderBy('c.dateCreation', 'DESC')
            ->addOrderBy('c.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findNextScheduledByAuthor(int $authorId): ?Chapter
    {
        return $this->createQueryBuilder('c')
            ->join('c.novel', 'n')
            ->join('n.userNovels', 'un', 'WITH', "un.relation = 'author'")
            ->where('un.user = :author')
            ->andWhere('n.publishedAt IS NOT NULL')
            ->andWhere("c.status = 'scheduled'")
            ->setParameter('author', $authorId)
            ->orderBy('c.publishAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function countPublishedInCategorySince(int $categoryId, \DateTimeInterface $since): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->join('c.novel', 'n')
            ->join('n.categories', 'cat')
            ->where('cat.id = :categoryId')
            ->andWhere('n.publishedAt IS NOT NULL')
            ->andWhere("c.status = 'published'")
            ->andWhere('c.dateCreation >= :since')
            ->setParameter('categoryId', $categoryId)
            ->setParameter('since', $since)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findLastChapters($limit, $offset = 0): array
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
        AND novel.published_at IS NOT NULL
        ORDER BY chapter.id DESC;
        */
        $lastChapters = $this->createQueryBuilder('c')
            ->select('MAX(c.id) AS last_chapter_id, n.id AS novel_id, MAX(c.dateCreation) AS HIDDEN last_date')
            ->join('c.novel', 'n')
            ->andWhere('c.status = :status')
            ->andWhere('n.publishedAt IS NOT NULL')
            ->setParameter('status', 'published')
            ->groupBy('novel_id')
            ->orderBy('last_date', 'DESC')
            ->addOrderBy('last_chapter_id', 'DESC')
            ->setFirstResult($offset)
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
                ->andWhere('n.publishedAt IS NOT NULL')
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
