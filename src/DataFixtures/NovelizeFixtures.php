<?php

namespace App\DataFixtures;

use App\DataFixtures\CategoryFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class NovelizeFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        CategoryFixtures::loadCategories($manager);
        ImageFixture::loadImages($manager);
        NovelFixture::loadNovels($manager);
        UserFixture::loadUsers($manager);
        UserNovelFixture::loadUserNovels($manager);
        OfferFixture::loadOffers($manager);
        ChapterFixture::loadChapters($manager);
        LikeFixture::loadLikes($manager);
        CommentFixture::loadComments($manager);
    }

    public static function getGroups(): array
    {
        return ['novelize'];
    }
}
