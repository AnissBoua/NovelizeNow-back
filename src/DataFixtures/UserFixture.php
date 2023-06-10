<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture {
    
    private static UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        self::$passwordHasher = $passwordHasher;
    }
    
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
    }

    public static function loadUsers(ObjectManager $manager)
    {
        $users = [
            [
                'email' => 'aragon@domain.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Aragorn',
                'lastname' => 'My Throne',
                'coins' => 100,
                'username' => 'aragon',
                'avatar' => NULL
            ],
            [
                'email' => 'samusaran@gmail.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Samus',
                'lastname' => 'Aran',
                'coins' => 120,
                'username' => 'samusaran',
                'avatar' => NULL
            ],
            [
                'email' => 'linkofhyrule@outlook.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Link',
                'lastname' => 'Hyrule',
                'coins' => 80,
                'username' => 'linkofhyrule',
                'avatar' => NULL
            ],
            [
                'email' => 'witcher.geralt@yahoo.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Geralt',
                'lastname' => 'of Rivia',
                'coins' => 90,
                'username' => 'witchergeralt',
                'avatar' => NULL
            ],
            [
                'email' => 'ezrascarlet@gmail.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Ezra',
                'lastname' => 'Scarlet',
                'coins' => 200,
                'username' => 'ezrascarlet',
                'avatar' => NULL
            ],
            [
                'email' => 'yusukekitagawa@outlook.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Yusuke',
                'lastname' => 'Kitagawa',
                'coins' => 150,
                'username' => 'yusukekitagawa',
                'avatar' => NULL
            ],
            [
                'email' => 'mulanwarrior@yahoo.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Mulan',
                'lastname' => 'Warrior',
                'coins' => 80,
                'username' => 'mulanwarrior',
                'avatar' => NULL
            ],
            [
                'email' => 'sorakeyblade@gmail.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Sora',
                'lastname' => 'Keyblade',
                'coins' => 70,
                'username' => 'sorakeyblade',
                'avatar' => NULL
            ],
            [
                'email' => 'cloudstrife@outlook.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Cloud',
                'lastname' => 'Strife',
                'coins' => 250,
                'username' => 'cloudstrife',
                'avatar' => NULL
            ],
            [
                'email' => 'elizabethbennet@yahoo.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Elizabeth',
                'lastname' => 'Bennet',
                'coins' => 30,
                'username' => 'elizabethbennet',
                'avatar' => NULL
            ],
            [
                'email' => 'kenshinhimura@gmail.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Kenshin',
                'lastname' => 'Himura',
                'coins' => 100,
                'username' => 'kenshinhimura',
                'avatar' => NULL
            ],
            [
                'email' => 'gonfreecss@outlook.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Gon',
                'lastname' => 'Freecss',
                'coins' => 180,
                'username' => 'gonfreecss',
                'avatar' => NULL
            ],
            [
                'email' => 'trisprior@yahoo.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Tris',
                'lastname' => 'Prior',
                'coins' => 70,
                'username' => 'trisprior',
                'avatar' => NULL
            ],
            [
                'email' => 'eowynshieldmaiden@gmail.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Eowyn',
                'lastname' => 'Shieldmaiden',
                'coins' => 40,
                'username' => 'eowynshieldmaiden',
                'avatar' => NULL
            ],
            [
                'email' => 'tyrionlannister@outlook.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Tyrion',
                'lastname' => 'Lannister',
                'coins' => 120,
                'username' => 'tyrionlannister',
                'avatar' => NULL
            ],
            [
                'email' => 'katara.waterbender@yahoo.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Katara',
                'lastname' => 'Waterbender',
                'coins' => 85,
                'username' => 'katarawaterbender',
                'avatar' => NULL
            ],
            [
                'email' => 'spikespiegel@gmail.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Spike',
                'lastname' => 'Spiegel',
                'coins' => 110,
                'username' => 'spikespiegel',
                'avatar' => NULL
            ],
            [
                'email' => 'tifalockhart@yahoo.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Tifa',
                'lastname' => 'Lockhart',
                'coins' => 170,
                'username' => 'tifalockhart',
                'avatar' => NULL
            ],
            [
                'email' => 'lightyagami@gmail.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Light',
                'lastname' => 'Yagami',
                'coins' => 95,
                'username' => 'lightyagami',
                'avatar' => NULL
            ],
            [
                'email' => 'lisbethsalander@outlook.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Lisbeth',
                'lastname' => 'Salander',
                'coins' => 75,
                'username' => 'lisbethsalander',
                'avatar' => NULL
            ],
            [
                'email' => 'alucard@domain.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Alucard',
                'lastname' => 'Castlevania',
                'coins' => 130,
                'username' => 'alucard',
                'avatar' => NULL
            ],
            [
                'email' => 'yunafinalfantasy@gmail.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Yuna',
                'lastname' => 'Final Fantasy',
                'coins' => 90,
                'username' => 'yunafinalfantasy',
                'avatar' => NULL
            ],
            [
                'email' => 'edwardelric@outlook.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Edward',
                'lastname' => 'Elric',
                'coins' => 120,
                'username' => 'edwardelric',
                'avatar' => NULL
            ],
            [
                'email' => 'leviackerman@gmail.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Levi',
                'lastname' => 'Ackerman',
                'coins' => 110,
                'username' => 'leviackerman',
                'avatar' => NULL
            ],
            [
                'email' => 'gatstheberserker@outlook.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Gats',
                'lastname' => 'The Berserker',
                'coins' => 150,
                'username' => 'gatstheberserker',
                'avatar' => NULL
            ],
            [
                'email' => 'totoro@outlook.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Totoro',
                'lastname' => 'Studio Ghibli',
                'coins' => 50,
                'username' => 'totoro',
                'avatar' => NULL
            ],
            [
                'email' => 'jonathanjoestar@yahoo.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Jonathan',
                'lastname' => 'Joestar',
                'coins' => 80,
                'username' => 'jonathanjoestar',
                'avatar' => NULL
            ],
            [
                'email' => 'lucyscarlett@gmail.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Lucy',
                'lastname' => 'Scarlett',
                'coins' => 90,
                'username' => 'lucyscarlett',
                'avatar' => NULL
            ],
            [
                'email' => 'arthurmorgan@outlook.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Arthur',
                'lastname' => 'Morgan',
                'coins' => 160,
                'username' => 'arthurmorgan',
                'avatar' => NULL
            ],
            [
                'email' => 'erenjaeger@yahoo.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Eren',
                'lastname' => 'Jaeger',
                'coins' => 120,
                'username' => 'erenjaeger',
                'avatar' => NULL
            ],
            [
                'email' => 'korratheavatar@gmail.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Korra',
                'lastname' => 'The Avatar',
                'coins' => 100,
                'username' => 'korratheavatar',
                'avatar' => NULL
            ],
            [
                'email' => 'sonicthehedgehog@yahoo.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Sonic',
                'lastname' => 'The Hedgehog',
                'coins' => 80,
                'username' => 'sonicthehedgehog',
                'avatar' => NULL
            ],
            [
                'email' => 'leviathan@gmail.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Leviathan',
                'lastname' => 'Mythology',
                'coins' => 70,
                'username' => 'leviathan',
                'avatar' => NULL
            ],
            [
                'email' => 'beatrixkiddo@outlook.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Beatrix',
                'lastname' => 'Kiddo',
                'coins' => 90,
                'username' => 'beatrixkiddo',
                'avatar' => NULL
            ],
            [
                'email' => 'vegeta@yahoo.com',
                'roles' => ["ROLE_USER"],
                'password' => 'password',
                'name' => 'Vegeta',
                'lastname' => 'Dragon Ball',
                'coins' => 100,
                'username' => 'vegeta',
                'avatar' => NULL
            ],
        ];

        foreach ($users as $key => $user) {
            $usr = new User();
            $usr->setEmail($user['email']);
            $usr->setRoles($user['roles']);
            $usr->setPassword(self::$passwordHasher->hashPassword($usr, 'galacticshadow'));
            $usr->setName($user['name']);
            $usr->setLastname($user['lastname']);
            $usr->setCoins($user['coins']);
            $usr->setUsername($user['username']);
            $avatar = $manager->getRepository(Image::class)
            ->createQueryBuilder('i')
            ->where('i.filename LIKE :filename')
            ->setParameter('filename', '%' . strtolower($user['name']) . '%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
            $usr->setAvatar($avatar);
            $manager->persist($usr);
        }
        $manager->flush();
    }
}