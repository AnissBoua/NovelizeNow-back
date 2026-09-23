<?php

namespace App\Controller;

use Exception;
use App\Entity\Order;
use App\Entity\Chapter;
use App\Entity\Comment;
use App\Entity\CommentLike;
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

    public function __construct(EntityManagerInterface $em, ChapterRepository $chapterRepo , NovelRepository $novelRepo, NovelRelationService $novelRelationService, private SecurityAuth $security){
        $this->em = $em;
        $this->chapterRepo = $chapterRepo;
        $this->novelRepo = $novelRepo;
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
        $chapter->setDateCreation(new \DateTime());
        if ($error = $this->applyStatus($chapter, $data)) {
            return $this->json(['error' => $error], 400);
        }
        $chapter->setNovel($novel);
        $chapter->setContent($data["content"] ?? null);
        $chapter->setHtml($data["html"] ?? null);
        $this->em->persist($chapter);
        $this->em->flush();
        $json = $serializer->serialize($chapter, 'json', ['groups' => 'chapter:read']);
        return new JsonResponse($json, 201, [], true);
    }

    #[Route('/chapter/{id}', methods: ['GET']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function getChapter(int $id, SerializerInterface $serializer){
        $chapter = $this->chapterRepo->find($id);
        if (!$chapter) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }
        if (!$this->novelRelationService->isUserAuthorized($chapter->getNovel(), $this->security->getUser())) {
            return $this->json(['error' => 'Forbidden.'], 403);
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
        if ($error = $this->applyStatus($chapter, $data)) {
            return $this->json(['error' => $error], 400);
        }
        $chapter->setContent($data["content"] ?? null);
        $chapter->setHtml($data["html"] ?? null);
        $this->em->persist($chapter);
        $this->em->flush();
        $json = $serializer->serialize($chapter, 'json', ['groups' => 'chapter:read']);
        return new JsonResponse($json, 200, [], true);
    }

    private function applyStatus(Chapter $chapter, array $data): ?string
    {
        $status = $data['status'] ?? 'in_progress';
        if (!in_array($status, ['published', 'in_progress', 'scheduled'], true)) {
            return 'Statut invalide.';
        }

        $publishAt = null;
        if ($status === 'scheduled') {
            $publishAt = !empty($data['publishAt']) ? \DateTime::createFromFormat('!Y-m-d\TH:i', $data['publishAt']) : false;
            if (!$publishAt || $publishAt <= new \DateTime()) {
                return 'Choisissez une date de parution dans le futur.';
            }
        }

        // The chapter's date is shown to readers as its publication date.
        if ($status === 'published' && $chapter->getStatus() !== 'published') {
            $chapter->setDateCreation(new \DateTime());
        }
        $chapter->setStatus($status);
        $chapter->setPublishAt($publishAt);

        return null;
    }

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

    #[Route('/chapter_pages/{id}', methods: ['GET'])]
    public function getChapterPages(int $id, SerializerInterface $serializer){
        $chapter = $this->chapterRepo->find($id);
        if (!$chapter) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $novel = $chapter->getNovel();
        $user = $this->security->getUser();
        $isAuthor = $this->novelRelationService->isUserAuthorized($novel, $user);

        if (($chapter->getStatus() !== 'published' || !$novel->isPublished()) && !$isAuthor) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $publishedChapters = $novel->getPublishedChapters();
        $firstChapter = isset($publishedChapters[0]) && $publishedChapters[0]->getId() === $chapter->getId();

        if ($firstChapter === false && !$isAuthor) {
            $order = $this->em->getRepository(Order::class)
                        ->findOneBy([
                            "user" => $user,
                            "novel" => $novel
                        ]);
            if (!$order) {
                return $this->json(['error' => 'You haven\'t buy this novel, so you can\'t read it'], 403);
            }
        }

        $comments = $this->em->getRepository(Comment::class)->findBy([
            'chapter' => $chapter->getId(),
            'comment' => null,
        ], ['id' => 'DESC']);

        $likedCommentIds = [];
        if ($user) {
            $allCommentIds = array_map(fn($c) => $c->getId(), $comments);
            $replies = $allCommentIds ? $this->em->getRepository(Comment::class)->findBy(['comment' => $allCommentIds]) : [];
            foreach ($replies as $reply) {
                $allCommentIds[] = $reply->getId();
            }
            if ($allCommentIds) {
                foreach ($this->em->getRepository(CommentLike::class)->findBy(['user' => $user->getId(), 'comment' => $allCommentIds]) as $commentLike) {
                    $likedCommentIds[] = $commentLike->getComment()->getId();
                }
            }
        }

        $comments = array_map(function ($comment) use ($serializer, $likedCommentIds) {
            $decoded = json_decode($serializer->serialize($comment, 'json', ['groups' => 'novel:get']));
            $decoded->isLiked = in_array($comment->getId(), $likedCommentIds);

            $replies = $this->em->getRepository(Comment::class)->findBy(['comment' => $comment->getId()], ['id' => 'DESC']);
            $decoded->comments = array_map(function ($reply) use ($serializer, $likedCommentIds) {
                $decodedReply = json_decode($serializer->serialize($reply, 'json', ['groups' => 'novel:get']));
                $decodedReply->isLiked = in_array($reply->getId(), $likedCommentIds);
                return $decodedReply;
            }, $replies);

            return $decoded;
        }, $comments);

        $arrayResponse = [
            "novelTitle" => $chapter->getNovel()->getTitle(),
            "chapterTitle" => $chapter->getTitle(),
            "content" => $chapter->getContent(),
            "html" => $chapter->getHtml(),
            "dateCreation" => $chapter->getDateCreation(),
            "firstChapter" => $firstChapter,
            "comments" => $comments,
        ];

        return new JsonResponse(json_encode($arrayResponse),200, [], true);
    }

}
