<?php

namespace App\Controller;

use App\Entity\Page;
use App\Repository\PageRepository;
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

class PageController extends AbstractController
{
    private $em;
    private $pageRepo;
    private $chapterRepo;

    public function __construct(EntityManagerInterface $em, PageRepository $pageRepo, ChapterRepository $chapterRepo, NovelRelationService $novelRelationService , private SecurityAuth $security){
        $this->em = $em;
        $this->pageRepo = $pageRepo;
        $this->chapterRepo = $chapterRepo;
        $this->novelRelationService = $novelRelationService;
    }

    #[Route('/page', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function createPage(Request $request, SerializerInterface $serializer){
        $data = json_decode($request->getContent(),true);
        $page = new Page();
        $chapter = $this->chapterRepo->find($data["chapter"]);
        $novel = $chapter->getNovel();
        $user = $this->security->getUser();

        if (!$this->novelRelationService->isUserAuthorized($novel, $user)) {
            return $this->json(['error' => 'Vous n\'êtes pas l\'autheur de ce roman, vous ne pouvez pas créer une page : '. $novel->getId()], 404);
        }
        $page->setContent($data["content"]);
        $page->setHtml($data["html"]);
        $page->setChapter($chapter);
        $this->em->persist($page);
        $this->em->flush();
        $newPageId = $page->getId();
        $pageState = $chapter->getPageState();
        array_push($pageState,$newPageId);
        $chapter->setPageState($pageState);
        $this->em->persist($chapter);
        $this->em->flush();
        
        $json = $serializer->serialize($page, 'json', ['groups' => 'page:read']);
        return new JsonResponse($json, 201, [], true);
    }

    #[Route('/page/{id}', methods: ['GET'])]
    public function getPage(int $id, SerializerInterface $serializer){
        $page = $this->pageRepo->find($id);
        if (!$page) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }
        $json = $serializer->serialize($page, 'json', ['groups' => 'page:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/page/{id}', methods: ['PUT']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function updatePage(int $id, Request $request, SerializerInterface $serializer){
        $data = json_decode($request->getContent(),true);
        $page = $this->pageRepo->find($id);
        if (!$page) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $novel = $page->getChapter()->getNovel();
        $user = $this->security->getUser();

        if (!$this->novelRelationService->isUserAuthorized($novel, $user)) {
            return $this->json(['error' => 'Vous éte pas l\'author de cette novel du coup vous ne pouver pas le supprimer : '. $novel->getId()], 404);
        }

        $page->setContent($data["content"]);
        $page->setHtml($data["html"]);
        $chapter = $this->chapterRepo->find($data["chapter"]);
        $page->setChapter($chapter);
        $this->em->persist($page);
        $this->em->flush();
        $json = $serializer->serialize($page, 'json', ['groups' => 'page:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/page/{id}', methods: ['DELETE']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function deletePage(int $id){
        $page = $this->pageRepo->find($id);
        if (!$page) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $novel = $page->getChapter()->getNovel();
        $user = $this->security->getUser();

        if (!$this->novelRelationService->isUserAuthorized($novel, $user)) {
            return $this->json(['error' => 'Vous éte pas l\'author de cette novel du coup vous ne pouver pas le supprimer : '. $novel->getId()], 404);
        }

        $chapter = $page->getChapter();
        $pageState = $chapter->getPageState();
        $indexOfPage = array_search($id, $pageState);
        unset($pageState[$indexOfPage]);
        $chapter->setPageState($pageState);
        $this->em->persist($chapter);
        $this->em->remove($page);
        $this->em->flush();
        return new Response("no content", 204);
    }
}