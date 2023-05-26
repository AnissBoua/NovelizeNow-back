<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Like;
use App\Entity\Page;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Novel;
use App\Entity\Offer;
use App\Entity\Chapter;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\UserNovel;
use App\Entity\NovelImage;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class NovelizeFixtures extends Fixture
{
    protected $faker;

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadCategories($manager);
        $this->loadImages($manager);
        $this->loadNovels($manager);
        $this->loadUsers($manager, $this->passwordHasher);
        $this->loadUserNovels($manager);
        $this->loadOffers($manager);
        $this->loadChapters($manager);
        $this->loadLikes($manager);
        $this->loadComments($manager);
    }

    private function loadCategories(ObjectManager $manager) {
        $categories = [
            "SCI-FI",
            "Fantastic",
            "Romance",
            "Mystery",
            "Humourous",
            "Horror",
            "Action",
            "Adventure",
            "Thriller",
            "Drama",
            "Comedy",
            "Animation",
            "Family",
            "Crime",
            "Historical",
            "Fantasy",
            "Documentary",
            "War",
            "Musical",
            "Biography"
        ];
        foreach ($categories as $key => $categorie) {
            $cat = new Category();
            $cat->setName($categorie);
            $manager->persist($cat);
        }
        $manager->flush();
    }

    private function loadImages(ObjectManager $manager) {
        $images = [
            ['filename' => '20230518101045-q6wE9r2tY7uI4-the-fault-in-our-stars.png', 'filepath' => '/uploads/novels/20230518101045-q6wE9r2tY7uI4-the-fault-in-our-stars.png'],
            ['filename' => '20230518101045-q6wE9r2tY7uI4-the-fault-in-our-stars-banner.png', 'filepath' => '/uploads/novels/20230518101045-q6wE9r2tY7uI4-the-fault-in-our-stars-banner.png'],
            ['filename' => '20230518101125-l2kS5dF8gH6jM3-the-chronicles-of-narnia.png','filepath' => '/uploads/novels/20230518101125-l2kS5dF8gH6jM3-the-chronicles-of-narnia.png'],
            ['filename' => '20230518101125-l2kS5dF8gH6jM3-the-chronicles-of-narnia-banner.png', 'filepath' => '/uploads/novels/20230518101125-l2kS5dF8gH6jM3-the-chronicles-of-narnia-banner.png'],
            ['filename' => '20230518101305-cX6zA2vB3nM7-1917.png', 'filepath' => '/uploads/novels/20230518101305-cX6zA2vB3nM7-1917.png'],
            ['filename' => '20230518101305-cX6zA2vB3nM7-1917-banner.png', 'filepath' => '/uploads/novels/20230518101305-cX6zA2vB3nM7-1917-banner.png'],
            ['filename' => '20230518101335-d6eW3qA8s5dF2gH6-the-hunger-games.png', 'filepath' => '/uploads/novels/20230518101335-d6eW3qA8s5dF2gH6-the-hunger-games.png'],
            ['filename' => '20230518101335-d6eW3qA8s5dF2gH6-the-hunger-games-banner.png', 'filepath' => '/uploads/novels/20230518101335-d6eW3qA8s5dF2gH6-the-hunger-games-banner.png'],
            ['filename' => '20230518101405-tY7uI4r6eW3qA8-the-hobbit.png', 'filepath' => '/uploads/novels/20230518101405-tY7uI4r6eW3qA8-the-hobbit.png'],
            ['filename' => '20230518101405-tY7uI4r6eW3qA8-the-hobbit-banner.png', 'filepath' => '/uploads/novels/20230518101405-tY7uI4r6eW3qA8-the-hobbit-banner.png'],
            ['filename' => '20230518101425-cX6zA2vB3nM7-the-last-of-us.png','filepath' => '/uploads/novels/20230518101425-cX6zA2vB3nM7-the-last-of-us.png'],
            ['filename' => '20230518101425-cX6zA2vB3nM7-the-last-of-us-banner.png', 'filepath' => '/uploads/novels/20230518101425-cX6zA2vB3nM7-the-last-of-us-banner.png'],
            ['filename' => '20230518101425-cX6zA2vB3nM7-final-fantasy-the-crystal-chronicles.png', 'filepath' => '/uploads/novels/20230518101425-cX6zA2vB3nM7-final-fantasy-the-crystal-chronicles.png'],
            ['filename' => '20230518101425-cX6zA2vB3nM7-final-fantasy-the-crystal-chronicles-banner.png', 'filepath' => '/uploads/novels/20230518101425-cX6zA2vB3nM7-final-fantasy-the-crystal-chronicles-banner.png'],
            ['filename' => '20230518101435-eW3qA8s5dF2gH6-uncharted.png', 'filepath' => '/uploads/novels/20230518101435-eW3qA8s5dF2gH6-uncharted.png'],
            ['filename' => '20230518101435-eW3qA8s5dF2gH6-uncharted-banner.png', 'filepath' => '/uploads/novels/20230518101435-eW3qA8s5dF2gH6-uncharted-banner.png'],
            ['filename' => '20230518101435-eW3qA8s5dF2gH6-persona.png', 'filepath' => '/uploads/novels/20230518101435-eW3qA8s5dF2gH6-persona.png'],
            ['filename' => '20230518101435-eW3qA8s5dF2gH6-persona-banner.png', 'filepath' => '/uploads/novels/20230518101435-eW3qA8s5dF2gH6-persona-banner.png'],
            ['filename' => '20230518101455-d6eW3qA8s5dF2gH6-dark-souls.png', 'filepath' => '/uploads/novels/20230518101455-d6eW3qA8s5dF2gH6-dark-souls.png'],
            ['filename' => '20230518101455-d6eW3qA8s5dF2gH6-dark-souls-banner.png', 'filepath' => '/uploads/novels/20230518101455-d6eW3qA8s5dF2gH6-dark-souls-banner.png'],
            ['filename' => '20230518101505-cX6zA2vB3nM7-sekiro.png', 'filepath' => '/uploads/novels/20230518101505-cX6zA2vB3nM7-sekiro.png'],
            ['filename' => '20230518101505-cX6zA2vB3nM7-sekiro-banner.png', 'filepath' => '/uploads/novels/20230518101505-cX6zA2vB3nM7-sekiro-banner.png'],
            ['filename' => '20230518101515-eW3qA8s5dF2gH6-red-dead-redemption.png', 'filepath' => '/uploads/novels/20230518101515-eW3qA8s5dF2gH6-red-dead-redemption.png'],
            ['filename' => '20230518101515-eW3qA8s5dF2gH6-red-dead-redemption-banner.png', 'filepath' => '/uploads/novels/20230518101515-eW3qA8s5dF2gH6-red-dead-redemption-banner.png'],
            ['filename' => '20230518101525-tY7uI4r6eW3qA8-full-metal-alchemist-brotherhood.png', 'filepath' => '/uploads/novels/20230518101525-tY7uI4r6eW3qA8-full-metal-alchemist-brotherhood.png'],
            ['filename' => '20230518101525-tY7uI4r6eW3qA8-full-metal-alchemist-brotherhood-banner.png', 'filepath' => '/uploads/novels/20230518101525-tY7uI4r6eW3qA8-full-metal-alchemist-brotherhood-banner.png'],
            ['filename' => '20230518101535-d6eW3qA8s5dF2gH6-hunter-x-hunter.png', 'filepath' => '/uploads/novels/20230518101535-d6eW3qA8s5dF2gH6-hunter-x-hunter.png'],
            ['filename' => '20230518101835-eW3qA8s5dF2gH6-hunter-x-hunter-banner.png', 'filepath' => '/uploads/novels/20230518101835-eW3qA8s5dF2gH6-hunter-x-hunter-banner.png'],
            ['filename' => '20230518101545-cX6zA2vB3nM7-fire-force.png', 'filepath' => '/uploads/novels/20230518101545-cX6zA2vB3nM7-fire-force.png'],
            ['filename' => '20230518101545-cX6zA2vB3nM7-fire-force-banner.png', 'filepath' => '/uploads/novels/20230518101545-cX6zA2vB3nM7-fire-force-banner.png'],
            ['filename' => '20230518101555-eW3qA8s5dF2gH6-my-hero-academia.png', 'filepath' => '/uploads/novels/20230518101555-eW3qA8s5dF2gH6-my-hero-academia.png'],
            ['filename' => '20230518101555-eW3qA8s5dF2gH6-my-hero-academia-banner.png', 'filepath' => '/uploads/novels/20230518101555-eW3qA8s5dF2gH6-my-hero-academia-banner.png'],
            ['filename' => '20230518101605-tY7uI4r6eW3qA8-magi.png', 'filepath' => '/uploads/novels/20230518101605-tY7uI4r6eW3qA8-magi.png'],
            ['filename' => '20230518101605-tY7uI4r6eW3qA8-magi-banner.png', 'filepath' => '/uploads/novels/20230518101605-tY7uI4r6eW3qA8-magi-banner.png'],
            ['filename' => '20230518101615-d6eW3qA8s5dF2gH6-ao-haru-ride.png', 'filepath' => '/uploads/novels/20230518101615-d6eW3qA8s5dF2gH6-ao-haru-ride.png'],
            ['filename' => '20230518101615-d6eW3qA8s5dF2gH6-ao-haru-ride-banner.png', 'filepath' => '/uploads/novels/20230518101615-d6eW3qA8s5dF2gH6-ao-haru-ride-banner.png'],
            ['filename' => '20230518101625-cX6zA2vB3nM7-gintama.png', 'filepath' => '/uploads/novels/20230518101625-cX6zA2vB3nM7-gintama.png'],
            ['filename' => '20230518101625-cX6zA2vB3nM7-gintama-banner.png', 'filepath' => '/uploads/novels/20230518101625-cX6zA2vB3nM7-gintama-banner.png'],
            ['filename' => '20230518101635-eW3qA8s5dF2gH6-vagabond.png', 'filepath' => '/uploads/novels/20230518101635-eW3qA8s5dF2gH6-vagabond.png'],
            ['filename' => '20230518101635-eW3qA8s5dF2gH6-vagabond-banner.png', 'filepath' => '/uploads/novels/20230518101635-eW3qA8s5dF2gH6-vagabond-banner.png'],
            ['filename' => '20230518101655-d6eW3qA8s5dF2gH6-attack-on-titan.png', 'filepath' => '/uploads/novels/20230518101655-d6eW3qA8s5dF2gH6-attack-on-titan.png'],
            ['filename' => '20230518101655-d6eW3qA8s5dF2gH6-attack-on-titan-banner.png', 'filepath' => '/uploads/novels/20230518101655-d6eW3qA8s5dF2gH6-attack-on-titan-banner.png'],
            ['filename' => '20230518101705-cX6zA2vB3nM7-naruto.png', 'filepath' => '/uploads/novels/20230518101705-cX6zA2vB3nM7-naruto.png'], // ici
            ['filename' => '20230518101705-cX6zA2vB3nM7-naruto-banner.png', 'filepath' => '/uploads/novels/20230518101705-cX6zA2vB3nM7-naruto-banner.png'],
            ['filename' => '20230518101715-eW3qA8s5dF2gH6-one-piece.png', 'filepath' => '/uploads/novels/20230518101715-eW3qA8s5dF2gH6-one-piece.png'],
            ['filename' => '20230518101715-eW3qA8s5dF2gH6-one-piece-banner.png', 'filepath' => '/uploads/novels/20230518101715-eW3qA8s5dF2gH6-one-piece-banner.png'],
            ['filename' => '20230518101725-tY7uI4r6eW3qA8-death-note.png', 'filepath' => '/uploads/novels/20230518101725-tY7uI4r6eW3qA8-death-note.png'],
            ['filename' => '20230518101725-tY7uI4r6eW3qA8-death-note-banner.png', 'filepath' => '/uploads/novels/20230518101725-tY7uI4r6eW3qA8-death-note-banner.png'],
            ['filename' => '20230518101735-d6eW3qA8s5dF2gH6-bleach.png', 'filepath' => '/uploads/novels/20230518101735-d6eW3qA8s5dF2gH6-bleach.png'],
            ['filename' => '20230518101735-d6eW3qA8s5dF2gH6-bleach-banner.png', 'filepath' => '/uploads/novels/20230518101735-d6eW3qA8s5dF2gH6-bleach-banner.png'],
            ['filename' => '20230518101755-eW3qA8s5dF2gH6-dragon-ball.png', 'filepath' => '/uploads/novels/20230518101755-eW3qA8s5dF2gH6-dragon-ball.png'],
            ['filename' => '20230518101755-eW3qA8s5dF2gH6-dragon-ball-banner.png', 'filepath' => '/uploads/novels/20230518101755-eW3qA8s5dF2gH6-dragon-ball-banner.png'],
            ['filename' => '20230518101805-tY7uI4r6eW3qA8-demon-slayer.png', 'filepath' => '/uploads/novels/20230518101805-tY7uI4r6eW3qA8-demon-slayer.png'],
            ['filename' => '20230518101815-d6eW3qA8s5dF2gH6-my-neighbor-totoro.png', 'filepath' => '/uploads/novels/20230518101815-d6eW3qA8s5dF2gH6-my-neighbor-totoro.png'],
            ['filename' => '20230518101905-cX6zA2vB3nM7-the-promised-neverland.png', 'filepath' => '/uploads/novels/20230518101905-cX6zA2vB3nM7-the-promised-neverland.png'],
            ['filename' => '20230518101915-eW3qA8s5dF2gH6-harry-potter.png', 'filepath' => '/uploads/novels/20230518101915-eW3qA8s5dF2gH6-harry-potter.png'],
            ['filename' => '20230518101915-eW3qA8s5dF2gH6-harry-potter-banner.png', 'filepath' => '/uploads/novels/20230518101915-eW3qA8s5dF2gH6-harry-potter-banner.png'],
            ['filename' => '20230518101915-eW3qA8s5dF2gH6-the-lord-of-the-rings.png', 'filepath' => '/uploads/novels/20230518101915-eW3qA8s5dF2gH6-the-lord-of-the-rings.png'],
            ['filename' => '20230518101915-eW3qA8s5dF2gH6-the-lord-of-the-rings-banner.png', 'filepath' => '/uploads/novels/20230518101915-eW3qA8s5dF2gH6-the-lord-of-the-rings-banner.png'],
            ['filename' => '20230518101010-L5t9P1q2tG7f4eP9-ffvii-cloud-strife.png', 'filepath' => '/uploads/avatars/20230518101010-L5t9P1q2tG7f4eP9-ffvii-cloud-strife.png'],
            ['filename' => '20230518101015-A5w7O0p2bC9yQ3nL-harry-potter-the-boy-who-lived.png', 'filepath' => '/uploads/avatars/20230518101015-A5w7O0p2bC9yQ3nL-harry-potter-the-boy-who-lived.png'],
            ['filename' => '20230518101020-T8k4J3z5uS0hG6fB-iron-man-stark-industries.png', 'filepath' => '/uploads/avatars/20230518101020-T8k4J3z5uS0hG6fB-iron-man-stark-industries.png'],
            ['filename' => '20230518101030-H6e1F3y7dN2mK4pL-naruto-uzumaki.png', 'filepath' => '/uploads/avatars/20230518101030-H6e1F3y7dN2mK4pL-naruto-uzumaki.png'],
            ['filename' => '20230518101035-M7n4R9x3dW8gB5zH-lara-croft-tomb-raider.png', 'filepath' => '/uploads/avatars/20230518101035-M7n4R9x3dW8gB5zH-lara-croft-tomb-raider.png'],
            ['filename' => '20230518101045-V3t1E8p5kN2cH4jG-hermione-granger.png', 'filepath' => '/uploads/avatars/20230518101045-V3t1E8p5kN2cH4jG-hermione-granger.png'],
            ['filename' => '20230518101055-P3q8M2h5dC9vN4rA-aragon.png', 'filepath' => '/uploads/avatars/20230518101055-P3q8M2h5dC9vN4rA-aragon.png'],
            ['filename' => '20230518101100-H5t9N1q7pM8rC3vA-alucard-castlevania.png', 'filepath' => '/uploads/avatars/20230518101100-H5t9N1q7pM8rC3vA-alucard-castlevania.png'],
            ['filename' => '20230518101105-G7h2D4m9rP3vC5tA-yuna-final-fantasy.png', 'filepath' => '/uploads/avatars/20230518101105-G7h2D4m9rP3vC5tA-yuna-final-fantasy.png'],
            ['filename' => '20230518101110-B2c8F6m4hT7vN9rA-edward-elric.png', 'filepath' => '/uploads/avatars/20230518101110-B2c8F6m4hT7vN9rA-edward-elric.png'],
            ['filename' => '20230518101115-M9r3P7q5tC8hD2vA-gon-freecss.png', 'filepath' => '/uploads/avatars/20230518101115-M9r3P7q5tC8hD2vA-gon-freecss.png'],
            ['filename' => '20230518101120-K1p5Q3n7lW4mV9rA-levi-ackerman.png', 'filepath' => '/uploads/avatars/20230518101120-K1p5Q3n7lW4mV9rA-levi-ackerman.png'],
            ['filename' => '20230518101125-A4f8G7t1y2qN9rC3-gats-the-berserker.png', 'filepath' => '/uploads/avatars/20230518101125-A4f8G7t1y2qN9rC3-gats-the-berserker.png'],
            ['filename' => '20230518101140-H6e1F3y7dN2mK4pA-totoro.png', 'filepath' => '/uploads/avatars/20230518101140-H6e1F3y7dN2mK4pA-totoro.png'],
            ['filename' => '20230518101145-D5g9H1s3bL7pN4zA-jonathan-joestar.png', 'filepath' => '/uploads/avatars/20230518101145-D5g9H1s3bL7pN4zA-jonathan-joestar.png'],
            ['filename' => '20230518101150-V4t1E7p2kN9cH3zA-lucy-scarlett.png', 'filepath' => '/uploads/avatars/20230518101150-V4t1E7p2kN9cH3zA-lucy-scarlett.png'],
            ['filename' => '20230518101155-Y6j2H4c9tE1vN7qA-eren-yeager.png', 'filepath' => '/uploads/avatars/20230518101155-Y6j2H4c9tE1vN7qA-eren-yeager.png'],
            ['filename' => '20230518101200-T9k5J3z7uS0hB6fA-ichigo-kurosaki.png', 'filepath' => '/uploads/avatars/20230518101200-T9k5J3z7uS0hB6fA-ichigo-kurosaki.png'],
            ['filename' => '20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild.png', 'filepath' => '/uploads/novels/20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild.png'],
            ['filename' => '20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild-banner.png', 'filepath' => '/uploads/novels/20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild-banner.png'],
            ['filename' => '20230518101215-F4u5B8h2rG3wP9mA-spike-spiegel.png', 'filepath' => '/uploads/avatars/20230518101215-F4u5B8h2rG3wP9mA-spike-spiegel.png'], // ici
            ['filename' => '20230518101220-G2d5M9n4tB8rP7hA-sora-kingdom-hearts.png', 'filepath' => '/uploads/avatars/20230518101220-G2d5M9n4tB8rP7hA-sora-kingdom-hearts.png'],
            ['filename' => '20230518101225-V3t1E8p5kN2cH4jA-geralt-of-rivia.png', 'filepath' => '/uploads/avatars/20230518101225-V3t1E8p5kN2cH4jA-geralt-of-rivia.png'],
            ['filename' => '20230518101230-S6j8G2t9yA1qN4vA-nezuko-kamado.png', 'filepath' => '/uploads/avatars/20230518101230-S6j8G2t9yA1qN4vA-nezuko-kamado.png'],
            ['filename' => '20230518101235-C9h2D4t7vM3qN5rA-mikasa-ackerman.png', 'filepath' => '/uploads/avatars/20230518101235-C9h2D4t7vM3qN5rA-mikasa-ackerman.png'],
            ['filename' => '20230518101245-T5v6N1r4pA7Bke65-the-witcher.png', 'filepath' => '/uploads/novels/20230518101245-T5v6N1r4pA7Bke65-the-witcher.png'],
            ['filename' => '20230518101245-T5v6N1r4pA7Bke65-the-witcher-banner.png', 'filepath' => '/uploads/novels/20230518101245-T5v6N1r4pA7Bke65-the-witcher-banner.png'],
            ['filename' => '20230518101245-T5v6N1r4pA7Bke65-super-mario.png', 'filepath' => '/uploads/novels/20230518101245-T5v6N1r4pA7Bke65-super-mario.png'],
            ['filename' => '20230518101245-T5v6N1r4pA7Bke65-super-mario-banner.png', 'filepath' => '/uploads/novels/20230518101245-T5v6N1r4pA7Bke65-super-mario-banner.png'],
        ];

        foreach ($images as $key => $image) {
            $img = new Image();
            $img->setFilename($image['filename']);
            $img->setFilepath($image['filepath']);
            $manager->persist($img);
        }
        $manager->flush();
    }

    private function loadNovels(ObjectManager $manager) {
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

    private function loadUsers(ObjectManager $manager, UserPasswordHasherInterface $passwordHasher) {
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
            $usr->setPassword($passwordHasher->hashPassword($usr, 'galacticshadow'));
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

    private function loadUserNovels(ObjectManager $manager) {
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

    private function loadOffers(ObjectManager $manager) {
        $offers = [
            [
                'name' => 'Basic',
                'coins' => 450,
                'price' => 5,
                'active' => true,
            ],
            [
                'name' => 'Standard',
                'coins' => 1200,
                'price' => 10,
                'active' => true
            ],
            [
                'name' => 'Premium',
                'coins' => 2500,
                'price' => 20,
                'active' => true
            ],
            [
                'name' => 'Ultimate',
                'coins' => 8000,
                'price' => 50,
                'active' => true
            ]
        ];

        foreach ($offers as $key => $offer) {
            $offerObj = new Offer();
            $offerObj->setName($offer['name']);
            $offerObj->setCoins($offer['coins']);
            $offerObj->setPrice($offer['price']);
            $offerObj->setActive($offer['active']);
            $manager->persist($offerObj);
        }
        $manager->flush();
    }

    private function loadChapters(ObjectManager $manager) {
        $chapters = [
            [
                'novel' => 'the-last-of-us-a-post-apocalyptic-journey',
                'chapters' => [
                    [
                        'title' => 'Prologue: A World Collapses',
                        'status' => 'published',
                        'page_state' => [
                            [
                                'content' => "The sun's warm embrace painted the city streets with hues of amber and gold. Life unfolded in a rhythm of familiarity, oblivious to the impending storm lurking on the horizon. People went about their daily routines, immersed in the mundane patterns of their lives. Children laughed and played, their innocence untarnished by the darkness that loomed.

                                In the heart of the city, a coffee shop hummed with activity. The aromatic scent of freshly brewed coffee wafted through the air, blending with the gentle chatter and clinking of cups. Patrons sat at tables, engrossed in conversation, their smiles and laughter filling the cozy space.
                                
                                Outside, a gentle breeze rustled through the trees, carrying with it a sense of tranquility. Couples strolled hand-in-hand along the boulevard, their laughter mingling with the distant sounds of traffic. It was a moment frozen in time, a snapshot of a world teetering on the edge of change.
                                
                                Unbeknownst to them, the threads of fate were already weaving a tapestry of turmoil. Unseen forces stirred in the shadows, threatening to unravel the delicate fabric of society. The calmness of this moment, the simplicity of these interactions, would soon give way to a torrent of chaos and despair.

                                But for now, life went on, blissfully unaware of the storm gathering strength just beyond the horizon. The city continued to breathe, its heart pulsating with the rhythm of everyday existence. It was a fleeting respite, a brief intermission before the world collapsed.

                                Little did they know, this would be the last day they would recognize as normal. The tranquility they took for granted would soon be shattered, leaving behind a scarred and desolate landscape. The world was about to change, and they would be swept along in its merciless tide.",
                                'html' => "
                                <p>The sun's warm embrace painted the city streets with hues of amber and gold. Life unfolded in a rhythm of familiarity, oblivious to the impending storm lurking on the horizon. People went about their daily routines, immersed in the mundane patterns of their lives. Children laughed and played, their innocence untarnished by the darkness that loomed.</p>

                                <p>In the heart of the city, a coffee shop hummed with activity. The aromatic scent of freshly brewed coffee wafted through the air, blending with the gentle chatter and clinking of cups. Patrons sat at tables, engrossed in conversation, their smiles and laughter filling the cozy space.</p>
                            
                                <p>Outside, a gentle breeze rustled through the trees, carrying with it a sense of tranquility. Couples strolled hand-in-hand along the boulevard, their laughter mingling with the distant sounds of traffic. It was a moment frozen in time, a snapshot of a world teetering on the edge of change.</p>
                            
                                <p>Unbeknownst to them, the threads of fate were already weaving a tapestry of turmoil. Unseen forces stirred in the shadows, threatening to unravel the delicate fabric of society. The calmness of this moment, the simplicity of these interactions, would soon give way to a torrent of chaos and despair.</p>
                            
                                <p>But for now, life went on, blissfully unaware of the storm gathering strength just beyond the horizon. The city continued to breathe, its heart pulsating with the rhythm of everyday existence. It was a fleeting respite, a brief intermission before the world collapsed.</p>
                            
                                <p>Little did they know, this would be the last day they would recognize as normal. The tranquility they took for granted would soon be shattered, leaving behind a scarred and desolate landscape. The world was about to change, and they would be swept along in its merciless tide.</p>"
                            ],
                            [
                                'content' => "Yet, beneath the surface, signs of unrest lingered. News reports buzzed with murmurs of a mysterious illness spreading rapidly. Whispers echoed through crowded streets, as worried glances were exchanged. Supermarkets saw a surge in panicked shoppers, clearing shelves of supplies, as if preparing for an impending storm.

                                The once bright and lively city now carried an air of unease. Strangers cast wary looks at one another, their trust eroding with each passing day. Rumors swirled like a gathering storm, infiltrating every conversation. Fear settled deep within the hearts of the citizens, its weight palpable in the silence that fell upon the city after nightfall.
                                
                                Authorities attempted to maintain an image of control, but cracks began to show in their façade. Press conferences were held, promises made, but doubts persisted. The truth seemed to slip through their fingers, replaced by official statements that failed to quell the rising tide of anxiety.
                                
                                People began to isolate themselves, seeking refuge within the walls of their homes. Windows remained shut, curtains drawn, as if shutting out the impending chaos. The streets, once vibrant and teeming with life, grew eerily empty. A sense of foreboding settled upon the city, an unspoken understanding that the storm was approaching, ready to wreak havoc upon their lives.",
                                'html' => "
                                <p>Yet, beneath the surface, signs of unrest lingered. News reports buzzed with murmurs of a mysterious illness spreading rapidly. Whispers echoed through crowded streets, as worried glances were exchanged. Supermarkets saw a surge in panicked shoppers, clearing shelves of supplies, as if preparing for an impending storm.</p>

                                <p>The once bright and lively city now carried an air of unease. Strangers cast wary looks at one another, their trust eroding with each passing day. Rumors swirled like a gathering storm, infiltrating every conversation. Fear settled deep within the hearts of the citizens, its weight palpable in the silence that fell upon the city after nightfall.</p>

                                <p>Authorities attempted to maintain an image of control, but cracks began to show in their façade. Press conferences were held, promises made, but doubts persisted. The truth seemed to slip through their fingers, replaced by official statements that failed to quell the rising tide of anxiety.</p>

                                <p>People began to isolate themselves, seeking refuge within the walls of their homes. Windows remained shut, curtains drawn, as if shutting out the impending chaos. The streets, once vibrant and teeming with life, grew eerily empty. A sense of foreboding settled upon the city, an unspoken understanding that the storm was approaching, ready to wreak havoc upon their lives.</p>"
                            ],
                            [
                                'content' => "And then, like a thunderclap, chaos erupted. The once familiar streets turned into scenes of pandemonium. Panic spread like wildfire as reports of violent attacks and uncontrollable aggression flooded the airwaves. Hospitals overflowed with the infected, their desperate cries reverberating through the halls.

                                The news painted a grim picture of a rapidly spreading outbreak. Medical experts struggled to identify the source and nature of the illness, grappling with an enemy that seemed to defy conventional understanding. The infected became a constant threat, their presence a menacing shadow that loomed around every corner.
                                
                                Emergency services strained to keep up with the growing demand. Ambulances raced through crowded streets, sirens wailing, while overwhelmed hospitals turned away patients due to lack of space and resources. Desperate pleas for help filled the phone lines, but the system was buckling under the weight of the crisis.
                                
                                Amidst the chaos, brave healthcare workers fought tirelessly on the front lines. Exhausted and facing unimaginable risks, they battled to save lives, often at the cost of their own well-being. Their selflessness and dedication became a beacon of hope, shining through the darkness that engulfed the city.

                                Outside the healthcare facilities, ordinary people grappled with their own fear and uncertainty. Lockdowns and quarantine measures were implemented, confining individuals to the confines of their homes. The once vibrant city now stood still, its vibrant pulse replaced by an eerie silence.

                                Neighbors exchanged anxious glances, their eyes reflecting a mix of concern and trepidation. Trust in fellow humans diminished as paranoia took hold. It became a time of suspicion and self-preservation, as people retreated into their homes, barricading themselves against an invisible enemy.",
                                'html' => "
                                <p>And then, like a thunderclap, chaos erupted. The once familiar streets turned into scenes of pandemonium. Panic spread like wildfire as reports of violent attacks and uncontrollable aggression flooded the airwaves. Hospitals overflowed with the infected, their desperate cries reverberating through the halls.</p>
                                
                                <p>The news painted a grim picture of a rapidly spreading outbreak. Medical experts struggled to identify the source and nature of the illness, grappling with an enemy that seemed to defy conventional understanding. The infected became a constant threat, their presence a menacing shadow that loomed around every corner.</p>
                                
                                <p>Emergency services strained to keep up with the growing demand. Ambulances raced through crowded streets, sirens wailing, while overwhelmed hospitals turned away patients due to lack of space and resources. Desperate pleas for help filled the phone lines, but the system was buckling under the weight of the crisis.</p>
                                
                                <p>Amidst the chaos, brave healthcare workers fought tirelessly on the front lines. Exhausted and facing unimaginable risks, they battled to save lives, often at the cost of their own well-being. Their selflessness and dedication became a beacon of hope, shining through the darkness that engulfed the city.</p>
                                
                                <p>Outside the healthcare facilities, ordinary people grappled with their own fear and uncertainty. Lockdowns and quarantine measures were implemented, confining individuals to the confines of their homes. The once vibrant city now stood still, its vibrant pulse replaced by an eerie silence.</p>
                                
                                <p>Neighbors exchanged anxious glances, their eyes reflecting a mix of concern and trepidation. Trust in fellow humans diminished as paranoia took hold. It became a time of suspicion and self-preservation, as people retreated into their homes, barricading themselves against an invisible enemy.</p>"
                            ],
                            [
                                'content' => "Sirens blared, drowning out the wails of terrified citizens. Emergency services raced through the streets, their lights piercing the darkness, but their efforts seemed futile against the onslaught of chaos. The once orderly city descended into a maelstrom of panic and confusion.

                                News broadcasts became a constant stream of updates, each report more alarming than the last. Speculation ran rampant as theories circulated about the cause and possible containment of the outbreak. Misinformation mingled with genuine concern, amplifying the collective anxiety gripping the city.
                                
                                Supermarkets and stores, once pillars of stability, transformed into battlegrounds for survival. Shelves were stripped bare within hours as people scrambled to stockpile supplies. Shopping carts collided, tempers flared, and the desperate fought for every last can of food or bottle of water.
                                Amidst the frenzy, individuals tried to make sense of the rapidly changing world. Friends and family gathered in hushed conversations, sharing rumors and strategies for staying safe. Each day brought new challenges and unforeseen dangers, forcing them to adapt to a reality that seemed to unravel with every passing moment.

                                The authorities, burdened by the sheer scale of the crisis, struggled to maintain control. Their communications became sporadic, leaving citizens to fend for themselves. The absence of clear directives and guidance only fueled the chaos, as people grappled with the weight of their own decisions.

                                In the midst of this turmoil, acts of both compassion and desperation emerged. Strangers extended helping hands to one another, united by a common understanding of the fragility of life. Yet, there were those who exploited the chaos, taking advantage of the vulnerable and engaging in acts of violence and looting.

                                The city once known for its vibrancy and unity now stood fractured and divided. The collapse of social order and the erosion of trust left scars that would run deep. As the days blurred together, the true extent of the catastrophe became painfully apparent—the world they knew was unraveling, and there was no turning back.",
                                'html' => "
                                <p>Sirens blared, drowning out the wails of terrified citizens. Emergency services raced through the streets, their lights piercing the darkness, but their efforts seemed futile against the onslaught of chaos. The once orderly city descended into a maelstrom of panic and confusion.</p>
                                
                                <p>News broadcasts became a constant stream of updates, each report more alarming than the last. Speculation ran rampant as theories circulated about the cause and possible containment of the outbreak. Misinformation mingled with genuine concern, amplifying the collective anxiety gripping the city.</p>
                                
                                <p>Supermarkets and stores, once pillars of stability, transformed into battlegrounds for survival. Shelves were stripped bare within hours as people scrambled to stockpile supplies. Shopping carts collided, tempers flared, and the desperate fought for every last can of food or bottle of water.</p>
                                
                                <p>Amidst the frenzy, individuals tried to make sense of the rapidly changing world. Friends and family gathered in hushed conversations, sharing rumors and strategies for staying safe. Each day brought new challenges and unforeseen dangers, forcing them to adapt to a reality that seemed to unravel with every passing moment.</p>
                                
                                <p>The authorities, burdened by the sheer scale of the crisis, struggled to maintain control. Their communications became sporadic, leaving citizens to fend for themselves. The absence of clear directives and guidance only fueled the chaos, as people grappled with the weight of their own decisions.</p>
                                
                                <p>In the midst of this turmoil, acts of both compassion and desperation emerged. Strangers extended helping hands to one another, united by a common understanding of the fragility of life. Yet, there were those who exploited the chaos, taking advantage of the vulnerable and engaging in acts of violence and looting.</p>
                                
                                
                                <p>The city once known for its vibrancy and unity now stood fractured and divided. The collapse of social order and the erosion of trust left scars that would run deep. As the days blurred together, the true extent of the catastrophe became painfully apparent—the world they knew was unraveling, and there was no turning back.</p>"
                            ]
                        ]
                    ],
                    [
                        'title' => 'The Outbreak Begins',
                        'status' => 'published',
                        'page_state' => [
                            [
                                'content' => "The once vibrant city now stood on the precipice of an unimaginable catastrophe. Fear spread like wildfire through the veins of its inhabitants, choking the air with an oppressive heaviness. The first whispers of the outbreak had turned into a deafening roar of panic and desperation.

                                In the early hours of the morning, the city awoke to a grim reality. Reports of a rapidly spreading contagion had shattered the illusion of safety. Anxiety gnawed at the hearts of its citizens, leaving them haunted by the uncertain future that lay ahead.
                                
                                News channels blasted warnings, images of the infected, and advice on how to protect oneself. People huddled around television screens, their eyes wide with a mixture of terror and disbelief. The once familiar faces of news anchors now carried expressions of urgency and concern, their voices trembled as they struggled to convey the magnitude of the crisis.
                                
                                Within the blink of an eye, the bustling streets transformed into ghostly avenues. Shops shuttered their doors, their windows reflecting the collective fear that had taken hold. The city's rhythm faltered, replaced by an eerie silence broken only by the distant sound of sirens and muffled cries.
                                
                                Neighbors exchanged wary glances from behind locked doors, their once friendly greetings replaced by an unspoken understanding. Trust waned as suspicion grew, each passerby regarded with cautious eyes. Strangers became potential carriers of the unseen menace, prompting a withdrawal into the safety of one's own space.
                                
                                The city's infrastructure strained under the weight of the mounting crisis. Hospitals overflowed with the afflicted, their corridors filled with the agonized cries of the infected. Medical personnel fought valiantly against overwhelming odds, but the tide seemed unrelenting. The scarcity of resources and mounting casualties painted a bleak picture of a healthcare system on the brink of collapse.
                                
                                Authorities, burdened by the weight of their responsibilities, scrambled to maintain a semblance of order. Quarantine zones were established, cordoning off areas deemed most affected. Armed guards patrolled the boundaries, a stark reminder of the fragile balance between safety and containment.
                                
                                Amidst the chaos, ordinary citizens struggled to grasp the magnitude of the unfolding catastrophe. Families huddled together, seeking solace in each other's presence, their every decision driven by a primal instinct to survive. The city, once a beacon of progress and unity, now stood divided, its residents bonded by a shared dread of the unknown.
                                
                                And so, with hearts heavy and minds clouded by uncertainty, the city braced itself for what lay ahead. The outbreak had taken hold, setting in motion a chain of events that would forever alter the course of their lives. The journey into the depths of this new reality had only just begun.",
                                'html' => "
                                <p>The once vibrant city now stood on the precipice of an unimaginable catastrophe. Fear spread like wildfire through the veins of its inhabitants, choking the air with an oppressive heaviness. The first whispers of the outbreak had turned into a deafening roar of panic and desperation.</p>
                                
                                <p>In the early hours of the morning, the city awoke to a grim reality. Reports of a rapidly spreading contagion had shattered the illusion of safety. Anxiety gnawed at the hearts of its citizens, leaving them haunted by the uncertain future that lay ahead.</p>
                                
                                <p>News channels blasted warnings, images of the infected, and advice on how to protect oneself. People huddled around television screens, their eyes wide with a mixture of terror and disbelief. The once familiar faces of news anchors now carried expressions of urgency and concern, their voices trembled as they struggled to convey the magnitude of the crisis.</p>
                                
                                <p>Within the blink of an eye, the bustling streets transformed into ghostly avenues. Shops shuttered their doors, their windows reflecting the collective fear that had taken hold. The city's rhythm faltered, replaced by an eerie silence broken only by the distant sound of sirens and muffled cries.</p>
                                
                                <p>Neighbors exchanged wary glances from behind locked doors, their once friendly greetings replaced by an unspoken understanding. Trust waned as suspicion grew, each passerby regarded with cautious eyes. Strangers became potential carriers of the unseen menace, prompting a withdrawal into the safety of one's own space.</p>
                                
                                <p>The city's infrastructure strained under the weight of the mounting crisis. Hospitals overflowed with the afflicted, their corridors filled with the agonized cries of the infected. Medical personnel fought valiantly against overwhelming odds, but the tide seemed unrelenting. The scarcity of resources and mounting casualties painted a bleak picture of a healthcare system on the brink of collapse.</p>
                                
                                <p>Authorities, burdened by the weight of their responsibilities, scrambled to maintain a semblance of order. Quarantine zones were established, cordoning off areas deemed most affected. Armed guards patrolled the boundaries, a stark reminder of the fragile balance between safety and containment.</p>
                                
                                <p>Amidst the chaos, ordinary citizens struggled to grasp the magnitude of the unfolding catastrophe. Families huddled together, seeking solace in each other's presence, their every decision driven by a primal instinct to survive. The city, once a beacon of progress and unity, now stood divided, its residents bonded by a shared dread of the unknown.</p>
                                
                                <p>And so, with hearts heavy and minds clouded by uncertainty, the city braced itself for what lay ahead. The outbreak had taken hold, setting in motion a chain of events that would forever alter the course of their lives. The journey into the depths of this new reality had only just begun.</p>"
                            ],
                            [
                                'content' => "As the reality of the outbreak took hold, the city's inhabitants struggled to comprehend the magnitude of the situation. Each passing moment brought a new wave of information, blurring the line between fact and speculation. Confusion reigned supreme, and amidst the chaos, questions begged for answers.

                                People sought solace in the familiarity of their routines, desperately clinging to a semblance of normalcy. Some ventured outside cautiously, wearing masks and gloves as shields against the invisible threat that lurked in the air. Others barricaded themselves indoors, their homes transformed into fortresses against the encroaching danger.
                                
                                Schools and workplaces closed their doors, their once bustling halls now empty and devoid of life. Children adapted to virtual classrooms, their laughter silenced within the confines of their homes. Professionals grappled with the challenges of remote work, the boundaries between personal and professional life blurring in the face of an uncertain future.
                                
                                The city's streets, once alive with the vibrant energy of its inhabitants, now stood deserted. The absence of bustling crowds and honking cars echoed a haunting emptiness. Nature, however, began to reclaim its space. Wildlife ventured closer to the urban landscape, exploring the vacant alleys and abandoned parks.
                                
                                Amongst the chaos, acts of kindness and solidarity emerged like fragile flowers in a cracked pavement. Good Samaritans distributed food to the needy, lending a helping hand to those most affected by the crisis. Community support groups formed, offering solace and support in a time of isolation and despair.
                                
                                Yet, with each passing day, the impact of the outbreak became more apparent. The death toll rose, and grief cast its long shadow over the city. Mourning became a collective experience as families bid farewell to their loved ones in somber, restricted ceremonies. The weight of loss hung heavy in the air, intertwining with the fear that gripped the hearts of those left behind.
                                
                                In the face of adversity, heroes emerged from unexpected corners. Healthcare workers, armed with courage and resilience, fought tirelessly on the front lines. Their dedication and selflessness breathed life into the flickering embers of hope. They stood as beacons of light amidst the encroaching darkness, their unwavering commitment a testament to the strength of the human spirit.
                                
                                But for many, the overwhelming despair threatened to consume their resolve. The city had become a battlefield, and its inhabitants were caught in the crossfire. The outbreak had begun, and its impact reverberated through every facet of life, shattering the illusions of safety and revealing the fragility of existence.",
                                'html' => "
                                <p>As the reality of the outbreak took hold, the city's inhabitants struggled to comprehend the magnitude of the situation. Each passing moment brought a new wave of information, blurring the line between fact and speculation. Confusion reigned supreme, and amidst the chaos, questions begged for answers.</p>
                                
                                <p>People sought solace in the familiarity of their routines, desperately clinging to a semblance of normalcy. Some ventured outside cautiously, wearing masks and gloves as shields against the invisible threat that lurked in the air. Others barricaded themselves indoors, their homes transformed into fortresses against the encroaching danger.</p>
                                
                                <p>Schools and workplaces closed their doors, their once bustling halls now empty and devoid of life. Children adapted to virtual classrooms, their laughter silenced within the confines of their homes. Professionals grappled with the challenges of remote work, the boundaries between personal and professional life blurring in the face of an uncertain future.</p>
                                
                                <p>The city's streets, once alive with the vibrant energy of its inhabitants, now stood deserted. The absence of bustling crowds and honking cars echoed a haunting emptiness. Nature, however, began to reclaim its space. Wildlife ventured closer to the urban landscape, exploring the vacant alleys and abandoned parks.</p>
                                
                                <p>Amongst the chaos, acts of kindness and solidarity emerged like fragile flowers in a cracked pavement. Good Samaritans distributed food to the needy, lending a helping hand to those most affected by the crisis. Community support groups formed, offering solace and support in a time of isolation and despair.</p>
                                
                                <p>Yet, with each passing day, the impact of the outbreak became more apparent. The death toll rose, and grief cast its long shadow over the city. Mourning became a collective experience as families bid farewell to their loved ones in somber, restricted ceremonies. The weight of loss hung heavy in the air, intertwining with the fear that gripped the hearts of those left behind.</p>
                                
                                <p>In the face of adversity, heroes emerged from unexpected corners. Healthcare workers, armed with courage and resilience, fought tirelessly on the front lines. Their dedication and selflessness breathed life into the flickering embers of hope. They stood as beacons of light amidst the encroaching darkness, their unwavering commitment a testament to the strength of the human spirit.</p>
                                
                                <p>But for many, the overwhelming despair threatened to consume their resolve. The city had become a battlefield, and its inhabitants were caught in the crossfire. The outbreak had begun, and its impact reverberated through every facet of life, shattering the illusions of safety and revealing the fragility of existence.</p>"
                            ],
                            [
                                'content' => "Amidst the chaos and despair, a desperate hunger for answers took hold of the city's inhabitants. The quest for understanding became a driving force, pushing people to seek out any semblance of truth in a sea of uncertainty.

                                Online forums and social media platforms became battlegrounds of information, as individuals shared their theories and personal experiences. Debates raged, fueled by a mixture of fear, frustration, and the insatiable thirst for knowledge. Eyewitness accounts and scientific studies were dissected, each scrap of information pored over in the hopes of unraveling the mysteries of the outbreak.
                                
                                Conspiracy theories and rumors ran rampant, weaving their tangled webs through the collective consciousness. Some speculated about government cover-ups and clandestine experiments gone awry. Others pointed fingers at foreign entities, attributing the outbreak to acts of bioterrorism. The line between fact and fiction blurred, leaving many to question whom they could trust.
                                
                                Scientists and medical experts toiled tirelessly in laboratories and makeshift research facilities, desperate to unlock the secrets of the contagion. They sought to understand its origins, its mode of transmission, and any potential avenues for treatment or prevention. Their work became a beacon of hope, a flickering light in the darkness that offered the possibility of redemption.
                                
                                As the days turned into weeks, the toll of the outbreak etched itself onto the city's landscape. Makeshift memorials adorned street corners, walls adorned with photographs and messages of remembrance. Vigils were held, where grieving souls came together to share their sorrow and find solace in the company of others who had lost so much.
                                
                                Yet, even in the face of despair, pockets of resilience and defiance emerged. Some began to organize grassroots movements, advocating for change and demanding accountability from those in power. Their voices echoed through the city streets, determined to turn tragedy into catalysts for societal transformation.
                                
                                In the depths of the outbreak's grip, alliances were formed and fractures deepened. Factions emerged, each with their own ideologies and agendas. Some sought to restore order and rebuild a semblance of the world they once knew. Others embraced chaos, believing that survival required ruthless measures and an abandonment of the old ways.
                                
                                The city, once a tapestry of diverse lives intertwined, now stood divided. Boundaries were drawn, allegiances chosen, and conflicts simmered beneath the surface. The outbreak had become a crucible, testing the limits of humanity's resilience, compassion, and capacity for cruelty.",
                                'html' => "
                                <p>Amidst the chaos and despair, a desperate hunger for answers took hold of the city's inhabitants. The quest for understanding became a driving force, pushing people to seek out any semblance of truth in a sea of uncertainty.</p>
                                
                                <p>Online forums and social media platforms became battlegrounds of information, as individuals shared their theories and personal experiences. Debates raged, fueled by a mixture of fear, frustration, and the insatiable thirst for knowledge. Eyewitness accounts and scientific studies were dissected, each scrap of information pored over in the hopes of unraveling the mysteries of the outbreak.</p>
                                
                                <p>Conspiracy theories and rumors ran rampant, weaving their tangled webs through the collective consciousness. Some speculated about government cover-ups and clandestine experiments gone awry. Others pointed fingers at foreign entities, attributing the outbreak to acts of bioterrorism. The line between fact and fiction blurred, leaving many to question whom they could trust.</p>
                                
                                <p>Scientists and medical experts toiled tirelessly in laboratories and makeshift research facilities, desperate to unlock the secrets of the contagion. They sought to understand its origins, its mode of transmission, and any potential avenues for treatment or prevention. Their work became a beacon of hope, a flickering light in the darkness that offered the possibility of redemption.</p>
                                
                                <p>As the days turned into weeks, the toll of the outbreak etched itself onto the city's landscape. Makeshift memorials adorned street corners, walls adorned with photographs and messages of remembrance. Vigils were held, where grieving souls came together to share their sorrow and find solace in the company of others who had lost so much.</p>
                                
                                <p>Yet, even in the face of despair, pockets of resilience and defiance emerged. Some began to organize grassroots movements, advocating for change and demanding accountability from those in power. Their voices echoed through the city streets, determined to turn tragedy into catalysts for societal transformation.</p>
                                
                                <p>In the depths of the outbreak's grip, alliances were formed and fractures deepened. Factions emerged, each with their own ideologies and agendas. Some sought to restore order and rebuild a semblance of the world they once knew. Others embraced chaos, believing that survival required ruthless measures and an abandonment of the old ways.</p>
                                
                                <p>The city, once a tapestry of diverse lives intertwined, now stood divided. Boundaries were drawn, allegiances chosen, and conflicts simmered beneath the surface. The outbreak had become a crucible, testing the limits of humanity's resilience, compassion, and capacity for cruelty.</p>"
                            ],
                            [
                                'content' => "As the days turned into weeks and the grip of the outbreak tightened, a suffocating realization settled upon the city: there was no way out. The once bustling metropolis had transformed into a prison, its inhabitants trapped within its boundaries by an invisible enemy.

                                Quarantine zones, once established as a means of protection, now became confinements that grew tighter with each passing day. The walls that had promised safety now seemed like prison bars, enclosing the desperate masses within a crumbling sanctuary.
                                
                                Resources dwindled, testing the limits of survival. Rations became scarce, and hunger gnawed at the bellies of the hungry. Desperation fueled acts of theft and violence, as people fought tooth and nail for the basic necessities that had become luxuries in this new reality.
                                
                                Inside the quarantine zones, tensions escalated. The strain of confinement wore on the nerves of the inhabitants, eroding the fragile bonds of camaraderie. Trust became a scarce commodity, as suspicion and paranoia seeped into the fabric of daily life.
                                
                                Authority figures, once seen as protectors, now struggled to maintain control. Their presence served as a constant reminder of the limitations imposed on freedom. The streets were patrolled by armed guards, their watchful eyes scanning for signs of dissent or disobedience.
                                
                                Rumors circulated of escape attempts, of individuals risking everything to break free from the confines of the city. Some whispered tales of hidden routes and secret passages, while others dismissed such notions as desperate fantasies. The city had become a prison with walls that seemed impenetrable.
                                
                                But amidst the despair, a flicker of hope remained. Whispers spread of a resistance movement brewing in the shadows, a group of individuals who refused to accept their fate. They believed that there had to be a way out, an escape from the clutches of the outbreak that held them captive.",
                                'html' => "
                                <p>As the days turned into weeks and the grip of the outbreak tightened, a suffocating realization settled upon the city: there was no way out. The once bustling metropolis had transformed into a prison, its inhabitants trapped within its boundaries by an invisible enemy.</p>
                                
                                <p>Quarantine zones, once established as a means of protection, now became confinements that grew tighter with each passing day. The walls that had promised safety now seemed like prison bars, enclosing the desperate masses within a crumbling sanctuary.</p>
                                
                                <p>Resources dwindled, testing the limits of survival. Rations became scarce, and hunger gnawed at the bellies of the hungry. Desperation fueled acts of theft and violence, as people fought tooth and nail for the basic necessities that had become luxuries in this new reality.</p>
                                
                                <p>Inside the quarantine zones, tensions escalated. The strain of confinement wore on the nerves of the inhabitants, eroding the fragile bonds of camaraderie. Trust became a scarce commodity, as suspicion and paranoia seeped into the fabric of daily life.</p>
                                
                                <p>Authority figures, once seen as protectors, now struggled to maintain control. Their presence served as a constant reminder of the limitations imposed on freedom. The streets were patrolled by armed guards, their watchful eyes scanning for signs of dissent or disobedience.</p>
                                
                                <p>Rumors circulated of escape attempts, of individuals risking everything to break free from the confines of the city. Some whispered tales of hidden routes and secret passages, while others dismissed such notions as desperate fantasies. The city had become a prison with walls that seemed impenetrable.</p>
                                
                                <p>But amidst the despair, a flicker of hope remained. Whispers spread of a resistance movement brewing in the shadows, a group of individuals who refused to accept their fate. They believed that there had to be a way out, an escape from the clutches of the outbreak that held them captive.</p>"
                            ]
                        ]
                    ],
                    [
                        'title' => 'No Way Out',
                        'status' => 'published',
                        'page_state' => [
                            [
                                'content' => "Within the suffocating confines of the quarantine zone, whispers of rebellion stirred. The air crackled with a tangible tension as a small group of individuals gathered in the shadows, their faces obscured by the veil of darkness.

                                Among them was Ellie, a young woman whose spirit burned with a fierce determination to break free from the chains of the outbreak's grip. Her eyes shone with an unwavering resolve, the weight of the city's confinement etched upon her shoulders. She had seen the best and worst of humanity, and now, she sought an escape from this maze of desperation.
                                
                                Beside Ellie stood Joel, a weathered survivor who carried the weight of his own losses. His gaze betrayed a mix of weariness and cautious optimism. He had traversed through the darkest corners of this new world, battling against the infected and the merciless forces that sought to control what little remained.
                                
                                The others in the group, each with their own stories etched upon their faces, looked to Ellie and Joel as beacons of hope. They had come together, bound by a shared belief that there must be a way out, a path to freedom from this labyrinth of despair.
                                
                                Their plan was daring and fraught with danger. It required stealth, resourcefulness, and a willingness to risk everything. They had spent countless hours mapping the intricacies of the quarantine zone, identifying potential weaknesses in the oppressive regime that held them captive.
                                
                                As they huddled together, their breath mingling with the cold night air, Ellie addressed the group with a voice that betrayed a mix of determination and uncertainty. She spoke of the countless lives lost, the dreams shattered, and the will to fight back against the injustices that suffocated them.
                                
                                \"This is our chance,\" Ellie said, her voice a quiet but unwavering resolve. \"We won't let them keep us locked away, trapped like animals. We'll find a way out, no matter the cost. They can't break us.\"
                                
                                The group nodded in silent agreement, their eyes glimmering with a flicker of hope. The time for passivity had passed. It was time to rise against the forces that sought to snuff out their spirits and reclaim their freedom.",
                                'html' => "
                                <p>Within the suffocating confines of the quarantine zone, whispers of rebellion stirred. The air crackled with a tangible tension as a small group of individuals gathered in the shadows, their faces obscured by the veil of darkness.</p>
                                
                                <p>Among them was Ellie, a young woman whose spirit burned with a fierce determination to break free from the chains of the outbreak's grip. Her eyes shone with an unwavering resolve, the weight of the city's confinement etched upon her shoulders. She had seen the best and worst of humanity, and now, she sought an escape from this maze of desperation.</p>
                                
                                <p>Beside Ellie stood Joel, a weathered survivor who carried the weight of his own losses. His gaze betrayed a mix of weariness and cautious optimism. He had traversed through the darkest corners of this new world, battling against the infected and the merciless forces that sought to control what little remained.</p>
                                
                                <p>The others in the group, each with their own stories etched upon their faces, looked to Ellie and Joel as beacons of hope. They had come together, bound by a shared belief that there must be a way out, a path to freedom from this labyrinth of despair.</p>
                                
                                <p>Their plan was daring and fraught with danger. It required stealth, resourcefulness, and a willingness to risk everything. They had spent countless hours mapping the intricacies of the quarantine zone, identifying potential weaknesses in the oppressive regime that held them captive.</p>
                                
                                <p>As they huddled together, their breath mingling with the cold night air, Ellie addressed the group with a voice that betrayed a mix of determination and uncertainty. She spoke of the countless lives lost, the dreams shattered, and the will to fight back against the injustices that suffocated them.</p>
                                
                                <p>\"This is our chance,\" Ellie said, her voice a quiet but unwavering resolve. \"We won't let them keep us locked away, trapped like animals. We'll find a way out, no matter the cost. They can't break us.\"</p>
                                
                                <p>The group nodded in silent agreement, their eyes glimmering with a flicker of hope. The time for passivity had passed. It was time to rise against the forces that sought to snuff out their spirits and reclaim their freedom.</p>"
                            ],
                            [
                                'content' => "In the dimly lit room, the group finalized their escape plan, their voices hushed as they carefully considered every detail. Maps were spread across the table, marked with points of interest and potential obstacles. It was a precarious balancing act, their hopes buoyed by determination, but tempered by the ever-present risks that awaited them beyond the quarantine zone's boundaries.

                                Ellie, her eyes alight with intensity, pointed to a series of interconnected tunnels that snaked beneath the city streets. \"These tunnels,\" she said, her voice filled with a mix of excitement and trepidation, \"they could be our way out. If we navigate them carefully, we might just slip past their watchful eyes.\"
                                
                                Joel leaned forward, studying the map intently. He traced a finger along the tunnels, mapping out potential routes and escape points. \"We'll need supplies,\" he said, his voice gruff but resolute. \"Weapons, food, anything that can help us survive once we're out there.\"
                                
                                The others nodded in agreement, their gazes fixed on the map, their minds already calculating the risks and challenges that lay ahead. They knew that the journey beyond the walls would be treacherous, filled with unknown dangers and the ever-present threat of the infected. But they were willing to face the chaos outside, knowing that the confines of the quarantine zone offered no real sanctuary.
                                
                                Each member of the group brought their unique skills to the table. Some were skilled in stealth and scouting, capable of navigating through the shadows undetected. Others possessed medical expertise, their knowledge essential for treating injuries and illnesses that might arise during their escape.
                                
                                As the plan took shape, hope intertwined with anxiety, forming a tight knot within their chests. The escape would require precision, timing, and a united front. They would need to rely on each other, trust one another implicitly, as they ventured into the unknown.
                                
                                The final moments of preparation weighed heavily upon them. They steeled themselves for the challenges that awaited, mentally preparing for the risks they were about to undertake. The time for hesitation had passed; their resolve had been forged in the crucible of despair.",
                                'html' => "
                                <p>In the dimly lit room, the group finalized their escape plan, their voices hushed as they carefully considered every detail. Maps were spread across the table, marked with points of interest and potential obstacles. It was a precarious balancing act, their hopes buoyed by determination, but tempered by the ever-present risks that awaited them beyond the quarantine zone's boundaries.</p>

                                <p>Ellie, her eyes alight with intensity, pointed to a series of interconnected tunnels that snaked beneath the city streets. \"These tunnels,\" she said, her voice filled with a mix of excitement and trepidation, \"they could be our way out. If we navigate them carefully, we might just slip past their watchful eyes.\"</p>

                                <p>Joel leaned forward, studying the map intently. He traced a finger along the tunnels, mapping out potential routes and escape points. \"We'll need supplies,\" he said, his voice gruff but resolute. \"Weapons, food, anything that can help us survive once we're out there.\"</p>

                                <p>The others nodded in agreement, their gazes fixed on the map, their minds already calculating the risks and challenges that lay ahead. They knew that the journey beyond the walls would be treacherous, filled with unknown dangers and the ever-present threat of the infected. But they were willing to face the chaos outside, knowing that the confines of the quarantine zone offered no real sanctuary.</p>

                                <p>Each member of the group brought their unique skills to the table. Some were skilled in stealth and scouting, capable of navigating through the shadows undetected. Others possessed medical expertise, their knowledge essential for treating injuries and illnesses that might arise during their escape.</p>

                                <p>As the plan took shape, hope intertwined with anxiety, forming a tight knot within their chests. The escape would require precision, timing, and a united front. They would need to rely on each other, trust one another implicitly, as they ventured into the unknown.</p>

                                <p>The final moments of preparation weighed heavily upon them. They steeled themselves for the challenges that awaited, mentally preparing for the risks they were about to undertake. The time for hesitation had passed; their resolve had been forged in the crucible of despair.</p>"
                            ]
                        ]
                    ],
                    [
                        'title' => 'Surviving the Chaos',
                        'status' => 'published',
                        'page_state' => [
                            [
                                'content' => "The world outside the quarantine zone greeted them with a cacophony of chaos. It was a place where the remnants of civilization fought a losing battle against the encroaching darkness. The once familiar streets now lay in ruins, their structures crumbling under the weight of neglect and despair.

                                As the group stepped beyond the boundaries of their former prison, a surge of adrenaline coursed through their veins. Every step felt like a defiant act, a declaration of their determination to survive against all odds. They were now prisoners of the world, navigating the treacherous path of a shattered society.
                                
                                The streets echoed with haunting silence, broken only by the distant howls of the infected and the rustling of wind through broken windows. Nature had begun its relentless reclamation, vines creeping up buildings, reclaiming what had once been human domain. It was a reminder of the fragility of humanity's grasp on the world.
                                
                                The group moved cautiously, their senses heightened as they scanned their surroundings for signs of danger. Every alley, every abandoned car, held the potential for hidden threats. Their survival instincts had been honed through hardship, and they knew that complacency could be fatal.
                                
                                They encountered others along the way, survivors who had weathered their own trials and tribulations. Some were cautious, their eyes filled with suspicion, wary of strangers in a world that had grown hostile. Others sought solace in the company of fellow survivors, understanding that strength lay in unity.
                                
                                Scavenging became a way of life, as they searched for supplies to sustain them on their journey. Abandoned stores and buildings offered glimpses of what once was, their shelves now stripped bare or filled with remnants of a bygone era. They scoured every nook and cranny, seizing whatever meager provisions they could find.
                                
                                As they ventured deeper into the heart of the chaos, the group encountered pockets of resistance and desperate enclaves. Factions had risen, each with their own vision for survival, their own rules and hierarchies. Trust became a scarce commodity, earned through shared struggles and proven loyalties.
                                
                                In this new world, violence lurked around every corner. The infected were a constant threat, their twisted forms and insatiable hunger a reminder of the price paid for complacency. But it was the human element that often posed the greatest danger. Desperation had a way of eroding morality, pushing people to commit unspeakable acts in the name of survival.",
                                'html' => "
                                <p>The world outside the quarantine zone greeted them with a cacophony of chaos. It was a place where the remnants of civilization fought a losing battle against the encroaching darkness. The once familiar streets now lay in ruins, their structures crumbling under the weight of neglect and despair.</p>

                                <p>As the group stepped beyond the boundaries of their former prison, a surge of adrenaline coursed through their veins. Every step felt like a defiant act, a declaration of their determination to survive against all odds. They were now prisoners of the world, navigating the treacherous path of a shattered society.</p>

                                <p>The streets echoed with haunting silence, broken only by the distant howls of the infected and the rustling of wind through broken windows. Nature had begun its relentless reclamation, vines creeping up buildings, reclaiming what had once been human domain. It was a reminder of the fragility of humanity's grasp on the world.</p>

                                <p>The group moved cautiously, their senses heightened as they scanned their surroundings for signs of danger. Every alley, every abandoned car, held the potential for hidden threats. Their survival instincts had been honed through hardship, and they knew that complacency could be fatal.</p>

                                <p>They encountered others along the way, survivors who had weathered their own trials and tribulations. Some were cautious, their eyes filled with suspicion, wary of strangers in a world that had grown hostile. Others sought solace in the company of fellow survivors, understanding that strength lay in unity.</p>

                                <p>Scavenging became a way of life, as they searched for supplies to sustain them on their journey. Abandoned stores and buildings offered glimpses of what once was, their shelves now stripped bare or filled with remnants of a bygone era. They scoured every nook and cranny, seizing whatever meager provisions they could find.</p>

                                <p>As they ventured deeper into the heart of the chaos, the group encountered pockets of resistance and desperate enclaves. Factions had risen, each with their own vision for survival, their own rules and hierarchies. Trust became a scarce commodity, earned through shared struggles and proven loyalties.</p>

                                <p>In this new world, violence lurked around every corner. The infected were a constant threat, their twisted forms and insatiable hunger a reminder of the price paid for complacency. But it was the human element that often posed the greatest danger. Desperation had a way of eroding morality, pushing people to commit unspeakable acts in the name of survival.</p>"
                            ],
                            [
                                'content' => "Every step they took through the desolate streets carried the weight of loss. The world they once knew had crumbled, leaving behind only fragments of memories and the ache of what was forever lost. Faces of loved ones haunted their thoughts, their absence a constant companion in this new reality.

                                Ellie, her eyes reflecting a mix of sorrow and determination, clutched a photograph close to her chest. It was a snapshot of a time before, a moment frozen in joy and innocence. The image whispered promises of a life that had slipped through their fingers, a life that would never be the same again.
                                
                                Joel walked beside her, his features etched with lines of grief and resolve. He carried within him the burden of those he had lost, their absence a constant ache in his heart. But he knew that to dwell on the past would only invite the dangers of the present to consume them. He pushed forward, driven by the need to protect and survive.
                                
                                The streets whispered tales of tragedy, their broken structures testaments to the fragility of human existence. Buildings stood as silent witnesses to the battles waged, their scarred facades a reminder of the fierce struggles fought for each inch of territory.
                                
                                Among the remnants of the old world, nature fought to reclaim its dominion. The encroaching wilderness twisted through concrete cracks, reclaiming abandoned spaces with untamed growth. It was a stark reminder that the world had moved on, indifferent to the plight of its former masters.
                                
                                Survival became a constant struggle, as resources grew scarcer by the day. The group scoured abandoned homes, their footsteps muffled by the debris of lives interrupted. They scavenged for food, water, and any supplies that could sustain them in the harshness of their new reality.
                                
                                But amidst the desolation, glimmers of hope emerged. Acts of kindness, however rare, reaffirmed their faith in the resilience of humanity. Strangers banded together, pooling their limited resources and skills to forge a path forward. It was in these fleeting moments of connection that they found solace and strength.",

                                'html' => "
                                <p>Every step they took through the desolate streets carried the weight of loss. The world they once knew had crumbled, leaving behind only fragments of memories and the ache of what was forever lost. Faces of loved ones haunted their thoughts, their absence a constant companion in this new reality.</p>

                                <p>Ellie, her eyes reflecting a mix of sorrow and determination, clutched a photograph close to her chest. It was a snapshot of a time before, a moment frozen in joy and innocence. The image whispered promises of a life that had slipped through their fingers, a life that would never be the same again.</p>

                                <p>Joel walked beside her, his features etched with lines of grief and resolve. He carried within him the burden of those he had lost, their absence a constant ache in his heart. But he knew that to dwell on the past would only invite the dangers of the present to consume them. He pushed forward, driven by the need to protect and survive.</p>

                                <p>The streets whispered tales of tragedy, their broken structures testaments to the fragility of human existence. Buildings stood as silent witnesses to the battles waged, their scarred facades a reminder of the fierce struggles fought for each inch of territory.</p>

                                <p>Among the remnants of the old world, nature fought to reclaim its dominion. The encroaching wilderness twisted through concrete cracks, reclaiming abandoned spaces with untamed growth. It was a stark reminder that the world had moved on, indifferent to the plight of its former masters.</p>

                                <p>Survival became a constant struggle, as resources grew scarcer by the day. The group scoured abandoned homes, their footsteps muffled by the debris of lives interrupted. They scavenged for food, water, and any supplies that could sustain them in the harshness of their new reality.</p>

                                <p>But amidst the desolation, glimmers of hope emerged. Acts of kindness, however rare, reaffirmed their faith in the resilience of humanity. Strangers banded together, pooling their limited resources and skills to forge a path forward. It was in these fleeting moments of connection that they found solace and strength.</p>"
                            ],
                            [
                                'content' => "As they ventured deeper into the heart of the chaos, the bonds of brotherhood strengthened among the group. Adversity had a way of forging unlikely alliances, of bringing together individuals from disparate backgrounds bound by a shared goal: survival.

                                Ellie, her youthful determination shining through, formed a friendship with Riley, a fellow survivor with a mischievous smile that belied the weight of the world on her shoulders. In this shattered reality, they found solace in each other's company, a respite from the constant struggle for survival.
                                
                                Joel, too, found a kindred spirit in Tess, a woman whose resilience matched his own. They shared a connection borne out of shared loss and an unspoken understanding of the harsh realities they faced. Their partnership became a pillar of strength amidst the chaos, each bolstering the other's resolve.
                                
                                The group's dynamics shifted and evolved as they encountered new challenges. They learned to rely on one another's strengths, to trust in their collective expertise. Each member brought unique skills to the table, contributing to the group's chances of survival.
                                
                                Navigating through treacherous territory required stealth, cunning, and a keen eye for danger. They moved like shadows, slipping past threats that lurked around every corner. Their survival depended on their ability to adapt, to blend into the background and evade the watchful eyes of both infected and hostile survivors.
                                
                                But survival alone was not enough. They yearned for something more—a semblance of normalcy in a world that had been torn apart. In quiet moments of respite, they shared stories, laughter, and dreams of a future where the scars of the past could heal.
                                
                                Yet, the weight of their actions also weighed heavily upon them. The choices made in the name of survival sometimes carried a heavy moral toll. The boundaries between right and wrong blurred, forcing them to confront the depths of their own humanity. It was a constant battle to hold onto their humanity amidst the brutality of their circumstances.",
                                'html' => "
                                <p>As they ventured deeper into the heart of the chaos, the bonds of brotherhood strengthened among the group. Adversity had a way of forging unlikely alliances, of bringing together individuals from disparate backgrounds bound by a shared goal: survival.</p>

                                <p>Ellie, her youthful determination shining through, formed a friendship with Riley, a fellow survivor with a mischievous smile that belied the weight of the world on her shoulders. In this shattered reality, they found solace in each other's company, a respite from the constant struggle for survival.</p>

                                <p>Joel, too, found a kindred spirit in Tess, a woman whose resilience matched his own. They shared a connection borne out of shared loss and an unspoken understanding of the harsh realities they faced. Their partnership became a pillar of strength amidst the chaos, each bolstering the other's resolve.</p>

                                <p>The group's dynamics shifted and evolved as they encountered new challenges. They learned to rely on one another's strengths, to trust in their collective expertise. Each member brought unique skills to the table, contributing to the group's chances of survival.</p>

                                <p>Navigating through treacherous territory required stealth, cunning, and a keen eye for danger. They moved like shadows, slipping past threats that lurked around every corner. Their survival depended on their ability to adapt, to blend into the background and evade the watchful eyes of both infected and hostile survivors.</p>

                                <p>But survival alone was not enough. They yearned for something more—a semblance of normalcy in a world that had been torn apart. In quiet moments of respite, they shared stories, laughter, and dreams of a future where the scars of the past could heal.</p>

                                <p>Yet, the weight of their actions also weighed heavily upon them. The choices made in the name of survival sometimes carried a heavy moral toll. The boundaries between right and wrong blurred, forcing them to confront the depths of their own humanity. It was a constant battle to hold onto their humanity amidst the brutality of their circumstances.</p>"
                            ]
                        ]
                    ],
                    [
                        'title' => 'Struggling for Shelter',
                        'status' => 'published',
                        'page_state' => [
                            [
                                'content' => "In the unforgiving landscape of the post-apocalyptic world, the search for shelter became a desperate struggle. The group had survived the chaos thus far, but the need for a secure haven grew more pressing with each passing day. They yearned for a place where they could rest, regroup, and gather their strength for the challenges that lay ahead.

                                Their journey led them through barren wastelands and dilapidated towns, their eyes scanning the horizon for any sign of a potential refuge. The remnants of once-thriving communities stood as hollow reminders of what had been lost, their empty streets and crumbling structures testaments to the relentless march of time.
                                
                                Resources grew scarcer, forcing the group to ration what little they had left. Hunger gnawed at their bellies, their bodies weakened by the strain of constant movement and limited sustenance. Fatigue settled into their bones, threatening to erode their resolve.
                                
                                But hope flickered in their hearts as they stumbled upon whispers of a place rumored to offer sanctuary. Whispers carried on the wind spoke of a settlement nestled deep within the wilderness—a beacon of hope amidst the desolation. The promise of safety and a chance to rebuild their shattered lives beckoned to them, renewing their determination to press onward.
                                
                                As they ventured closer to their destination, the signs of struggle and desperation grew more pronounced. Other survivors, driven to the brink of madness by the harsh realities of this new world, fought tooth and nail for whatever meager resources remained. The group treaded cautiously, wary of potential encounters that could either offer aid or spark deadly conflict.
                                
                                Each member of the group carried their own burdens—physical, emotional, and psychological. Scars adorned their bodies, reminders of battles fought and wounds endured. The weight of their past, their losses, and the choices made in the name of survival shaped their every step.
                                
                                The landscape shifted, nature weaving its own tapestry of beauty and danger. Overgrown forests whispered secrets, concealing hidden perils and untapped resources. Rivers meandered through the land, offering the possibility of sustenance and a chance to quench their unrelenting thirst.",
                                'html' => "
                                <p>In the unforgiving landscape of the post-apocalyptic world, the search for shelter became a desperate struggle. The group had survived the chaos thus far, but the need for a secure haven grew more pressing with each passing day. They yearned for a place where they could rest, regroup, and gather their strength for the challenges that lay ahead.</p>

                                <p>Their journey led them through barren wastelands and dilapidated towns, their eyes scanning the horizon for any sign of a potential refuge. The remnants of once-thriving communities stood as hollow reminders of what had been lost, their empty streets and crumbling structures testaments to the relentless march of time.</p>

                                <p>Resources grew scarcer, forcing the group to ration what little they had left. Hunger gnawed at their bellies, their bodies weakened by the strain of constant movement and limited sustenance. Fatigue settled into their bones, threatening to erode their resolve.</p>

                                <p>But hope flickered in their hearts as they stumbled upon whispers of a place rumored to offer sanctuary. Whispers carried on the wind spoke of a settlement nestled deep within the wilderness—a beacon of hope amidst the desolation. The promise of safety and a chance to rebuild their shattered lives beckoned to them, renewing their determination to press onward.</p>

                                <p>As they ventured closer to their destination, the signs of struggle and desperation grew more pronounced. Other survivors, driven to the brink of madness by the harsh realities of this new world, fought tooth and nail for whatever meager resources remained. The group treaded cautiously, wary of potential encounters that could either offer aid or spark deadly conflict.</p>

                                <p>Each member of the group carried their own burdens—physical, emotional, and psychological. Scars adorned their bodies, reminders of battles fought and wounds endured. The weight of their past, their losses, and the choices made in the name of survival shaped their every step.</p>

                                <p>The landscape shifted, nature weaving its own tapestry of beauty and danger. Overgrown forests whispered secrets, concealing hidden perils and untapped resources. Rivers meandered through the land, offering the possibility of sustenance and a chance to quench their unrelenting thirst.</p>"
                            ],
                            [
                                'content' => "Exhaustion etched deep lines on their faces as the group trudged forward, driven by a glimmer of hope that guided their weary steps. The rumors of a settlement offering refuge grew more concrete with each passing mile, fueling their determination to reach their destination.

                                Nature conspired against them, testing their resilience at every turn. Harsh weather assaulted their senses, relentless storms threatening to wash away their hopes and leave them vulnerable in the open. They huddled together, seeking warmth and solace in their shared determination.
                                
                                Scavenging took on a renewed urgency as their resources dwindled to mere scraps. Every abandoned building held the potential for salvation—a can of food, a fresh water source, or even a makeshift shelter to offer a momentary respite from the unforgiving elements.
                                
                                The group's dynamics shifted as they faced the harsh realities of survival. Trust was earned through shared hardships and proven loyalty, but doubt lingered in the shadows. The weight of their own secrets threatened to fracture the fragile bonds they had formed.
                                
                                Yet, amidst the struggle, sparks of hope ignited. Acts of kindness and selflessness emerged, reminding them of the indomitable spirit of humanity. Strangers offered a helping hand, sharing meager provisions or offering guidance to navigate the treacherous terrain.
                                
                                As they neared their destination, the settlement materialized before their weary eyes—a sanctuary in the midst of chaos. It stood as a beacon of hope, its fortified walls a testament to the resilience of those who had carved out a semblance of order amidst the ruins.
                                
                                But the path to safety was not without its challenges. The settlement's gates loomed before them, guarded by watchful eyes. They would need to prove their worth, to demonstrate that they were more than desperate survivors seeking refuge. The group steeled themselves, ready to face whatever trials awaited them at the threshold of their potential sanctuary.",
                                'html' => "
                                <p>Exhaustion etched deep lines on their faces as the group trudged forward, driven by a glimmer of hope that guided their weary steps. The rumors of a settlement offering refuge grew more concrete with each passing mile, fueling their determination to reach their destination.</p>

                                <p>Nature conspired against them, testing their resilience at every turn. Harsh weather assaulted their senses, relentless storms threatening to wash away their hopes and leave them vulnerable in the open. They huddled together, seeking warmth and solace in their shared determination.</p>

                                <p>Scavenging took on a renewed urgency as their resources dwindled to mere scraps. Every abandoned building held the potential for salvation—a can of food, a fresh water source, or even a makeshift shelter to offer a momentary respite from the unforgiving elements.</p>

                                <p>The group's dynamics shifted as they faced the harsh realities of survival. Trust was earned through shared hardships and proven loyalty, but doubt lingered in the shadows. The weight of their own secrets threatened to fracture the fragile bonds they had formed.</p>

                                <p>Yet, amidst the struggle, sparks of hope ignited. Acts of kindness and selflessness emerged, reminding them of the indomitable spirit of humanity. Strangers offered a helping hand, sharing meager provisions or offering guidance to navigate the treacherous terrain.</p>

                                <p>As they neared their destination, the settlement materialized before their weary eyes—a sanctuary in the midst of chaos. It stood as a beacon of hope, its fortified walls a testament to the resilience of those who had carved out a semblance of order amidst the ruins.</p>

                                <p>But the path to safety was not without its challenges. The settlement's gates loomed before them, guarded by watchful eyes. They would need to prove their worth, to demonstrate that they were more than desperate survivors seeking refuge. The group steeled themselves, ready to face whatever trials awaited them at the threshold of their potential sanctuary.</p>"
                            ],
                            [
                                'content' => "The settlement's gates loomed large before them, imposing and fortified. It was a formidable barrier, designed to keep out the dangers of the outside world. Guards stood vigilant, their eyes sharp with suspicion, as they assessed the newcomers approaching their sanctuary.

                                The group approached cautiously, their weariness masked by a facade of determination. Each step felt heavier, the weight of their hopes and fears pressing down upon them. They knew that their entry into the settlement would be no simple feat—they would need to prove their worth, demonstrate their skills, and earn the trust of those who held the key to safety.
                                
                                A stern-faced guard emerged from the shadows, his gaze piercing as he surveyed the group. His voice carried the weight of authority as he posed a series of questions, seeking to assess their intentions and determine their worthiness. They responded with a mixture of trepidation and conviction, laying bare their struggles, their resilience, and their unwavering desire for a chance at a better life.
                                
                                Their words hung heavy in the air, the silence that followed pregnant with uncertainty. The guard's eyes narrowed as he deliberated, weighing their words against the risks of welcoming outsiders into their sanctuary. The fate of the group hung in the balance, their hopes teetering on the precipice of acceptance or rejection.
                                
                                Finally, after what felt like an eternity, the guard's stoic facade softened ever so slightly. He nodded, a glimmer of approval dancing in his eyes, signaling their successful passage through the first trial. With a gesture, the gates creaked open, revealing a glimpse of the settlement beyond—a place of relative safety and the promise of a new beginning.
                                
                                As they crossed the threshold into the settlement, a mix of relief and anticipation washed over them. The streets bustled with activity, survivors going about their daily routines, seeking solace and normalcy amidst the chaos. The group marveled at the sight—the remnants of civilization pieced together in this fragile haven.
                                
                                But their journey was far from over. Within the settlement's confines, new challenges awaited them. They would need to prove their worth, contribute to the community, and navigate the intricate web of relationships and power dynamics that had formed within its walls. The struggle for shelter had brought them to this point, but their true test would be in establishing a place for themselves within the settlement's fabric.",
                                'html' => "
                                <p>The settlement's gates loomed large before them, imposing and fortified. It was a formidable barrier, designed to keep out the dangers of the outside world. Guards stood vigilant, their eyes sharp with suspicion, as they assessed the newcomers approaching their sanctuary.</p>

                                <p>The group approached cautiously, their weariness masked by a facade of determination. Each step felt heavier, the weight of their hopes and fears pressing down upon them. They knew that their entry into the settlement would be no simple feat—they would need to prove their worth, demonstrate their skills, and earn the trust of those who held the key to safety.</p>

                                <p>A stern-faced guard emerged from the shadows, his gaze piercing as he surveyed the group. His voice carried the weight of authority as he posed a series of questions, seeking to assess their intentions and determine their worthiness. They responded with a mixture of trepidation and conviction, laying bare their struggles, their resilience, and their unwavering desire for a chance at a better life.</p>

                                <p>Their words hung heavy in the air, the silence that followed pregnant with uncertainty. The guard's eyes narrowed as he deliberated, weighing their words against the risks of welcoming outsiders into their sanctuary. The fate of the group hung in the balance, their hopes teetering on the precipice of acceptance or rejection.</p>

                                <p>Finally, after what felt like an eternity, the guard's stoic facade softened ever so slightly. He nodded, a glimmer of approval dancing in his eyes, signaling their successful passage through the first trial. With a gesture, the gates creaked open, revealing a glimpse of the settlement beyond—a place of relative safety and the promise of a new beginning.</p>

                                <p>As they crossed the threshold into the settlement, a mix of relief and anticipation washed over them. The streets bustled with activity, survivors going about their daily routines, seeking solace and normalcy amidst the chaos. The group marveled at the sight—the remnants of civilization pieced together in this fragile haven.</p>

                                <p>But their journey was far from over. Within the settlement's confines, new challenges awaited them. They would need to prove their worth, contribute to the community, and navigate the intricate web of relationships and power dynamics that had formed within its walls. The struggle for shelter had brought them to this point, but their true test would be in establishing a place for themselves within the settlement's fabric.</p>"
                            ]
                        ]
                    ]
                ]
            ],
            [
                'novel' => 'uncharted-lost-treasures',
                'chapters' => [
                    [
                        'title' => 'The Search Begins',
                        'status' => 'published',
                        'page_state' => [
                            [
                                'content' => "Nathan Drake stood at the edge of a weathered cliff, the wind whipping through his tousled hair. His gaze fixated on the vast expanse before him—an uncharted territory shrouded in mystery and untold treasures. The allure of the unknown beckoned him, fueling his adventurous spirit.

                                It had been months since his last expedition, but the lure of discovering lost treasures had always been his siren's song. The thrill of the hunt, the adrenaline coursing through his veins as he unraveled the secrets of the past—it was an addiction he couldn't resist.
                                
                                This time, however, the stakes were higher. Whispers of a fabled artifact, said to possess unimaginable power, had reached his ears. Legends and myths intertwined, leading him on a trail that would challenge his skills, his wit, and his resolve.
                                
                                Nathan's trusted companion, Victor Sullivan, stood by his side. The seasoned adventurer had weathered countless storms and shared in the triumphs and losses of their past expeditions. Together, they formed an unstoppable duo—a combination of experience, resourcefulness, and a shared hunger for the thrill of discovery.
                                
                                As the sun dipped below the horizon, casting a golden hue across the landscape, Nathan's resolve solidified. The time had come to embark on a new journey, to unravel the mysteries that awaited them in the uncharted corners of the world.
                                
                                The first clue lay hidden within an ancient map—etched onto aged parchment, a testament to the long-forgotten civilizations that once thrived. It spoke of a forgotten city, buried beneath layers of time and guarded by treacherous terrain. But it also promised unimaginable riches for those bold enough to venture forth.
                                
                                With a determined gleam in his eyes, Nathan unfurled the map, tracing his finger along its faded lines. The path ahead would be treacherous, fraught with peril and adversaries seeking the same fabled treasure. But he had faced danger before, emerging victorious through cunning, agility, and a touch of luck.
                                
                                The adventure awaited, and Nathan Drake was ready to embrace it. With the weight of anticipation settled firmly on his shoulders, he took a step forward, his boots crunching against the rocky ground. The search for lost treasures had begun, and the world would never be the same.",
                                'html' => "
                                <p>Nathan Drake stood at the edge of a weathered cliff, the wind whipping through his tousled hair. His gaze fixated on the vast expanse before him—an uncharted territory shrouded in mystery and untold treasures. The allure of the unknown beckoned him, fueling his adventurous spirit.</p>

                                <p>It had been months since his last expedition, but the lure of discovering lost treasures had always been his siren's song. The thrill of the hunt, the adrenaline coursing through his veins as he unraveled the secrets of the past—it was an addiction he couldn't resist.</p>

                                <p>This time, however, the stakes were higher. Whispers of a fabled artifact, said to possess unimaginable power, had reached his ears. Legends and myths intertwined, leading him on a trail that would challenge his skills, his wit, and his resolve.</p>

                                <p>Nathan's trusted companion, Victor Sullivan, stood by his side. The seasoned adventurer had weathered countless storms and shared in the triumphs and losses of their past expeditions. Together, they formed an unstoppable duo—a combination of experience, resourcefulness, and a shared hunger for the thrill of discovery.</p>

                                <p>As the sun dipped below the horizon, casting a golden hue across the landscape, Nathan's resolve solidified. The time had come to embark on a new journey, to unravel the mysteries that awaited them in the uncharted corners of the world.</p>

                                <p>The first clue lay hidden within an ancient map—etched onto aged parchment, a testament to the long-forgotten civilizations that once thrived. It spoke of a forgotten city, buried beneath layers of time and guarded by treacherous terrain. But it also promised unimaginable riches for those bold enough to venture forth.</p>

                                <p>With a determined gleam in his eyes, Nathan unfurled the map, tracing his finger along its faded lines. The path ahead would be treacherous, fraught with peril and adversaries seeking the same fabled treasure. But he had faced danger before, emerging victorious through cunning, agility, and a touch of luck.</p>

                                <p>The adventure awaited, and Nathan Drake was ready to embrace it. With the weight of anticipation settled firmly on his shoulders, he took a step forward, his boots crunching against the rocky ground. The search for lost treasures had begun, and the world would never be the same.</p>"
                            ],
                            [
                                'content' => "Nathan Drake held the ancient map tightly in his grasp, the delicate parchment whispering secrets of forgotten lands. His eyes scanned its faded markings, searching for the first clue that would set them on their path. Lines intersected, symbols intertwined, and cryptic inscriptions hinted at the challenges they would face.

                                The journey ahead would be no easy feat. The map pointed them towards a dense jungle, teeming with untamed wildlife and hidden dangers. Ancient ruins, overgrown and swallowed by nature's embrace, lay hidden within the thick foliage. They would need to navigate treacherous terrain, overcome natural obstacles, and outwit cunning adversaries lurking in the shadows.
                                
                                Sullivan's grizzled voice broke the silence, his tone filled with caution and wisdom. \"You know, kid, this ain't gonna be a walk in the park. We're diving headfirst into the unknown, chasing legends and risking our lives. Are you sure you're up for it?\"
                                
                                Nathan's eyes met Sullivan's, determination burning brightly within them. \"I've never been more ready, Sully. This is what we live for. The thrill of discovery, the rush of unraveling history's mysteries—it's what sets our souls on fire. We'll face whatever comes our way, together.\"
                                
                                With a nod of agreement, Sullivan gripped his trusty cigar between weathered fingers, a wry smile playing on his lips. \"That's the spirit, kid. We make our own luck, and by God, we've got a knack for finding trouble. Let's give 'em a show they won't forget.\"
                                
                                Their resolve forged an unbreakable bond, their shared passion propelling them forward. With the map as their guide, they ventured deeper into the uncharted jungle, each step unveiling new wonders and challenges. The air thickened with anticipation, the very atmosphere alive with the echoes of ancient civilizations.
                                
                                Days turned into nights, and nights bled into days as they followed the trail of clues. Mysteries unfolded, revealing a tapestry of tales long forgotten. They deciphered riddles, solved enigmatic puzzles, and deciphered the secrets of forgotten languages. Their expertise in history, archaeology, and sheer tenacity propelled them closer to their goal.
                                
                                As they ventured deeper into the heart of the jungle, they encountered remnants of past explorers—broken tools, abandoned campsites, and even faded journals bearing witness to their predecessors' failures and triumphs. The weight of their footsteps mingled with the echoes of those who had come before, their spirits urging them onward.",
                                'html' => "
                                <p>Nathan Drake held the ancient map tightly in his grasp, the delicate parchment whispering secrets of forgotten lands. His eyes scanned its faded markings, searching for the first clue that would set them on their path. Lines intersected, symbols intertwined, and cryptic inscriptions hinted at the challenges they would face.</p>
                                
                                <p>The journey ahead would be no easy feat. The map pointed them towards a dense jungle, teeming with untamed wildlife and hidden dangers. Ancient ruins, overgrown and swallowed by nature's embrace, lay hidden within the thick foliage. They would need to navigate treacherous terrain, overcome natural obstacles, and outwit cunning adversaries lurking in the shadows.</p>
                                
                                <p>Sullivan's grizzled voice broke the silence, his tone filled with caution and wisdom. \"You know, kid, this ain't gonna be a walk in the park. We're diving headfirst into the unknown, chasing legends and risking our lives. Are you sure you're up for it?\"</p>

                                <p>Nathan's eyes met Sullivan's, determination burning brightly within them. \"I've never been more ready, Sully. This is what we live for. The thrill of discovery, the rush of unraveling history's mysteries—it's what sets our souls on fire. We'll face whatever comes our way, together.\"</p>

                                <p>With a nod of agreement, Sullivan gripped his trusty cigar between weathered fingers, a wry smile playing on his lips. \"That's the spirit, kid. We make our own luck, and by God, we've got a knack for finding trouble. Let's give 'em a show they won't forget.\"</p>

                                <p>Their resolve forged an unbreakable bond, their shared passion propelling them forward. With the map as their guide, they ventured deeper into the uncharted jungle, each step unveiling new wonders and challenges. The air thickened with anticipation, the very atmosphere alive with the echoes of ancient civilizations.</p>

                                <p>Days turned into nights, and nights bled into days as they followed the trail of clues. Mysteries unfolded, revealing a tapestry of tales long forgotten. They deciphered riddles, solved enigmatic puzzles, and deciphered the secrets of forgotten languages. Their expertise in history, archaeology, and sheer tenacity propelled them closer to their goal.</p>

                                <p>As they ventured deeper into the heart of the jungle, they encountered remnants of past explorers—broken tools, abandoned campsites, and even faded journals bearing witness to their predecessors' failures and triumphs. The weight of their footsteps mingled with the echoes of those who had come before, their spirits urging them onward.</p>"
                            ],
                            [
                                'content' => "The dense jungle enveloped Nathan Drake and Victor Sullivan, its lush foliage and towering trees creating an ethereal canopy. Every step forward brought them deeper into uncharted territory, where the line between reality and myth blurred, and the shadows whispered forgotten tales.

                                As they pressed on, the air grew heavy with anticipation. Nature itself seemed to guard its secrets, concealing ancient traps and treacherous pitfalls. Their senses were heightened, instincts finely tuned, as they navigated the perilous landscape with cautious steps and wary eyes.
                                
                                It was then, amidst the hushed whispers of rustling leaves, that they stumbled upon an imposing stone archway—a relic of a long-lost civilization. Carved with intricate symbols and worn by the passage of time, it beckoned them forward, promising both peril and enlightenment.
                                
                                Nathan traced his fingers along the weathered stone, his mind racing with possibilities. \"Sully, this archway... it's more than just a gateway. It's a portal to history. We're on the right track.\"
                                
                                Sullivan's keen eyes surveyed their surroundings, his voice filled with caution. \"Aye, kid, but let's not forget that we're not the only ones seeking these treasures. We'll need to watch our backs. The lure of untold riches draws out both the ambitious and the treacherous.\"
                                
                                Their journey had already brought them face to face with adversaries—ruthless mercenaries, treasure hunters, and those consumed by their own greed. The allure of the lost treasures had drawn them into a deadly dance, where alliances were fleeting and betrayal lurked around every corner.
                                
                                Nathan's grip tightened on his trusted pistol, a reminder of the dangers they faced. \"We've faced worse odds, Sully. We've come out on top before, and we'll do it again. No treasure is worth our lives, but we can't let the shadow of danger deter us.\"
                                
                                The archway loomed ahead, its enigmatic presence a testament to the mysteries waiting to be unraveled. They exchanged a knowing glance, their shared determination a silent promise to see this journey through to the end.
                                
                                With cautious steps, they crossed the threshold of the archway, leaving behind the familiar world and stepping into the unknown. The search for lost treasures had brought them here, to this pivotal moment, where legends and reality merged. They were prepared to face the trials ahead, to uncover the secrets hidden within the depths of time.",
                                'html' => "
                                <p>The dense jungle enveloped Nathan Drake and Victor Sullivan, its lush foliage and towering trees creating an ethereal canopy. Every step forward brought them deeper into uncharted territory, where the line between reality and myth blurred, and the shadows whispered forgotten tales.</p>

                                <p>As they pressed on, the air grew heavy with anticipation. Nature itself seemed to guard its secrets, concealing ancient traps and treacherous pitfalls. Their senses were heightened, instincts finely tuned, as they navigated the perilous landscape with cautious steps and wary eyes.</p>

                                <p>It was then, amidst the hushed whispers of rustling leaves, that they stumbled upon an imposing stone archway—a relic of a long-lost civilization. Carved with intricate symbols and worn by the passage of time, it beckoned them forward, promising both peril and enlightenment.</p>

                                <p>Nathan traced his fingers along the weathered stone, his mind racing with possibilities. \"Sully, this archway... it's more than just a gateway. It's a portal to history. We're on the right track.\"</p>

                                <p>Sullivan's keen eyes surveyed their surroundings, his voice filled with caution. \"Aye, kid, but let's not forget that we're not the only ones seeking these treasures. We'll need to watch our backs. The lure of untold riches draws out both the ambitious and the treacherous.\"</p>

                                <p>Their journey had already brought them face to face with adversaries—ruthless mercenaries, treasure hunters, and those consumed by their own greed. The allure of the lost treasures had drawn them into a deadly dance, where alliances were fleeting and betrayal lurked around every corner.</p>

                                <p>Nathan's grip tightened on his trusted pistol, a reminder of the dangers they faced. \"We've faced worse odds, Sully. We've come out on top before, and we'll do it again. No treasure is worth our lives, but we can't let the shadow of danger deter us.\"</p>

                                <p>The archway loomed ahead, its enigmatic presence a testament to the mysteries waiting to be unraveled. They exchanged a knowing glance, their shared determination a silent promise to see this journey through to the end.</p>

                                <p>With cautious steps, they crossed the threshold of the archway, leaving behind the familiar world and stepping into the unknown. The search for lost treasures had brought them here, to this pivotal moment, where legends and reality merged. They were prepared to face the trials ahead, to uncover the secrets hidden within the depths of time.</p>"
                            ]
                        ]
                    ],
                    [
                        'title' => 'Echoes of the Forgotten',
                        'status' => 'published',
                        'page_state' => [
                            [
                                'content' => "Nathan Drake and Victor Sullivan stood before a vast, overgrown jungle clearing. Rays of sunlight pierced through the dense canopy, casting dancing shadows upon the ancient stone ruins that lay before them. Moss-covered walls rose like silent sentinels, bearing the weight of centuries gone by.

                                Before them stood the remnants of a forgotten temple—a testament to a civilization lost in the annals of time. Intricate carvings adorned weathered walls, their meanings obscured by the passage of ages. It was a place untouched by modern hands, steeped in mystery and the echoes of forgotten tales.
                                
                                Nathan's eyes sparkled with curiosity as he surveyed the scene before him. He could almost hear the whispers of the past, the stories waiting to be unearthed. \"Sully, look at this place. It's like stepping back in time. The secrets buried within these walls could rewrite history.\"
                                
                                Sullivan nodded, his experienced gaze scanning the surroundings. \"Aye, kid. This temple holds more than just beauty. It holds the answers we seek. But be warned, ancient sanctuaries like these rarely reveal their secrets willingly. We'll need to tread carefully.\"
                                
                                The duo approached the entrance, a grand doorway flanked by weather-worn statues. As they stepped over the threshold, a sense of reverence washed over them—a recognition that they were stepping into hallowed grounds, where the footsteps of the past mingled with the present.
                                
                                Inside, the air was heavy with the scent of age and the promise of discovery. Shadows danced upon intricately tiled floors, their patterns telling stories long forgotten. The sound of dripping water echoed through the chamber, like whispers from unseen lips.
                                
                                Nathan's hand brushed against the cool surface of a stone tablet, its inscriptions worn but still legible. He traced his fingers over the grooves, his mind working to decipher the cryptic symbols. \"Every carving, every etching... they hold clues, Sully. Clues to what lies ahead, to the treasures waiting to be found.\"
                                
                                Sullivan's voice held a note of caution. \"Aye, lad, but remember, not all treasure is gold and jewels. Sometimes, the greatest reward lies in the knowledge we gain, the truths we uncover. Let's not lose sight of that.\"
                                
                                With their resolve fortified, Nathan and Sullivan delved deeper into the forgotten temple, their footsteps echoing through the corridors. They were but visitors in a world long gone, seeking the remnants of a forgotten civilization. The path ahead would be riddled with puzzles, traps, and the unknown. Yet, they were undeterred, driven by the insatiable thirst for discovery.",
                                'html' => "
                                <p>Nathan Drake and Victor Sullivan stood before a vast, overgrown jungle clearing. Rays of sunlight pierced through the dense canopy, casting dancing shadows upon the ancient stone ruins that lay before them. Moss-covered walls rose like silent sentinels, bearing the weight of centuries gone by.</p>

                                <p>Before them stood the remnants of a forgotten temple—a testament to a civilization lost in the annals of time. Intricate carvings adorned weathered walls, their meanings obscured by the passage of ages. It was a place untouched by modern hands, steeped in mystery and the echoes of forgotten tales.</p>

                                <p>Nathan's eyes sparkled with curiosity as he surveyed the scene before him. He could almost hear the whispers of the past, the stories waiting to be unearthed. \"Sully, look at this place. It's like stepping back in time. The secrets buried within these walls could rewrite history.\"</p>

                                <p>Sullivan nodded, his experienced gaze scanning the surroundings. \"Aye, kid. This temple holds more than just beauty. It holds the answers we seek. But be warned, ancient sanctuaries like these rarely reveal their secrets willingly. We'll need to tread carefully.\"</p>

                                <p>The duo approached the entrance, a grand doorway flanked by weather-worn statues. As they stepped over the threshold, a sense of reverence washed over them—a recognition that they were stepping into hallowed grounds, where the footsteps of the past mingled with the present.</p>

                                <p>Inside, the air was heavy with the scent of age and the promise of discovery. Shadows danced upon intricately tiled floors, their patterns telling stories long forgotten. The sound of dripping water echoed through the chamber, like whispers from unseen lips.</p>

                                <p>Nathan's hand brushed against the cool surface of a stone tablet, its inscriptions worn but still legible. He traced his fingers over the grooves, his mind working to decipher the cryptic symbols. \"Every carving, every etching... they hold clues, Sully. Clues to what lies ahead, to the treasures waiting to be found.\"</p>

                                <p>Sullivan's voice held a note of caution. \"Aye, lad, but remember, not all treasure is gold and jewels. Sometimes, the greatest reward lies in the knowledge we gain, the truths we uncover. Let's not lose sight of that.\"</p>

                                <p>With their resolve fortified, Nathan and Sullivan delved deeper into the forgotten temple, their footsteps echoing through the corridors. They were but visitors in a world long gone, seeking the remnants of a forgotten civilization. The path ahead would be riddled with puzzles, traps, and the unknown. Yet, they were undeterred, driven by the insatiable thirst for discovery.</p>"
                            ],
                            [
                                'content' => "As Nathan Drake and Victor Sullivan ventured further into the depths of the forgotten temple, the air grew heavy with anticipation. Torchlight flickered, casting eerie shadows upon the walls adorned with fading murals. Each step echoed through the ancient halls, reverberating with the weight of history.

                                The temple revealed itself as a labyrinth of corridors, chambers, and hidden passages. Nathan's keen eyes scanned their surroundings, his mind racing with the possibilities. \"Sully, these carvings on the walls... they depict ancient rituals, mythic creatures, and legendary figures. It's as if the very essence of this place is etched into the stone.\"
                                
                                Sullivan followed suit, his fingers brushing against intricate engravings. \"Aye, lad. These carvings offer glimpses into a civilization long gone. The stories they tell may hold the key to unlocking the secrets of this temple. We must tread carefully and decipher their meaning.\"
                                
                                As they moved deeper into the heart of the temple, the atmosphere grew more foreboding. Shafts of light pierced through narrow openings, illuminating intricate mechanisms and hidden alcoves. Every step revealed the remnants of a lost civilization, their legacy preserved in the decaying stone.
                                
                                The air whispered with the echoes of the past, tantalizing and elusive. They stumbled upon a chamber adorned with faded tapestries, their vibrant colors now muted with time. A sense of reverence washed over them as they stood in the presence of ancient relics, reminders of a world long forgotten.
                                
                                Nathan's gaze fell upon a worn pedestal, a missing artifact leaving a void in the center. \"Sully, it seems we're not the only ones seeking the treasures hidden within these walls. Someone beat us to it.\"
                                
                                Sullivan's voice held a note of caution. \"Aye, lad. We're not alone in this race. But remember, it's not about who gets there first. It's about uncovering the truth, preserving history, and doing it with integrity.\"
                                
                                Their exploration continued, each step leading them deeper into the enigmatic tapestry of the forgotten temple. They encountered puzzles that tested their wits, perilous traps that threatened their very existence, and the remnants of those who had ventured before them.
                                
                                With every passing moment, the weight of history pressed upon them. The knowledge they gained, the artifacts they uncovered—they all became pieces of a grand puzzle, revealing a story long silenced by the passage of time.",
                                'html' => "
                                <p>As Nathan Drake and Victor Sullivan ventured further into the depths of the forgotten temple, the air grew heavy with anticipation. Torchlight flickered, casting eerie shadows upon the walls adorned with fading murals. Each step echoed through the ancient halls, reverberating with the weight of history.</p>

                                <p>The temple revealed itself as a labyrinth of corridors, chambers, and hidden passages. Nathan's keen eyes scanned their surroundings, his mind racing with the possibilities. \"Sully, these carvings on the walls... they depict ancient rituals, mythic creatures, and legendary figures. It's as if the very essence of this place is etched into the stone.\"</p>

                                <p>Sullivan followed suit, his fingers brushing against intricate engravings. \"Aye, lad. These carvings offer glimpses into a civilization long gone. The stories they tell may hold the key to unlocking the secrets of this temple. We must tread carefully and decipher their meaning.\"</p>

                                <p>As they moved deeper into the heart of the temple, the atmosphere grew more foreboding. Shafts of light pierced through narrow openings, illuminating intricate mechanisms and hidden alcoves. Every step revealed the remnants of a lost civilization, their legacy preserved in the decaying stone.</p>

                                <p>The air whispered with the echoes of the past, tantalizing and elusive. They stumbled upon a chamber adorned with faded tapestries, their vibrant colors now muted with time. A sense of reverence washed over them as they stood in the presence of ancient relics, reminders of a world long forgotten.</p>

                                <p>Nathan's gaze fell upon a worn pedestal, a missing artifact leaving a void in the center. \"Sully, it seems we're not the only ones seeking the treasures hidden within these walls. Someone beat us to it.\"</p>

                                <p>Sullivan's voice held a note of caution. \"Aye, lad. We're not alone in this race. But remember, it's not about who gets there first. It's about uncovering the truth, preserving history, and doing it with integrity.\"</p>

                                <p>Their exploration continued, each step leading them deeper into the enigmatic tapestry of the forgotten temple. They encountered puzzles that tested their wits, perilous traps that threatened their very existence, and the remnants of those who had ventured before them.</p>

                                <p>With every passing moment, the weight of history pressed upon them. The knowledge they gained, the artifacts they uncovered—they all became pieces of a grand puzzle, revealing a story long silenced by the passage of time.</p>"
                            ]
                        ]
                    ]
                ]
            ],
            [
                'novel' => 'dark-souls-the-age-of-fire',
                'chapters' => [
                    [
                        'title' => 'The Age of Fire',
                        'status' => 'published',
                        'page_state' => [
                            [
                                'content' => "In a desolate land shrouded by eternal twilight, the Age of Fire flickered on the brink of extinction. The world was consumed by darkness, a bleak realm where hope had all but withered away. But amidst the fading embers, a lone figure emerged—a silent sentinel in a land ravaged by time.

                                As the chosen Undead, you stood at the precipice of destiny. Clad in worn armor and wielding a weathered blade, you were tasked with rekindling the flame that once illuminated the world. The weight of this monumental undertaking rested upon your shoulders, the fate of an entire realm hanging in the balance.
                                
                                With each step, you traversed treacherous landscapes. The remnants of once-majestic cities crumbled beneath your feet, their towering spires reduced to mere rubble. Whispering winds carried echoes of forgotten tales, warning of the perils that awaited those who dared to challenge the darkness.
                                
                                The Age of Fire, once a symbol of prosperity and life, now teetered on the edge of annihilation. The flame, which once fueled the gods and granted strength to mortals, dwindled to a mere ember, casting feeble light in a world of shadow. The very essence of existence hung in the balance.
                                
                                As you ventured deeper into the forgotten corners of this forsaken world, you encountered other lost souls—both friend and foe. Each held their own stories, their own struggles against the encroaching darkness. Some sought salvation, while others embraced the chaos, consumed by their own desires.
                                
                                The path before you was treacherous, filled with nightmarish creatures and insurmountable challenges. Yet, within the darkest depths, the glimmers of forgotten lore and ancient power awaited. It was a realm where the line between life and death blurred, where every decision carried weight and consequences echoed through the ages.
                                
                                You were not the first to undertake this harrowing journey, nor would you be the last. Legends spoke of heroes who had come before, their names etched in the annals of time. Their triumphs and failures served as a reminder of the daunting task that lay ahead—a test of your resolve, skill, and determination.",
                                'html' => "
                                <p>In a desolate land shrouded by eternal twilight, the Age of Fire flickered on the brink of extinction. The world was consumed by darkness, a bleak realm where hope had all but withered away. But amidst the fading embers, a lone figure emerged—a silent sentinel in a land ravaged by time.</p>

                                <p>As the chosen Undead, you stood at the precipice of destiny. Clad in worn armor and wielding a weathered blade, you were tasked with rekindling the flame that once illuminated the world. The weight of this monumental undertaking rested upon your shoulders, the fate of an entire realm hanging in the balance.</p>

                                <p>With each step, you traversed treacherous landscapes. The remnants of once-majestic cities crumbled beneath your feet, their towering spires reduced to mere rubble. Whispering winds carried echoes of forgotten tales, warning of the perils that awaited those who dared to challenge the darkness.</p>

                                <p>The Age of Fire, once a symbol of prosperity and life, now teetered on the edge of annihilation. The flame, which once fueled the gods and granted strength to mortals, dwindled to a mere ember, casting feeble light in a world of shadow. The very essence of existence hung in the balance.</p>

                                <p>As you ventured deeper into the forgotten corners of this forsaken world, you encountered other lost souls—both friend and foe. Each held their own stories, their own struggles against the encroaching darkness. Some sought salvation, while others embraced the chaos, consumed by their own desires.</p>

                                <p>The path before you was treacherous, filled with nightmarish creatures and insurmountable challenges. Yet, within the darkest depths, the glimmers of forgotten lore and ancient power awaited. It was a realm where the line between life and death blurred, where every decision carried weight and consequences echoed through the ages.</p>

                                <p>You were not the first to undertake this harrowing journey, nor would you be the last. Legends spoke of heroes who had come before, their names etched in the annals of time. Their triumphs and failures served as a reminder of the daunting task that lay ahead—a test of your resolve, skill, and determination.</p>"
                                
                            ],
                            [
                                'content' => "As you ventured deeper into the desolate land, the weight of the world pressed upon you. Every step carried the burden of a dying realm, every encounter a test of your resilience. The path ahead was fraught with danger, but you pressed on, driven by an unyielding determination to restore balance to a world on the brink of oblivion.

                                Within the hollowed realms, you encountered twisted creatures—horrors borne from the fading flame and the despair that permeated the land. Their eyes, devoid of life, glowed with an unholy fire, mirroring the flickering embers that threatened to snuff out.
                                
                                Your blade clashed against their grotesque forms, each strike echoing with the clash of steel and the echoes of forgotten battles. The combat was a dance of survival, a delicate balance between caution and aggression. Every victory brought you closer to rekindling the flame, while every defeat threatened to extinguish your own spirit.
                                
                                The land itself seemed to conspire against your efforts, revealing hidden traps and perilous pitfalls. Ancient ruins held untold secrets, waiting to be unraveled by those with the courage to delve deeper. In the darkness, you discovered fragments of forgotten knowledge, pieces of a puzzle that hinted at the origins of this blighted world.
                                
                                With every encounter, you witnessed the toll the Age of Fire had taken on its inhabitants. Some had succumbed to the despair, becoming hollow—empty vessels devoid of purpose or identity. Others clung desperately to the fading embers, fighting to retain their humanity against insurmountable odds.
                                
                                Through it all, a sliver of hope remained—a flicker of resilience that burned within the hearts of a few. They were the kindred souls you encountered along your arduous journey. Some offered aid and guidance, sharing their wisdom and strength. Others sought to test your mettle, to determine if you were truly worthy of the daunting task you had undertaken.",
                                'html' => "
                                <p>As you ventured deeper into the desolate land, the weight of the world pressed upon you. Every step carried the burden of a dying realm, every encounter a test of your resilience. The path ahead was fraught with danger, but you pressed on, driven by an unyielding determination to restore balance to a world on the brink of oblivion.</p>

                                <p>Within the hollowed realms, you encountered twisted creatures—horrors borne from the fading flame and the despair that permeated the land. Their eyes, devoid of life, glowed with an unholy fire, mirroring the flickering embers that threatened to snuff out.</p>

                                <p>Your blade clashed against their grotesque forms, each strike echoing with the clash of steel and the echoes of forgotten battles. The combat was a dance of survival, a delicate balance between caution and aggression. Every victory brought you closer to rekindling the flame, while every defeat threatened to extinguish your own spirit.</p>

                                <p>The land itself seemed to conspire against your efforts, revealing hidden traps and perilous pitfalls. Ancient ruins held untold secrets, waiting to be unraveled by those with the courage to delve deeper. In the darkness, you discovered fragments of forgotten knowledge, pieces of a puzzle that hinted at the origins of this blighted world.</p>

                                <p>With every encounter, you witnessed the toll the Age of Fire had taken on its inhabitants. Some had succumbed to the despair, becoming hollow—empty vessels devoid of purpose or identity. Others clung desperately to the fading embers, fighting to retain their humanity against insurmountable odds.</p>

                                <p>Through it all, a sliver of hope remained—a flicker of resilience that burned within the hearts of a few. They were the kindred souls you encountered along your arduous journey. Some offered aid and guidance, sharing their wisdom and strength. Others sought to test your mettle, to determine if you were truly worthy of the daunting task you had undertaken.</p>"
                            ]
                        ]
                    ],
                    [
                        'title' => 'Veils of Desolation',
                        'status' => 'published',
                        'page_state' => [
                            [
                                'content' => "
                                The air was heavy with a sense of foreboding as you ventured deeper into the desolate realms. The remnants of once-vibrant civilizations now stood as decaying ruins, their grandeur faded to a mere echo of their former glory. Towers that once reached the heavens now crumbled, their skeletal frames reaching out like ghostly fingers.
                                
                                Nature itself had succumbed to the ravages of the dying flame. Once lush forests now lay withered and lifeless, their branches twisted in a macabre dance. Barren wastelands stretched as far as the eye could see, their cracked earth whispering tales of despair and decay.
                                
                                Yet, amidst the desolation, faint glimmers of life persisted. Scattered settlements offered refuge to those who still clung to hope, their flickering bonfires symbolizing a fragile camaraderie in the face of annihilation. Within their walls, weary souls sought solace, forging fragile alliances and sharing tales of the world that once was.
                                
                                As you made your way through this charred landscape, you encountered new adversaries—beings born from the chaos and decay. Their eyes glowed with a malevolent hunger, their twisted forms a testament to the corruption that seeped through the land. Each encounter tested your mettle, pushing you to the limits of your skill and determination.
                                
                                Among the ruins, you discovered remnants of forgotten knowledge and lost treasures. Crumbling manuscripts offered tantalizing glimpses into the history of the realm, hinting at the secrets buried beneath layers of ash and bone. The artifacts you uncovered were not mere trinkets, but pieces of a puzzle that would shed light on the truth behind the desolation.",
                                'html' => "
                                <p>The air was heavy with a sense of foreboding as you ventured deeper into the desolate realms. The remnants of once-vibrant civilizations now stood as decaying ruins, their grandeur faded to a mere echo of their former glory. Towers that once reached the heavens now crumbled, their skeletal frames reaching out like ghostly fingers.</p>

                                <p>Nature itself had succumbed to the ravages of the dying flame. Once lush forests now lay withered and lifeless, their branches twisted in a macabre dance. Barren wastelands stretched as far as the eye could see, their cracked earth whispering tales of despair and decay.</p>

                                <p>Yet, amidst the desolation, faint glimmers of life persisted. Scattered settlements offered refuge to those who still clung to hope, their flickering bonfires symbolizing a fragile camaraderie in the face of annihilation. Within their walls, weary souls sought solace, forging fragile alliances and sharing tales of the world that once was.</p>

                                <p>As you made your way through this charred landscape, you encountered new adversaries—beings born from the chaos and decay. Their eyes glowed with a malevolent hunger, their twisted forms a testament to the corruption that seeped through the land. Each encounter tested your mettle, pushing you to the limits of your skill and determination.</p>

                                <p>Among the ruins, you discovered remnants of forgotten knowledge and lost treasures. Crumbling manuscripts offered tantalizing glimpses into the history of the realm, hinting at the secrets buried beneath layers of ash and bone. The artifacts you uncovered were not mere trinkets, but pieces of a puzzle that would shed light on the truth behind the desolation.</p>"
                            ],
                            [
                                'content' => "As you ventured deeper into the desolation, your path led you to the forsaken watchtower—a crumbling fortress that stood as a sentinel of forgotten times. Its towering walls, once impenetrable, now bore the scars of countless battles and the erosion of ages. The winds whispered through its broken windows, carrying the mournful echoes of the past.

                                With caution, you approached the towering gates, their iron hinges groaning in protest as you pushed them open. The interior revealed a scene of desolation—a relic of a forgotten conflict frozen in time. Tattered banners fluttered feebly, their once-proud emblems now faded and torn. The air hung heavy with the scent of decay and the weight of lost valor.
                                
                                Each step you took echoed through the empty halls, the sound reverberating with an unsettling emptiness. Crumbling staircases led you to higher levels, where dilapidated chambers awaited your exploration. Dust-covered tapestries whispered tales of long-dead heroes, their deeds all but forgotten.
                                
                                Within the depths of the watchtower, you encountered remnants of the past—an array of traps and hidden passages designed to repel intruders. Rusty mechanisms creaked to life, blades sprung from the walls, and ancient contraptions whirred with malevolent intent. Every corner held the promise of danger, the remnants of forgotten guardians protecting what lay within.
                                
                                Amidst the decay, you discovered a hidden chamber—a vault long sealed shut. Its massive door, etched with cryptic symbols, stood as a final barrier to the unknown. With determination, you sought a way to unlock its secrets, to reveal the treasures or knowledge that lay beyond.
                                
                                As your exploration continued, you felt the weight of the forsaken watchtower—a monument to the desolation and despair that had befallen the realm. Its crumbling walls whispered tales of lost battles, shattered dreams, and the relentless passage of time. Yet, amidst the decay, you couldn't shake the feeling that something significant awaited your discovery.",

                                'html' => "
                                <p>As you ventured deeper into the desolation, your path led you to the forsaken watchtower—a crumbling fortress that stood as a sentinel of forgotten times. Its towering walls, once impenetrable, now bore the scars of countless battles and the erosion of ages. The winds whispered through its broken windows, carrying the mournful echoes of the past.</p>

                                <p>With caution, you approached the towering gates, their iron hinges groaning in protest as you pushed them open. The interior revealed a scene of desolation—a relic of a forgotten conflict frozen in time. Tattered banners fluttered feebly, their once-proud emblems now faded and torn. The air hung heavy with the scent of decay and the weight of lost valor.</p>

                                <p>Each step you took echoed through the empty halls, the sound reverberating with an unsettling emptiness. Crumbling staircases led you to higher levels, where dilapidated chambers awaited your exploration. Dust-covered tapestries whispered tales of long-dead heroes, their deeds all but forgotten.</p>

                                <p>Within the depths of the watchtower, you encountered remnants of the past—an array of traps and hidden passages designed to repel intruders. Rusty mechanisms creaked to life, blades sprung from the walls, and ancient contraptions whirred with malevolent intent. Every corner held the promise of danger, the remnants of forgotten guardians protecting what lay within.</p>

                                <p>Amidst the decay, you discovered a hidden chamber—a vault long sealed shut. Its massive door, etched with cryptic symbols, stood as a final barrier to the unknown. With determination, you sought a way to unlock its secrets, to reveal the treasures or knowledge that lay beyond.</p>

                                <p>As your exploration continued, you felt the weight of the forsaken watchtower—a monument to the desolation and despair that had befallen the realm. Its crumbling walls whispered tales of lost battles, shattered dreams, and the relentless passage of time. Yet, amidst the decay, you couldn't shake the feeling that something significant awaited your discovery.</p>"
                            ]
                        ]
                    ],
                    [
                        'title' => 'Shadows of Despair',
                        'status' => 'published',
                        'page_state' => [
                            [
                                'content' => "The path ahead led you to the edge of a foreboding forest—the Haunted Woods. The ancient trees, gnarled and twisted, stood like sentinels guarding the secrets hidden within their dense foliage. A heavy mist hung in the air, obscuring vision and distorting sounds, heightening the sense of unease that clung to the eerie atmosphere.

                                With each step into the woods, the silence deepened, broken only by the haunting whispers carried on the wind. Strange figures darted through the undergrowth, vanishing as quickly as they appeared, leaving behind a lingering sense of unease. The forest seemed to shift and change, playing tricks on your senses and disorienting your path.
                                
                                As you forged deeper into the Haunted Woods, you discovered remnants of forgotten rituals and ancient altars, their significance lost to time. Symbols etched into weathered stones hinted at long-abandoned practices, fueling your curiosity and guiding your exploration. Every step carried you closer to the heart of the forest, where answers awaited amidst the oppressive gloom.
                                
                                The woods proved to be a formidable adversary, with hidden traps and treacherous terrain. Quicksands threatened to swallow unwary travelers, while ancient tree roots snaked across the forest floor, seeking to ensnare unsuspecting victims. Survival required keen observation and quick reflexes, as danger lurked behind every shadow.
                                
                                Amidst the haunting beauty of the Haunted Woods, you encountered lost souls trapped between realms—spectral apparitions eternally bound to this ethereal plane. Their mournful cries echoed through the trees, a chilling reminder of the despair that permeated the land. Some offered cryptic guidance, while others tested your resolve through deadly encounters.",
                                'html' => "
                                <p>The path ahead led you to the edge of a foreboding forest—the Haunted Woods. The ancient trees, gnarled and twisted, stood like sentinels guarding the secrets hidden within their dense foliage. A heavy mist hung in the air, obscuring vision and distorting sounds, heightening the sense of unease that clung to the eerie atmosphere.</p>

                                <p>With each step into the woods, the silence deepened, broken only by the haunting whispers carried on the wind. Strange figures darted through the undergrowth, vanishing as quickly as they appeared, leaving behind a lingering sense of unease. The forest seemed to shift and change, playing tricks on your senses and disorienting your path.</p>

                                <p>As you forged deeper into the Haunted Woods, you discovered remnants of forgotten rituals and ancient altars, their significance lost to time. Symbols etched into weathered stones hinted at long-abandoned practices, fueling your curiosity and guiding your exploration. Every step carried you closer to the heart of the forest, where answers awaited amidst the oppressive gloom.</p>

                                <p>The woods proved to be a formidable adversary, with hidden traps and treacherous terrain. Quicksands threatened to swallow unwary travelers, while ancient tree roots snaked across the forest floor, seeking to ensnare unsuspecting victims. Survival required keen observation and quick reflexes, as danger lurked behind every shadow.</p>

                                <p>Amidst the haunting beauty of the Haunted Woods, you encountered lost souls trapped between realms—spectral apparitions eternally bound to this ethereal plane. Their mournful cries echoed through the trees, a chilling reminder of the despair that permeated the land. Some offered cryptic guidance, while others tested your resolve through deadly encounters.</p>"
                            ],
                            [
                                'content' => "As you ventured deeper into the Haunted Woods, the forest seemed to grow denser, its shadows stretching like grasping tendrils. The trees whispered ancient laments, their branches reaching out in twisted contortions, as if yearning to ensnare intruders within their clutches.

                                A sense of foreboding settled upon you as you entered the heart of the woods—a place known as the Cursed Grove. Here, the very fabric of reality appeared to unravel, giving rise to an otherworldly realm of torment and anguish. Malevolent spirits prowled the mist-laden pathways, their mournful wails piercing the silence.
                                
                                With every step, the ground beneath you seemed to shift and writhe, as if possessed by unseen forces. Shadows danced on the edge of your vision, taunting and beguiling, while ghostly figures darted through the trees, their visages distorted and twisted by sorrow.
                                
                                The Cursed Grove revealed ancient ruins, the remnants of a forgotten civilization. Crumbling stone pillars stood as silent witnesses to a tragic past, their inscriptions worn away by time. Within the ruins, you discovered the remnants of desperate souls—trapped between life and death, forever bound by the curse that plagued the grove.
                                
                                As you delved deeper, you encountered spectral guardians, protectors of the cursed realm. These ethereal beings tested your resolve, their spectral forms phasing in and out of existence, making them elusive and formidable adversaries. Each battle was a dance of survival, where precision and timing held the key to victory.
                                
                                Amidst the chaos and despair, you uncovered fragments of forgotten lore—scrolls and manuscripts that hinted at the origin of the curse and the means to break its hold. The knowledge within these ancient texts held the promise of salvation, fueling your determination to press on despite the overwhelming darkness.",
                                'html' => "
                                <p>As you ventured deeper into the Haunted Woods, the forest seemed to grow denser, its shadows stretching like grasping tendrils. The trees whispered ancient laments, their branches reaching out in twisted contortions, as if yearning to ensnare intruders within their clutches.</p>

                                <p>A sense of foreboding settled upon you as you entered the heart of the woods—a place known as the Cursed Grove. Here, the very fabric of reality appeared to unravel, giving rise to an otherworldly realm of torment and anguish. Malevolent spirits prowled the mist-laden pathways, their mournful wails piercing the silence.</p>

                                <p>With every step, the ground beneath you seemed to shift and writhe, as if possessed by unseen forces. Shadows danced on the edge of your vision, taunting and beguiling, while ghostly figures darted through the trees, their visages distorted and twisted by sorrow.</p>

                                <p>The Cursed Grove revealed ancient ruins, the remnants of a forgotten civilization. Crumbling stone pillars stood as silent witnesses to a tragic past, their inscriptions worn away by time. Within the ruins, you discovered the remnants of desperate souls—trapped between life and death, forever bound by the curse that plagued the grove.</p>

                                <p>As you delved deeper, you encountered spectral guardians, protectors of the cursed realm. These ethereal beings tested your resolve, their spectral forms phasing in and out of existence, making them elusive and formidable adversaries. Each battle was a dance of survival, where precision and timing held the key to victory.</p>

                                <p>Amidst the chaos and despair, you uncovered fragments of forgotten lore—scrolls and manuscripts that hinted at the origin of the curse and the means to break its hold. The knowledge within these ancient texts held the promise of salvation, fueling your determination to press on despite the overwhelming darkness.</p>"
                            ]
                        ]
                    ]
                ]
            ],
            [
                'novel' => 'sekiro-shadows-of-vengeance',
                'chapters' => [
                    [
                        'title' => 'Fires of Retribution',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Silent Shadows',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Unveiling the Conspiracy',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Blades of Honor',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Path of Redemption',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Shattered Allegiances',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Hidden Fortress',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Forlorn Reflections',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Embrace of the Demon',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Vengeance Unleashed',
                        'status' => 'published',
                        'page_state' => false
                    ],
                ]
            ],
            [
                'novel' => 'fullmetal-alchemist-brotherhood-alchemys-legacy',
                'chapters' => [
                    [
                        'title' => 'The Philosopher\'s Stone',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Bound by Blood',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Flame Alchemist',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Elric Brothers',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Alchemy Exam',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Homunculus Conspiracy',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Promised Day',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Truth Revealed',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Battle for Amestris',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Battle for Amestris',
                        'status' => 'published',
                        'page_state' => false
                    ],
                ]
            ],
            [
                'novel' => 'attack-on-titan-the-fall-of-wall-maria',
                'chapters' => [
                    [
                        'title' => 'The Fall of Wall Maria',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Into the Abyss',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Humanity\'s Last Stand',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Survey Corps',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Titans Unleashed',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Secrets of the Walls',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Rise of the Beast Titan',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Betrayal and Redemption',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Battle for Shiganshina',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Revelations of the Titans',
                        'status' => 'published',
                        'page_state' => false
                    ],
                ]
            ],
            [
                'novel' => 'ao-haru-ride-a-tale-of-youthful-love',
                'chapters' => [
                    [
                        'title' => 'The Promise of Spring',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Reunited by Fate',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Fleeting Memories',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Wavering Hearts',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Embracing Change',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Echoes of the Past',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Navigating Relationships',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Torn Between Worlds',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Summer Festival',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Whispers of Doubt',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Confessions and Conflicts',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'A New Beginning',
                        'status' => 'published',
                        'page_state' => false
                    ],
                ]
            ],
            [
                'novel' => 'vagabond-the-way-of-the-sword',
                'chapters' => [
                    [
                        'title' => 'The Ronin\'s Journey Begins',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Encounters on the Path',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Lessons of the Blade',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'A Duel of Honor',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Seeking Inner Strength',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Unveiling the Samurai Code',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Test of Resolve',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Wandering Swordsman',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'A Clash of Warriors',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Paths Converge',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'In the Shadow of Legends',
                        'status' => 'published',
                        'page_state' => false
                    ],
                ]
            ],
            [
                'novel' => 'death-note-the-power-of-the-death-note',
                'chapters' => [
                    [
                        'title' => 'The Power of the Death Note',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Deadly Game Begins',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Battle of Wits',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Unraveling the Secrets',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Pursuit of Kira',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Unforgiving Justice',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Masks and Manipulations',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Tangled Alliances',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Looming Shadows',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'A Clash of Titans',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Kira Investigation Intensifies',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Consequences and Sacrifices',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Breaking Point',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Into the Darkness',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Final Judgement',
                        'status' => 'published',
                        'page_state' => false
                    ],
                ]
            ],
            [
                'novel' => 'the-fault-in-our-stars',
                'chapters' => [
                    [
                        'title' => 'The Unexpected Encounter',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Shared Fears and Shared Dreams',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Finding Solace in Amsterdam',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'The Boundless Infinity of Okay',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Promises in the Night Sky',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Fleeting Eternity',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Unraveling Fragile Stars',
                        'status' => 'published',
                        'page_state' => false
                    ],
                    [
                        'title' => 'Infinite Love, Finite Time',
                        'status' => 'published',
                        'page_state' => false
                    ],
                ]
            ]
        ];
        foreach ($chapters as $key => $chapter) {
            $novel = $manager->getRepository(Novel::class)->findOneBy(['slug' => $chapter['novel']]);
            foreach ($chapter['chapters'] as $key => $chapter) {
                $chapterEntity = new Chapter();
                $chapterEntity->setTitle($chapter['title']);
                $chapterEntity->setStatus($chapter['status']);
                $chapterEntity->setNovel($novel);
                $manager->persist($chapterEntity);
                $manager->flush();

                $pages = [];
                if ($chapter['page_state']) {
                    foreach ($chapter['page_state'] as $key => $page) {
                        $pageEntity = new Page();
                        $pageEntity->setContent($page['content']);
                        $pageEntity->setHtml($page['html']);
                        $pageEntity->setChapter($chapterEntity);
                        $manager->persist($pageEntity);
                        $manager->flush();

                        $pages[] = $pageEntity->getId();
                    }
                }
                $chapterEntity->setPageState($pages);
                $manager->persist($chapterEntity);
                $manager->flush();
            }
        }
        $manager->flush();
    }

    private function loadLikes(ObjectManager $manager) {
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

    private function loadComments(ObjectManager $manager) {
        $this->faker = Factory::create();
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
                $comment->setContent($this->faker->text(200));
                $manager->persist($comment);
                $manager->flush();
            }
        }
    }
}
