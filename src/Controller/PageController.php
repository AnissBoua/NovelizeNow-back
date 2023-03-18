<?php

namespace App\Controller;

use App\Entity\Page;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    private $em;
    private $pageRepo;

    public function __construct(EntityManagerInterface $em, PageRepository $pageRepo){
        $this->em = $em;
        $this->pageRepo = $pageRepo;
    }

    #[Route('/page', methods: ['POST'])]
    public function createPage(Request $request){
        $data = json_decode($request->getContent(),true);
        $page = new Page();
        $page->setContent($data["content"]);
        $page->setHtml($data["html"]);
        //TODO ADD CHAPTER
        $this->em->persist($page);
        $this->em->flush();
        return $this->json($page,201);
    }

    #[Route('/page/{id}', methods: ['GET'])]
    public function getPage(int $id){
        $page = $this->pageRepo->find($id);
        return $this->json($page,200);
    }

    #[Route('/page/{id}', methods: ['PATCH'])]
    public function updatePage(int $id, Request $request){
        $data = json_decode($request->getContent(),true);
        $page = $this->pageRepo->find($id);
        $page->setContent($data["content"]);
        $page->setHtml($data["html"]);
        $this->em->persist($page);
        $this->em->flush();
        return $this->json($page,200);
    }

    #[Route('/page/{id}', methods: ['DELETE'])]
    public function deletePage(int $id){
        $page = $this->pageRepo->find($id);
        $this->em->remove($page);
        $this->em->flush();
        return new Response("no content", 204);
    }
}