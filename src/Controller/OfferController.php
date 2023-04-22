<?php

namespace App\Controller;

use App\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/offer')]
class OfferController extends AbstractController
{
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    #[Route('/', name: 'get_active_offer', methods: ['GET'])]
    public function getActiveOffer()
    {
        $offers = $this->em->getRepository(Offer::class)->findBy(['active' => true]);
        $offers = $this->serializer->serialize($offers, 'json', ['groups' => 'offer:get']);

        return new JsonResponse($offers, Response::HTTP_OK, [], true);
    }
}
