<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ImageFixture extends Fixture {
    
    public function load(ObjectManager $manager)
    {
        $this->loadImages($manager);
    }

    public static function loadImages(ObjectManager $manager)
    {
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
}