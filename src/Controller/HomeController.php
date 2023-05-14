<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Novel;
use PHPUnit\Util\Json;
use App\Entity\Chapter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/home')]
class HomeController extends AbstractController
{
    public function __construct(EntityManagerInterface $em, private SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    #[Route('/', name: 'app_home')]
    public function index()
    {
        $data = [];
        $data['carousel'] = $this->getCarousel();
        $data['chapters'] = $this->getLastChapters();
        $data['newNovels'] = $this->getNewNovels();
        $data = json_decode($this->serializer->serialize($data, 'json', ['groups' => 'home:get']), true);
        
        $categories = $this->getBestCategoriesNovels();
        $categories = json_decode($this->serializer->serialize($categories, 'json', ['groups' => ['home:categories']]));
        $data['categories'] = $categories;

        $data = json_encode($data);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    private function getCarousel()
    {
        $carousel = $this->em->getRepository(Novel::class)->findMostLikedAndCommentedNovels(5);
        return $carousel;
    }

    private function getLastChapters()
    {
        $lastChapters = $this->em->getRepository(Chapter::class)->findBy([], ['id' => 'DESC'], 8);
        return $lastChapters;
    }

    private function getBestCategoriesNovels()
    {
        $bestCategoriesNovels = $this->em->getRepository(Category::class)->findBestCategoriesNovels(5);
        return $bestCategoriesNovels;
    }

    private function getNewNovels()
    {
        $newNovels = $this->em->getRepository(Novel::class)->findBy([], ['id' => 'DESC'], 8);
        return $newNovels;
    }
}
