<?php

namespace App\Controller;

use App\Entity\Category;
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
    public function __construct(
        private SecurityAuth $security,
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

    #[Route('/{id}', name: 'get_category',  methods: ['GET'])]
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

        if (isset($data['parent'])) {
            $parent = $this->categoryRepository->find($data['parent']);
            $category->setParent($parent);
        }

        $this->em->persist($category);
        $this->em->flush();
        
        $category = $this->serializerInterface->serialize($category, 'json', ['groups' => 'category:post']);
        return new JsonResponse($category, 201, [], true);
    }

    #[Route('/{id}', name: 'edit_category', methods: ['PUT']), IsGranted("ROLE_ADMIN")]
    public function edit($id, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $category->setName($data['name']);

        if (isset($data['parent'])) {
            $parent = $this->categoryRepository->find($data['parent']);
            $category->setParent($parent);
        } 

        $this->em->persist($category);
        $this->em->flush();
        
        $category = $this->serializerInterface->serialize($category, 'json', ['groups' => 'category:post']);
        return new JsonResponse($category, 200, [], true);
    }

    #[Route('/{id}', name: 'delete_category', methods: ['DELETE']), IsGranted("ROLE_ADMIN")]
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
