<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Category;
use App\Services\NovelCardBuilder;
use App\Repository\NovelRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/category')]
class CategoryController extends AbstractController
{
    private const PAGE_SIZE = 24;

    public function __construct(
        private SecurityAuth $security,
        private NovelRepository $novelRepository,
        private NovelCardBuilder $novelCardBuilder,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $em,
        SerializerInterface $serializerInterface
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->em = $em;
        $this->serializerInterface = $serializerInterface;
    }

    #[Route('/', name: 'getAll_category',  methods: ['GET'])]
    public function getAll()
    {
        $categories = $this->categoryRepository->findAll();

        $categories = $this->serializerInterface->serialize($categories, 'json', ['groups' => 'category:get']);
        return new JsonResponse($categories, 200, [], true);
    }

    #[Route('/overview', name: 'category_overview', methods: ['GET'])]
    public function overview()
    {
        $stats = $this->categoryRepository->findAllWithPublishedStats();

        $ranked = array_values(array_filter($stats, fn($category) => $category['novelCount'] > 0));
        usort($ranked, fn($a, $b) => [$b['likesCount'], $b['novelCount']] <=> [$a['likesCount'], $a['novelCount']]);

        $featured = array_map(function ($category) {
            $novels = $this->novelRepository->findTopLikedInCategory($category['id'], 4);
            $category['novels'] = array_map(fn($novel) => $this->novelCardBuilder->build($novel), $novels);
            return $category;
        }, array_slice($ranked, 0, 3));

        return $this->json([
            'categories' => array_values($stats),
            'featured' => $featured,
        ]);
    }

    #[Route('/{id}/novels', name: 'category_novels', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function novels(int $id, Request $request)
    {
        $stats = $this->categoryRepository->findAllWithPublishedStats();
        if (!isset($stats[$id])) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $progress = $request->query->get('status', 'all');
        $freeOnly = $request->query->getBoolean('free');
        $sort = $request->query->get('sort', 'popular');
        $offset = max(0, $request->query->getInt('offset'));

        $total = count($this->novelRepository->createPublishedInCategoryQuery($id, $progress, $freeOnly)
            ->select('n.id')
            ->getQuery()
            ->getScalarResult());

        $qb = $this->novelRepository->createPublishedInCategoryQuery($id, $progress, $freeOnly);
        if ($sort === 'updated') {
            $qb->addSelect('MAX(ch.dateCreation) AS HIDDEN lastUpdate')->orderBy('lastUpdate', 'DESC');
        } elseif ($sort === 'new') {
            $qb->orderBy('n.publishedAt', 'DESC');
        } else {
            $qb->addSelect('COUNT(DISTINCT l.id) AS HIDDEN likeCount')->orderBy('likeCount', 'DESC');
        }
        $novels = $qb->addOrderBy('n.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults(self::PAGE_SIZE)
            ->getQuery()
            ->getResult();

        $related = array_map(function ($row) use ($stats) {
            $related = $stats[$row['id']];
            return ['id' => $related['id'], 'name' => $related['name'], 'novelCount' => $related['novelCount']];
        }, $this->categoryRepository->findRelated($id, 6));

        $category = $stats[$id];
        $category['newChaptersThisWeek'] = $this->em->getRepository(Chapter::class)
            ->countPublishedInCategorySince($id, new \DateTime('-7 days'));

        return $this->json([
            'category' => $category,
            'total' => $total,
            'novels' => array_map(fn($novel) => $this->novelCardBuilder->build($novel), $novels),
            'related' => $related,
        ]);
    }

    #[Route('/{id}', name: 'get_category',  methods: ['GET'], requirements: ['id' => '\d+'])]
    public function get($id)
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $category = $this->serializerInterface->serialize($category, 'json', ['groups' => 'category:get']);
        return new JsonResponse($category, 200, [], true);
    }

    #[Route('/', name: 'add_category', methods: ['POST']), IsGranted("ROLE_ADMIN")]
    public function post(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $category = new Category;
        $category->setName($data['name']);
        $category->setDescription($data['description'] ?? null);
        $category->setIcon($data['icon'] ?? null);

        if (isset($data['parent'])) {
            $parent = $this->categoryRepository->find($data['parent']);
            $category->setParent($parent);
        }

        $this->em->persist($category);
        $this->em->flush();
        
        $category = $this->serializerInterface->serialize($category, 'json', ['groups' => 'category:post']);
        return new JsonResponse($category, 201, [], true);
    }

    #[Route('/{id}', name: 'edit_category', methods: ['PUT'], requirements: ['id' => '\d+']), IsGranted("ROLE_ADMIN")]
    public function edit($id, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $category->setName($data['name']);
        if (array_key_exists('description', $data)) {
            $category->setDescription($data['description']);
        }
        if (array_key_exists('icon', $data)) {
            $category->setIcon($data['icon']);
        }

        if (isset($data['parent'])) {
            $parent = $this->categoryRepository->find($data['parent']);
            $category->setParent($parent);
        }

        $this->em->persist($category);
        $this->em->flush();
        
        $category = $this->serializerInterface->serialize($category, 'json', ['groups' => 'category:post']);
        return new JsonResponse($category, 200, [], true);
    }

    #[Route('/{id}', name: 'delete_category', methods: ['DELETE'], requirements: ['id' => '\d+']), IsGranted("ROLE_ADMIN")]
    public function delete($id)
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }
        $this->em->remove($category);
        $this->em->flush();
        
        return $this->json(['response' => 'Deleted succesfully'], 200);
    }

}
