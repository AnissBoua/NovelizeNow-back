<?php

namespace App\Controller;

use DateTime;
use App\Entity\Novel;
use App\Entity\UserNovel;
use App\Middleware\NovelRelationMiddleware;
use App\Repository\NovelRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/novel')]
class NovelController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $em, 
        NovelRepository $novelRepository, 
        UserRepository $userRepository, 
        private SecurityAuth $security,
        NovelRelationMiddleware $novelRelationMiddleware
    )
    {
        $this->em = $em;
        $this->novelRepository = $novelRepository;
        $this->userRepository = $userRepository;
        $this->novelRelationMiddleware = $novelRelationMiddleware;
    }

    #[Route('/', name: 'new_novel', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function post(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $novel = new Novel;
        $novel->setTitle($data['title']);
        $novel->setDateCreation(new DateTime());
       
        $user = $this->userRepository->find($this->security->getUser()->getId());

        $user_novel = new UserNovel;
        $user_novel->setNovel($novel);
        $user_novel->setUser($user);
        $user_novel->setRelation('author');

        $this->em->persist($novel);
        $this->em->persist($user_novel);
        $this->em->flush();
        
        return $this->json(['response' => 'Created succesfully'], 201);
    }

    #[Route('/{id}', name: 'get_novel', methods: ['GET'])]
    public function get($id, SerializerInterface $serializerInterface)
    {
        $novel = $this->novelRepository->find($id);
        if (!$novel) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }
        $novel = $serializerInterface->serialize($novel, 'json', ['groups' => 'novel:get']);
        return new JsonResponse($novel, 200,  [], true);
    }

    #[Route('/', name: 'get_all_novel', methods: ['GET'])]
    public function getAll(SerializerInterface $serializerInterface)
    {
        $novels = $this->novelRepository->findAll();
        $novels = $serializerInterface->serialize($novels, 'json', ['groups' => 'novel:get']);
        return new JsonResponse($novels, 200,  [], true);
    }

    #[Route('/{id}', name: 'edit_novel', methods: ['PUT']),  Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function edit($id, Request $request, SerializerInterface $serializerInterface)
    {
        $data = json_decode($request->getContent(), true);
        
        $novel = $this->novelRepository->find($id);
        if (!$novel) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $user = $this->security->getUser();

        if (!$this->novelRelationMiddleware->isUserAuthorized($novel, $user)) {
            return $this->json(['error' => 'Vous éte pas l\'author de cette novel du coup vous ne pouver pas le supprimer : '. $novel->getId()], 404);
        }

        $novel->setTitle($data['title']);
        $novel->setDateUpdate(new DateTime());
        $this->em->persist($novel);
        $this->em->flush();
        $novel = $serializerInterface->serialize($novel, 'json', ['groups' => 'novel:edit']);
        return new JsonResponse($novel, 200,  [], true);
    }

    #[Route('/{id}', name: 'delete_novel', methods: ['DELETE']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function delete($id)
    {
        $novel = $this->novelRepository->find($id);

        if (!$novel) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $user = $this->security->getUser();
        if (!$this->novelRelationMiddleware->isUserAuthorized($novel, $user)) {
            return $this->json(['error' => 'Vous éte pas l\'author de cette novel du coup vous ne pouver pas le supprimer : '. $novel->getId()], 404);
        }

        $this->em->remove($novel);
        $this->em->flush();

        return $this->json(['response' => 'Deleted succesfully'], 200);
    }
}
