<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Novel;
use App\Entity\UserNovel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserNovelFixture extends Fixture {
    
    public function load(ObjectManager $manager)
    {
        $this->loadUserNovels($manager);
    }

    public static function loadUserNovels(ObjectManager $manager)
    {
        $novels = $manager->getRepository(Novel::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        foreach ($novels as $key => $novel) {
            $usernovel = new UserNovel();
            $usernovel->setUser($users[array_rand($users)]);
            $usernovel->setNovel($novel);
            $usernovel->setRelation('author');
            $manager->persist($usernovel);
        }
        $manager->flush();
    }
}