<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Novel;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CommentFixture extends Fixture {

    protected static $faker;
    
    public function load(ObjectManager $manager)
    {
        $this->loadComments($manager);
    }

    public static function loadComments(ObjectManager $manager)
    {
        self::$faker = Factory::create();
        $novels = $manager->getRepository(Novel::class)->findAll();

        foreach ($novels as $key => $novel) {
            $quantiteComments = rand(0, $manager->getRepository(User::class)->count([]));
            for ($i=0; $i < $quantiteComments; $i++) { 
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
                $comment = new Comment();
                $comment->setNovel($novel);
                $comment->setUser($manager->getRepository(User::class)->find($user));
                $comment->setContent(self::$faker->text(200));
                $manager->persist($comment);
                $manager->flush();
            }
        }
    }
}