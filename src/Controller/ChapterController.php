<?php

namespace App\Controller;

use Exception;
use App\Entity\Order;
use App\Entity\Chapter;
use App\Repository\PageRepository;
use App\Repository\NovelRepository;
use App\Repository\ChapterRepository;
use App\Services\NovelRelationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChapterController extends AbstractController
{
    private $em;
    private $chapterRepo;
    private $novelRepo;
    private $pageRepo;

    public function __construct(EntityManagerInterface $em, ChapterRepository $chapterRepo , NovelRepository $novelRepo, PageRepository $pageRepo, NovelRelationService $novelRelationService, private SecurityAuth $security){
        $this->em = $em;
        $this->chapterRepo = $chapterRepo;
        $this->novelRepo = $novelRepo;
        $this->pageRepo = $pageRepo;
        $this->novelRelationService = $novelRelationService;
    }

    #[Route('/chapter', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function createChapter(Request $request, SerializerInterface $serializer){
        $data = json_decode($request->getContent(),true);
        $chapter = new Chapter();
        $novel = $this->novelRepo->find($data["novel"]);
        if (!$novel) {
            return $this->json(['error' => 'Not found novel, id: '. $data["novel"]], 404);
        }
        $user = $this->security->getUser();
        
        if (!$this->novelRelationService->isUserAuthorized($novel, $user)) {
            return $this->json(['error' => 'Vous n\'êtes pas l\'autheur de ce roman, vous ne pouvez pas créer un chapitre : '. $novel->getId()], 403);
        }
        $chapter->setTitle($data["title"]);
        $chapter->setStatus($data["status"]);
        $chapter->setNovel($novel);
        $this->em->persist($chapter);
        $this->em->flush();
        $json = $serializer->serialize($chapter, 'json', ['groups' => 'chapter:read']);
        return new JsonResponse($json, 201, [], true);
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

    #[Route('/chapter/{id}', methods: ['PUT']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function updateChapter(int $id, Request $request, SerializerInterface $serializer){
        $data = json_decode($request->getContent(),true);
        $chapter = $this->chapterRepo->find($id);
        if (!$chapter) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $novel = $chapter->getNovel();
        $user = $this->security->getUser();
        
        if (!$this->novelRelationService->isUserAuthorized($novel, $user)) {
            return $this->json(['error' => 'Vous n\'êtes pas l\'autheur de ce roman, vous ne pouvez pas modifier un chapitre : '. $novel->getId()], 403);
        }

        $chapter->setTitle($data["title"]);
        $chapter->setStatus($data["status"]);
        $chapter->setPageState($data["pageState"]);
        $this->em->persist($chapter);
        $this->em->flush();
        $json = $serializer->serialize($chapter, 'json', ['groups' => 'chapter:read']);
        return new JsonResponse($json, 200, [], true);
    }

    // private function addPageToChapter(int $id){
       
    //     $chapter = $this->chapterRepo->find($id);
        
    //     $pageState = $chapter->getPageState();
    //     $chapter->setPageState($data["newPageId"]);
    //     $this->em->persist($chapter);
    //     $this->em->flush();
    //     $json = $serializer->serialize($chapter, 'json', ['groups' => 'chapter:read']);
    //     return new JsonResponse($json, 202, [], true);
    // }

    #[Route('/chapter/{id}', methods: ['DELETE']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function deleteChapter(int $id ){
        $chapter = $this->chapterRepo->find($id);
        if (!$chapter) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $novel = $chapter->getNovel();
        $user = $this->security->getUser();

        if (!$this->novelRelationService->isUserAuthorized($novel, $user)) {
            return $this->json(['error' => 'Vous éte pas l\'author de cette novel du coup vous ne pouver pas le supprimer : '. $novel->getId()], 403);
        }

        $this->em->remove($chapter);
        $this->em->flush();
        return new Response("no content", 204);
    }

    #[Route('/chapter_pages/{id}', methods: ['GET']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function getChapterPages(int $id ){
        $chapter = $this->chapterRepo->find($id);
        if (!$chapter) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $novel = $chapter->getNovel();
        $allChapters = $novel->getChapters();
        $firstChapter = false;
        // is first chapter ?
        if($allChapters[0]->getId() == $chapter->getId()){
            $firstChapter = true;
        }

        if ($firstChapter === false) {
            $user = $this->security->getUser();
            $order = $this->em->getRepository(Order::class)
                        ->findOneBy([
                            "user" => $user, 
                            "novel" => $novel
                        ]);
            if (!$order) {
                return $this->json(['error' => 'You haven\'t buy this novel, so you can\'t read it'], 403);
            }
        }
        
        $pageState = $chapter->getPageState();
        $pages = [];
        foreach ($pageState as $pageId) {
            $page= $this->pageRepo->find($pageId);
            array_push($pages,$page->toArray());
        }

        

        $arrayResponse = [
            "novelTitle" => $chapter->getNovel()->getTitle(),
            "chapterTitle" => $chapter->getTitle(),
            "pageState" => $pageState, 
            "pages" => $pages,
            "firstChapter" => $firstChapter
        ];
        
        return new JsonResponse(json_encode($arrayResponse),200, [], true);
    }
    
}