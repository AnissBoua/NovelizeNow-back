<?php

namespace App\Repository;

use App\Entity\LibraryEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LibraryEntry>
 */
class LibraryEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LibraryEntry::class);
    }

    public function getNovelsByUser($user_id)
    {
        return $this->createQueryBuilder('library_entry')
            ->join('library_entry.novel', 'novel')
            ->where('library_entry.user = :user_id')
            ->setParameter('user_id', $user_id)
            ->orderBy('library_entry.dateCreation', 'DESC')
            ->select('library_entry', 'novel')
            ->getQuery()
            ->getResult();
    }
}
