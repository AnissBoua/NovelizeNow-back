<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Novel;
use App\Entity\Order;
use PHPUnit\Util\Json;
use App\Entity\Chapter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/home')]
class HomeController extends AbstractController
{
    private const CHAPTERS_PAGE_SIZE = 8;

    public function __construct(
        EntityManagerInterface $em,
        private SerializerInterface $serializer,
        private SecurityAuth $securityAuth,
    ) {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    #[Route('/', name: 'app_home')]
    public function index()
    {
        $data = [];
        $data['carousel'] = $this->getCarousel();
        $data['chapters'] = $this->getLastChapters(self::CHAPTERS_PAGE_SIZE, 0);
        $data['newNovels'] = $this->getNewNovels();
        $data['freeChaptersCount'] = $this->em->getRepository(Novel::class)->count(['status' => 'published']);
        $data['totalCategoriesCount'] = $this->em->getRepository(Category::class)->count([]);
        $data = json_decode($this->serializer->serialize($data, 'json', ['groups' => 'home:get']), true);

        $data['categories'] = $this->getBestCategoriesNovels();

        $data = json_encode($data);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/chapters', name: 'app_home_chapters')]
    public function chapters(Request $request)
    {
        $offset = max(0, (int) $request->query->get('offset', 0));
        $chapters = $this->getLastChapters(self::CHAPTERS_PAGE_SIZE, $offset);

        $data = [
            'chapters' => $chapters,
            'hasMore' => count($chapters) === self::CHAPTERS_PAGE_SIZE,
        ];
        $data = json_decode($this->serializer->serialize($data, 'json', ['groups' => 'home:get']), true);

        return new JsonResponse(json_encode($data), Response::HTTP_OK, [], true);
    }

    private function getCarousel()
    {
        $carousel = $this->em->getRepository(Novel::class)->findMostLikedAndCommentedNovels(5);
        return $carousel;
    }

    private function getLastChapters($limit, $offset)
    {
        $lastChapters = $this->em->getRepository(Chapter::class)->findLastChapters($limit, $offset);

        $user = $this->securityAuth->getUser();
        $boughtNovelIds = [];
        if ($user) {
            $novelIds = array_column($lastChapters, 'novel_id');
            if ($novelIds) {
                $rows = $this->em->getRepository(Order::class)->createQueryBuilder('o')
                    ->select('IDENTITY(o.novel) as novel_id')
                    ->andWhere('o.user = :user')
                    ->andWhere('o.novel IN (:novelIds)')
                    ->setParameter('user', $user)
                    ->setParameter('novelIds', $novelIds)
                    ->getQuery()
                    ->getScalarResult();
                $boughtNovelIds = array_map('intval', array_column($rows, 'novel_id'));
            }
        }

        foreach ($lastChapters as $key => $chapter) {
            $novel = $this->em->getRepository(Novel::class)->find($chapter['novel_id']);
            $firstChapter = $this->em->getRepository(Chapter::class)->findOneBy(
                ['novel' => $novel, 'status' => 'published'],
                ['id' => 'ASC']
            );
            $isAuthor = $user && $novel->getAuthor() && $novel->getAuthor()->getId() === $user->getId();

            $chapter['novel'] = $novel;
            $chapter['price'] = $novel->getPrice();
            $chapter['unlocked'] = ($firstChapter && $firstChapter->getId() === $chapter['id'])
                || in_array($novel->getId(), $boughtNovelIds, true)
                || $isAuthor;
            unset($chapter['novel_id']);
            $lastChapters[$key] = $chapter;
        }
        return $lastChapters;
    }

    private function getBestCategoriesNovels()
    {
        $bestCategoriesNovels = $this->em->getRepository(Category::class)->findBestCategoriesNovels(5);

        return array_map(function ($entry) {
            $category = json_decode(
                $this->serializer->serialize($entry['category'], 'json', ['groups' => ['home:categories']]),
                true
            );
            $category['novelCount'] = $entry['novelCount'];
            return $category;
        }, $bestCategoriesNovels);
    }

    private function getNewNovels()
    {
        $newNovels = $this->em->getRepository(Novel::class)->findBy(['status' => 'published'], ['id' => 'DESC'], 8);
        return $newNovels;
    }
}
