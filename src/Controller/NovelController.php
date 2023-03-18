<?php

namespace App\Controller;

use DateTime;
use App\Entity\Novel;
use App\Repository\NovelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/novel')]
class NovelController extends AbstractController
{
    public function __construct(EntityManagerInterface $em, NovelRepository $novelRepository, private SecurityAuth $security)
    {
        $this->em = $em;
        $this->novelRepository = $novelRepository;
    }

    #[Route('/', name: 'new_novel', methods: ['POST'])]
    public function post(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $novel = new Novel;
        $novel->setTitle($data['title']);
        $novel->setDateCreation(new DateTime());
        $this->em->persist($novel);
        $this->em->flush();
        return $this->json(['response' => 'Created succesfully'], 201);
    }

    #[Route('/{id}', name: 'get_novel', methods: ['GET'])]
    public function get($id)
    {
        $novel = $this->novelRepository->find($id);
        if (!$novel) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }
        return $this->json($novel, 200);
    }

    #[Route('/', name: 'get_all_novel', methods: ['GET'])]
    public function getAll()
    {
        $novels = $this->novelRepository->findAll();
        return $this->json($novels, 200);
    }

    #[Route('/{id}', name: 'edit_novel', methods: ['PUT'])]
    public function edit($id, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        
        $novel = $this->novelRepository->find($id);
        if (!$novel) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }
        $novel->setTitle($data['title']);
        $novel->setDateUpdate(new DateTime());
        $this->em->persist($novel);
        $this->em->flush();
        return $this->json($novel, 200);
    }

    #[Route('/{id}', name: 'delete_novel', methods: ['DELETE']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function delete($id)
    {
        $novel = $this->novelRepository->find($id);

        if (!$novel) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }
        $user = $this->security->getUser();

        $this->em->remove($novel);
        $this->em->flush();

        return $this->json(['response' => 'Deleted succesfully'], 200);
    }
}
