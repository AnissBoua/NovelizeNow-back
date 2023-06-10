<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Novel;
use App\Entity\Category;
use App\Entity\NovelImage;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class NovelFixture extends Fixture
{
    
    public function load(ObjectManager $manager)
    {
        $this->loadNovels($manager);
    }

    public static function loadNovels(ObjectManager $manager)
    {
        $novels = [
            [
                'title' => "The Last of Us: A Post-Apocalyptic Journey",
                'resume' => "In a world ravaged by a deadly fungal infection, survivors Joel and Ellie embark on a perilous cross-country journey to find a cure.",
                'slug' => "the-last-of-us-a-post-apocalyptic-journey",
                'price' => 29,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101425-cX6zA2vB3nM7-the-last-of-us.png',
                    'banner' => '20230518101425-cX6zA2vB3nM7-the-last-of-us-banner.png',
                ]
            ],
            [
                'title' => "Uncharted: Lost Treasures",
                'resume' => "Follow treasure hunter Nathan Drake as he unravels ancient mysteries and races against ruthless enemies in search of hidden treasures.",
                'slug' => "uncharted-lost-treasures",
                'price' => 24,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101435-eW3qA8s5dF2gH6-uncharted.png',
                    'banner' => '20230518101435-eW3qA8s5dF2gH6-uncharted-banner.png',
                ]
            ],
            [
                'title' => "The Witcher: The White Wolf",
                'resume' => "Geralt of Rivia, a monster hunter for hire, embarks on a perilous journey to find his adopted daughter and confront the secrets of his past.",
                'slug' => "the-witcher-the-white-wolf",
                'price' => 28,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101245-T5v6N1r4pA7Bke65-the-witcher.png',
                    'banner' => '20230518101245-T5v6N1r4pA7Bke65-the-witcher-banner.png',
                ]
            ],
            [
                'title' => "The Legend of Zelda: The Hero of Time",
                'resume' => "Join Link, a young adventurer, as he embarks on a quest to save the kingdom of Hyrule from the evil Ganondorf.",
                'slug' => "the-legend-of-zelda-the-hero-of-time",
                'price' => 25,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild.png',
                    'banner' => '20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild-banner.png',
                ]
            ],
            [
                'title' => "Super Mario: The Mushroom Kingdom",
                'resume' => "Join Mario, a plumber from Brooklyn, as he travels to the Mushroom Kingdom to rescue Princess Peach from the evil Bowser.",
                'slug' => "super-mario-the-mushroom-kingdom",
                'price' => 22,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101245-T5v6N1r4pA7Bke65-super-mario.png',
                    'banner' => '20230518101245-T5v6N1r4pA7Bke65-super-mario-banner.png',
                ]
            ],
            [
                'title' => "Final Fantasy: The Crystal Chronicles",
                'resume' => "Join a group of adventurers as they embark on a quest to save their world from the destructive Miasma.",
                'slug' => "final-fantasy-the-crystal-chronicles",
                'price' => 26,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101425-cX6zA2vB3nM7-final-fantasy-the-crystal-chronicles.png',
                    'banner' => '20230518101425-cX6zA2vB3nM7-final-fantasy-the-crystal-chronicles-banner.png',
                ]
            ],
            [
                'title' => "Persona: The Phantom Thieves",
                'resume' => "Join the Phantom Thieves, a group of high school students with the power to change the hearts of corrupt adults
                and steal their deepest secrets.",
                'slug' => "persona-the-phantom-thieves",
                'price' => 25,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101435-eW3qA8s5dF2gH6-persona.png',
                    'banner' => '20230518101435-eW3qA8s5dF2gH6-persona-banner.png',
                ]
            ],
            [
                'title' => "Dark Souls: The Age of Fire",
                'resume' => "Enter a dark and treacherous world where players must face unforgiving challenges, powerful bosses, and uncover the secrets of the Age of Fire.",
                'slug' => "dark-souls-the-age-of-fire",
                'price' => 27,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101455-d6eW3qA8s5dF2gH6-dark-souls.png',
                    'banner' => '20230518101455-d6eW3qA8s5dF2gH6-dark-souls-banner.png',
                ]
            ],
            [
                'title' => "Sekiro: Shadows of Vengeance",
                'resume' => "Become the one-armed wolf, a shinobi warrior, as you embark on a mission of revenge in feudal Japan filled with intense sword fights and supernatural foes.",
                'slug' => "sekiro-shadows-of-vengeance",
                'price' => 31,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101505-cX6zA2vB3nM7-sekiro.png',
                    'banner' => '20230518101505-cX6zA2vB3nM7-sekiro-banner.png',
                ]

            ],
            [
                'title' => "Red Dead Redemption: Outlaw's Redemption",
                'resume' => "Set in the wild west, outlaw Arthur Morgan's loyalty is tested as he confronts rival gangs, lawmen, and confronts his own inner demons.",
                'slug' => "red-dead-redemption-outlaws-redemption",
                'price' => 28,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101515-eW3qA8s5dF2gH6-red-dead-redemption.png',
                    'banner' => '20230518101515-eW3qA8s5dF2gH6-red-dead-redemption-banner.png',
                ]
            ],
            [
                'title' => "Fullmetal Alchemist Brotherhood: Alchemy's Legacy",
                'resume' => "Follow the Elric brothers, Edward and Alphonse, as they search for the Philosopher's Stone to restore their bodies in a world where alchemy rules.",
                'slug' => "fullmetal-alchemist-brotherhood-alchemys-legacy",
                'price' => 26,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101525-tY7uI4r6eW3qA8-full-metal-alchemist-brotherhood.png',
                    'banner' => '20230518101525-tY7uI4r6eW3qA8-full-metal-alchemist-brotherhood-banner.png',
                ]
            ],
            [
                'title' => "Hunter x Hunter: The Pursuit of Dreams",
                'resume' => "Gon Freecss embarks on a perilous journey to become a Hunter, encounter powerful adversaries, and uncover the secrets of his father's disappearance.",
                'slug' => "hunter-x-hunter-the-pursuit-of-dreams",
                'price' => 29,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101535-d6eW3qA8s5dF2gH6-hunter-x-hunter.png',
                    'banner' => '20230518101835-eW3qA8s5dF2gH6-hunter-x-hunter-banner.png',
                ]
            ],
            [
                'title' => "Fire Force: Inferno's Brigade",
                'resume' => "In a world where spontaneous human combustion turns people into living infernos, a special firefighting force battles against supernatural threats.",
                'slug' => "fire-force-infernos-brigade",
                'price' => 25,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101545-cX6zA2vB3nM7-fire-force.png',
                    'banner' => '20230518101545-cX6zA2vB3nM7-fire-force-banner.png',
                ]
            ],
            [
                'title' => "My Hero Academia: Rise of Heroes",
                'resume' => "In a society where most of the population possesses superpowers, young Izuku Midoriya strives to become the greatest hero and protect the innocent.",
                'slug' => "my-hero-academia-rise-of-heroes",
                'price' => 28,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101555-eW3qA8s5dF2gH6-my-hero-academia.png',
                    'banner' => '20230518101555-eW3qA8s5dF2gH6-my-hero-academia-banner.png',
                ]
            ],
            [
                'title' => "Magi: The Labyrinth of Magic",
                'resume' => "Follow the adventures of Alibaba, Aladdin, and Morgiana as they navigate a world of magic, treachery, and political intrigue.",
                'slug' => "magi-the-labyrinth-of-magic",
                'price' => 24,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101605-tY7uI4r6eW3qA8-magi.png',
                    'banner' => '20230518101605-tY7uI4r6eW3qA8-magi-banner.png',
                ]
            ],
            [
                'title' => "Ao Haru Ride: A Tale of Youthful Love",
                'resume' => "Futaba Yoshioka's life takes a turn when she reconnects with her middle school crush, Kou Tanaka, and they navigate the complexities of young love.",
                'slug' => "ao-haru-ride-a-tale-of-youthful-love",
                'price' => 23,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101615-d6eW3qA8s5dF2gH6-ao-haru-ride.png',
                    'banner' => '20230518101615-d6eW3qA8s5dF2gH6-ao-haru-ride-banner.png',
                ]
            ],
            [
                'title' => 'Gintama: Samurai Comedy Chronicles',
                'resume' => 'Join Gintoki Sakata, a lazy samurai, and his eccentric friends as they take on odd jobs and hilarious adventures in an alternate Edo period.',
                'slug' => 'gintama-samurai-comedy-chronicles',
                'price' => 26,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101625-cX6zA2vB3nM7-gintama.png',
                    'banner' => '20230518101625-cX6zA2vB3nM7-gintama-banner.png',
                ]
            ],
            [
                'title' => 'Vagabond: The Way of the Sword',
                'resume' => 'Based on the life of legendary swordsman Miyamoto Musashi, witness his journey of self-discovery and mastery of the sword.',
                'slug' => 'vagabond-the-way-of-the-sword',
                'price' => 33,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101635-eW3qA8s5dF2gH6-vagabond.png',
                    'banner' => '20230518101635-eW3qA8s5dF2gH6-vagabond-banner.png',
                ]
            ],
            [
                'title' => 'Attack on Titan: Walls of Desperation',
                'resume' => 'In a world where humanity is on the brink of extinction, follow Eren Yeager and his friends as they battle colossal titans and uncover the secrets behind their existence.',
                'slug' => 'attack-on-titan-walls-of-desperation',
                'price' => 26,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101655-d6eW3qA8s5dF2gH6-attack-on-titan.png',
                    'banner' => '20230518101655-d6eW3qA8s5dF2gH6-attack-on-titan-banner.png',
                ]
            ],
            [
                'title' => 'Death Note: The Power of the Death Note',
                'resume' => 'When a high school student named Light Yagami discovers a mysterious notebook with the power to kill anyone whose name is written in it, he embarks on a quest to create a new world free from criminals.',
                'slug' => 'death-note-the-power-of-the-death-note',
                'price' => 22,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101725-tY7uI4r6eW3qA8-death-note.png',
                    'banner' => '20230518101725-tY7uI4r6eW3qA8-death-note-banner.png',
                ]
            ],
            [
                'title' => 'Naruto: The Path of the Ninja',
                'resume' => 'Follow Naruto Uzumaki, a young ninja with a dream of becoming Hokage, as he trains, battles powerful enemies, and learns the value of friendship.',
                'slug' => 'naruto-the-path-of-the-ninja',
                'price' => 27,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101705-cX6zA2vB3nM7-naruto.png',
                    'banner' => '20230518101705-cX6zA2vB3nM7-naruto-banner.png',
                ]
            ],
            [
                'title' => 'Bleach: Soul Reaper Chronicles',
                'resume' => 'Ichigo Kurosaki, a teenager with the ability to see ghosts, becomes a Soul Reaper and protects the living world from malevolent spirits and otherworldly threats.',
                'slug' => 'bleach-soul-reaper-chronicles',
                'price' => 25,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101735-d6eW3qA8s5dF2gH6-bleach.png',
                    'banner' => '20230518101735-d6eW3qA8s5dF2gH6-bleach-banner.png',
                ]
            ],
            [
                'title' => 'Dragon Ball: The Journey of Goku',
                'resume' => 'Follow Goku, a young martial artist with superhuman strength, as he trains, battles powerful enemies, and protects the Earth from destruction.',
                'slug' => 'dragon-ball-the-journey-of-goku',
                'price' => 24,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101755-eW3qA8s5dF2gH6-dragon-ball.png',
                    'banner' => '20230518101755-eW3qA8s5dF2gH6-dragon-ball-banner.png',
                ]
            ],
            [
                'title' => 'One Piece: Pirate\'s Legacy',
                'resume' => 'Join Monkey D. Luffy and his crew, the Straw Hat Pirates, as they search for the ultimate treasure, the One Piece, and navigate the treacherous world of pirates.',
                'slug' => 'one-piece-pirates-legacy',
                'price' => 29,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101715-eW3qA8s5dF2gH6-one-piece.png',
                    'banner' => '20230518101715-eW3qA8s5dF2gH6-one-piece-banner.png',
                ]
            ],
            [
                'title' => 'Harry Potter and the Sorcerer\'s Stone',
                'resume' => 'Harry Potter, an orphaned wizard, discovers he is a famous wizard and enrolls in the Hogwarts School of Witchcraft and Wizardry, where he uncovers dark secrets and battles the dark wizard Lord Voldemort.',
                'slug' => 'harry-potter-and-the-sorcerers-stone',
                'price' => 20,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101915-eW3qA8s5dF2gH6-harry-potter.png',
                    'banner' => '20230518101915-eW3qA8s5dF2gH6-harry-potter-banner.png',
                ]
            ],
            [
                'title' => 'The Lord of the Rings: The Fellowship of the Ring',
                'resume' => 'Frodo Baggins embarks on a perilous journey to destroy the One Ring and save Middle-earth from the evil forces of Sauron, accompanied by a fellowship of diverse characters.',
                'slug' => 'the-lord-of-the-rings-the-fellowship-of-the-ring',
                'price' => 22,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101915-eW3qA8s5dF2gH6-the-lord-of-the-rings.png',
                    'banner' => '20230518101915-eW3qA8s5dF2gH6-the-lord-of-the-rings-banner.png',
                ]
            ],
            [
                'title' => '1917',
                'resume' => 'In a totalitarian society ruled by Big Brother, Winston Smith rebels against the oppressive regime and falls in love with Julia, leading to dire consequences.',
                'slug' => '1917',
                'price' => 17,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101305-cX6zA2vB3nM7-1917.png',
                    'banner' => '20230518101305-cX6zA2vB3nM7-1917-banner.png',
                ]
            ],
            [
                'title' => 'The Hobbit',
                'resume' => 'Bilbo Baggins, a reluctant hobbit, embarks on an epic adventure to help a group of dwarves reclaim their homeland from the fearsome dragon Smaug.',
                'slug' => 'the-hobbit',
                'price' => 16,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101405-tY7uI4r6eW3qA8-the-hobbit.png',
                    'banner' => '20230518101405-tY7uI4r6eW3qA8-the-hobbit-banner.png',
                ]
            ],
            [
                'title' => 'The Chronicles of Narnia: The Lion, the Witch, and the Wardrobe',
                'resume' => 'Four siblings stumble upon a magical wardrobe that transports them to the enchanting land of Narnia, where they join forces with the lion Aslan to defeat the White Witch and restore peace.',
                'slug' => 'the-chronicles-of-narnia-the-lion-the-witch-and-the-wardrobe',
                'price' => 22,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101125-l2kS5dF8gH6jM3-the-chronicles-of-narnia.png',
                    'banner' => '20230518101125-l2kS5dF8gH6jM3-the-chronicles-of-narnia-banner.png',
                ]
            ],
            [
                'title' => 'The Hunger Games: Arena of Survival',
                'resume' => 'In a dystopian future, Katniss Everdeen volunteers for the Hunger Games, a televised fight to the death, to protect her sister and challenge the oppressive Capitol.',
                'slug' => 'the-hunger-games-arena-of-survival',
                'price' => 21,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101335-d6eW3qA8s5dF2gH6-the-hunger-games.png',
                    'banner' => '20230518101335-d6eW3qA8s5dF2gH6-the-hunger-games-banner.png',
                ]
            ],
            [
                'title' => "The Fault in Our Stars",
                'resume' => "Hazel Grace Lancaster and Augustus Waters, two teenagers battling cancer, embark on a poignant and heartbreaking journey of love, hope, and mortality.",
                'slug' => "the-fault-in-our-stars",
                'price' => 16,
                'status' => 'published',
                'created_at' => new \DateTime(),
                'images' => [
                    'cover' => '20230518101045-q6wE9r2tY7uI4-the-fault-in-our-stars.png',
                    'banner' => '20230518101045-q6wE9r2tY7uI4-the-fault-in-our-stars-banner.png',
                ]
            ],
        ];

        $categories = $manager->getRepository(Category::class)->findAll();
        foreach ($novels as $key => $novel) {
            $nCategories = rand(1, 3);
            $nov =  new Novel();
            $nov->setTitle($novel['title']);
            $nov->setResume($novel['resume']);
            $nov->setSlug($novel['slug']);
            $nov->setPrice($novel['price']);
            $nov->setStatus($novel['status']);
            $nov->setDateCreation($novel['created_at']);
            for ($i = 0; $i < $nCategories; $i++) {
                $nov->addCategory($categories[array_rand($categories)]);
            }

            foreach ($novel['images'] as $key => $image) {
                $img = $manager->getRepository(Image::class)->findOneBy(['filename' => $image]);
                $novelImage = new NovelImage();
                $novelImage->setNovel($nov);
                $novelImage->setImage($img);
                $novelImage->setImgPosition($key);
                $manager->persist($novelImage);
            }
            $manager->persist($nov);
        }
        $manager->flush();
    }
}