<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Repository\NovelRepository;
use App\Repository\ChapterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChapterController extends AbstractController
{
    private $em;
    private $chapterRepo;
    private $novelRepo;

    public function __construct(EntityManagerInterface $em, ChapterRepository $chapterRepo , NovelRepository $novelRepo){
        $this->em = $em;
        $this->chapterRepo = $chapterRepo;
        $this->novelRepo = $novelRepo;
    }

    #[Route('/chapter', methods: ['POST'])]
    public function createChapter(Request $request, SerializerInterface $serializer){
        $data = json_decode($request->getContent(),true);
        $chapter = new Chapter();
        $chapter->setTitle($data["title"]);
        $chapter->setStatus($data["status"]);
        $novel = $this->novelRepo->find($data["novel"]);
        $chapter->setNovel($novel);
        $this->em->persist($chapter);
        $this->em->flush();
        $json = $serializer->serialize($chapter, 'json', ['groups' => 'chapter:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/chapter/{id}', methods: ['GET'])]
    public function getChapter(int $id, SerializerInterface $serializer){
        $chapter = $this->chapterRepo->find($id);
        if (!$chapter) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }
        $json = $serializer->serialize($chapter, 'json', ['groups' => 'chapter:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/chapter/{id}', methods: ['PUT'])]
    public function updateChapter(int $id, Request $request, SerializerInterface $serializer){
        $data = json_decode($request->getContent(),true);
        $chapter = $this->chapterRepo->find($id);
        if (!$chapter) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }
        $chapter->setTitle($data["title"]);
        $chapter->setStatus($data["status"]);
        $chapter->setPageState($data["pageState"]);
        $this->em->persist($chapter);
        $this->em->flush();
        $json = $serializer->serialize($chapter, 'json', ['groups' => 'chapter:read']);
        return new JsonResponse($json, 202, [], true);
    }

    #[Route('/chapter/{id}', methods: ['DELETE'])]
    public function deleteChapter(int $id){
        $chapter = $this->chapterRepo->find($id);
        if (!$chapter) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }
        $this->em->remove($chapter);
        $this->em->flush();
        return new Response("no content", 204);
    }
    
}