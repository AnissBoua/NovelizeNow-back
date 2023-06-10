<?php

namespace App\DataFixtures;

use App\Entity\Like;
use App\Entity\User;
use App\Entity\Novel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LikeFixture extends Fixture {
    
    public function load(ObjectManager $manager)
    {
        $this->loadLikes($manager);
    }

    public static function loadLikes(ObjectManager $manager)
    {
        $novels = $manager->getRepository(Novel::class)->findAll();

        foreach ($novels as $key => $novel) {
            $quantiteLikes = rand(0, $manager->getRepository(User::class)->count([]));
            for ($i=0; $i < $quantiteLikes; $i++) { 
                $user = rand(
                    $manager->getRepository(User::class)->createQueryBuilder('u')
                        ->select('MIN(u.id)')
                        ->getQuery()
                        ->getSingleScalarResult(),
                    $manager->getRepository(User::class)->createQueryBuilder('u')
                        ->select('MAX(u.id)')
                        ->getQuery()
                        ->getSingleScalarResult()
                );
                $alreadyLiked = $manager->getRepository(Like::class)->createQueryBuilder('l')
                    ->where('l.novel = :novel')
                    ->andWhere('l.user = :user')
                    ->setParameter('novel', $novel->getId())
                    ->setParameter('user', $user)
                    ->getQuery()
                    ->getOneOrNullResult();
                if ($alreadyLiked) {
                    continue;
                }
                $like = new Like();
                $like->setNovel($novel);
                $like->setUser($manager->getRepository(User::class)->find($user));
                $manager->persist($like);
                $manager->flush();
            }
        }
    }
}