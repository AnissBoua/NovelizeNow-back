<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Novel;
use App\Entity\Order;
use App\Entity\Follow;
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
        $data['publishedNovelsCount'] = (int) $this->em->getRepository(Novel::class)->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->where('n.publishedAt IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();
        // Each published novel gives away its first published chapter, so a novel with none yet has no free chapter.
        $data['freeChaptersCount'] = (int) $this->em->getRepository(Novel::class)->createQueryBuilder('n')
            ->select('COUNT(DISTINCT n.id)')
            ->join('n.chapters', 'c')
            ->where('n.publishedAt IS NOT NULL')
            ->andWhere("c.status = 'published'")
            ->getQuery()
            ->getSingleScalarResult();
        $data['totalCategoriesCount'] = $this->em->getRepository(Category::class)->count([]);
        $data = json_decode($this->serializer->serialize($data, 'json', ['groups' => 'home:get']), true);

        $data['chapters'] = $this->attachFollowStateToChapters($data['chapters']);
        $data['newNovels'] = $this->attachFollowStateToNovels($data['newNovels']);
        $data['categories'] = $this->getTopCategories();

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
        $data['chapters'] = $this->attachFollowStateToChapters($data['chapters']);

        return new JsonResponse(json_encode($data), Response::HTTP_OK, [], true);
    }

    private function attachFollowStateToChapters(array $chapters): array
    {
        $authorIds = [];
        foreach ($chapters as $chapter) {
            if (isset($chapter['novel']['author']['id'])) {
                $authorIds[$chapter['novel']['author']['id']] = true;
            }
        }

        $followedIds = $this->getFollowedAuthorIds(array_keys($authorIds));

        foreach ($chapters as &$chapter) {
            if (isset($chapter['novel']['author']['id'])) {
                $chapter['novel']['author']['isFollowing'] = in_array($chapter['novel']['author']['id'], $followedIds, true);
            }
        }

        return $chapters;
    }

    private function attachFollowStateToNovels(array $novels): array
    {
        $authorIds = [];
        foreach ($novels as $novel) {
            if (isset($novel['author']['id'])) {
                $authorIds[$novel['author']['id']] = true;
            }
        }

        $followedIds = $this->getFollowedAuthorIds(array_keys($authorIds));

        foreach ($novels as &$novel) {
            if (isset($novel['author']['id'])) {
                $novel['author']['isFollowing'] = in_array($novel['author']['id'], $followedIds, true);
            }
        }

        return $novels;
    }

    private function getFollowedAuthorIds(array $authorIds): array
    {
        $user = $this->securityAuth->getUser();
        if (!$user || !$authorIds) {
            return [];
        }

        $rows = $this->em->getRepository(Follow::class)->createQueryBuilder('f')
            ->select('IDENTITY(f.author) as author_id')
            ->andWhere('f.follower = :user')
            ->andWhere('f.author IN (:authorIds)')
            ->setParameter('user', $user)
            ->setParameter('authorIds', $authorIds)
            ->getQuery()
            ->getScalarResult();

        return array_map('intval', array_column($rows, 'author_id'));
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

    private function getTopCategories(): array
    {
        $categories = array_values(array_filter(
            $this->em->getRepository(Category::class)->findAllWithPublishedStats(),
            fn($category) => $category['novelCount'] > 0
        ));
        usort($categories, fn($a, $b) => [$b['novelCount'], $b['likesCount']] <=> [$a['novelCount'], $a['likesCount']]);

        return array_slice($categories, 0, 5);
    }

    private function getNewNovels()
    {
        $newNovels = $this->em->getRepository(Novel::class)->createQueryBuilder('n')
            ->where('n.publishedAt IS NOT NULL')
            ->orderBy('n.publishedAt', 'DESC')
            ->addOrderBy('n.id', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();
        return $newNovels;
    }
}
