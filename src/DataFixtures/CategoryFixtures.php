<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture {
    
    public function load(ObjectManager $manager)
    {
        $this->loadCategories($manager);
    }

    public static function loadCategories(ObjectManager $manager)
    {
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
}