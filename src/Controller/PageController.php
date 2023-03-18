<?php

namespace App\Controller;

use App\Entity\Page;
use App\Repository\PageRepository;
use App\Repository\ChapterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    private $em;
    private $pageRepo;
    private $chapterRepo;

    public function __construct(EntityManagerInterface $em, PageRepository $pageRepo, ChapterRepository $chapterRepo){
        $this->em = $em;
        $this->pageRepo = $pageRepo;
        $this->chapterRepo = $chapterRepo;
    }

    #[Route('/page', methods: ['POST'])]
    public function createPage(Request $request){
        $data = json_decode($request->getContent(),true);
        $page = new Page();
        $page->setContent($data["content"]);
        $page->setHtml($data["html"]);
        $page->setChapter($this->chapterRepo->find($data["chapter"]));
        $this->em->persist($page);
        $this->em->flush();
        return $this->json($page,201);
    }

    #[Route('/page/{id}', methods: ['GET'])]
    public function getPage(int $id, SerializerInterface $serializer){
        $page = $this->pageRepo->find($id);
        $json = $serializer->serialize($page, 'json', ['groups' => 'page:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/page/{id}', methods: ['PUT'])]
    public function updatePage(int $id, Request $request, SerializerInterface $serializer){
        $data = json_decode($request->getContent(),true);
        $page = $this->pageRepo->find($id);
        $page->setContent($data["content"]);
        $page->setHtml($data["html"]);
        $chapter = $this->chapterRepo->find($data["chapter"]);
        $page->setChapter($chapter);
        $this->em->persist($page);
        $this->em->flush();
        $json = $serializer->serialize($page, 'json', ['groups' => 'page:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/page/{id}', methods: ['DELETE'])]
    public function deletePage(int $id){
        $page = $this->pageRepo->find($id);
        $this->em->remove($page);
        $this->em->flush();
        return new Response("no content", 204);
    }
}