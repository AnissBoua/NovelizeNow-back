<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    /**
     * ranked by how many readers they share. Returns rows of [author_id, shared_readers].
     */
    public function findAuthorsAlsoRead(int $authorId, int $limit): array
    {
        $sql = "
            SELECT un2.user_id AS author_id, COUNT(DISTINCT activity.user_id) AS shared_readers
            FROM (
                SELECT rp.user_id FROM reading_progress rp
                    JOIN user_novel un ON un.novel_id = rp.novel_id AND un.relation = 'author'
                    JOIN novel n ON n.id = rp.novel_id
                    WHERE un.user_id = :author AND n.published_at IS NOT NULL AND rp.user_id <> :author
                UNION
                SELECT o.user_id FROM `order` o
                    JOIN user_novel un ON un.novel_id = o.novel_id AND un.relation = 'author'
                    JOIN novel n ON n.id = o.novel_id
                    WHERE un.user_id = :author AND n.published_at IS NOT NULL AND o.user_id <> :author
            ) readers
            JOIN (
                SELECT user_id, novel_id FROM reading_progress
                UNION
                SELECT user_id, novel_id FROM `order`
            ) activity ON activity.user_id = readers.user_id
            JOIN novel n2 ON n2.id = activity.novel_id AND n2.published_at IS NOT NULL
            JOIN user_novel un2 ON un2.novel_id = n2.id AND un2.relation = 'author'
            WHERE un2.user_id <> :author
            GROUP BY un2.user_id
            ORDER BY shared_readers DESC, un2.user_id ASC
            LIMIT " . (int) $limit;

        return $this->getEntityManager()->getConnection()
            ->executeQuery($sql, ['author' => $authorId])
            ->fetchAllAssociative();
    }

//    /**
//     * @return User[] Returns an array of User objects
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

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
