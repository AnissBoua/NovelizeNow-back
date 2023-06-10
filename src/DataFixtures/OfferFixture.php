<?php

namespace App\DataFixtures;

use App\Entity\Offer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class OfferFixture extends Fixture {
    
    public function load(ObjectManager $manager)
    {
        $this->loadOffers($manager);
    }

    public static function loadOffers(ObjectManager $manager)
    {
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
}