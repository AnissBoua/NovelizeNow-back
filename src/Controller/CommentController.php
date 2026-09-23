<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Entity\Novel;
use App\Entity\Chapter;
use App\Entity\Comment;
use App\Entity\CommentLike;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/comment')]
class CommentController extends AbstractController
{

    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        private SecurityAuth $security
    )
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    #[Route('/', name: 'new_comment', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function post(Request $request)
    {
        $data = json_decode($request->getContent(), false);
        $novel = $this->em->getRepository(Novel::class)->find($data->novel);
        if(!$novel) {
            return $this->json(['error' => 'Novel not found.'], Response::HTTP_NOT_FOUND);
        }
        
        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        if(!$user) {
            return $this->json(['error' => 'Vous devez être connecté pour effectuer cette action.'], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->em->getRepository(User::class)->find($user->getId());

        if (isset($data->parent)) {
            $parent = $this->em->getRepository(Comment::class)->find($data->parent);
            if ($parent->getComment()) {
                return $this->json(['error' => 'You can\'t reply to a reply.'], Response::HTTP_BAD_REQUEST);
            }
        }

        if (isset($data->chapter)) {
            $chapter = $this->em->getRepository(Chapter::class)->find($data->chapter);
            if (!$chapter || $chapter->getNovel()->getId() !== $novel->getId()) {
                return $this->json(['error' => 'Chapter not found.'], Response::HTTP_NOT_FOUND);
            }
        }

        $comment = new Comment;
        $comment->setNovel($novel);
        $comment->setUser($user);
        $comment->setContent($data->content);
        $comment->setDateCreation(new \DateTime());
        if (isset($parent)) {
            $comment->setComment($parent);
        }
        if (isset($chapter)) {
            $comment->setChapter($chapter);
        }

        $this->em->persist($comment);
        $this->em->flush();

        $comment = json_decode($this->serializer->serialize($comment, 'json', ['groups' => 'comment:post']));

        return $this->json($comment, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update_comment', methods: ['PUT']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function update(int $id, Request $request)
    {
        $data = json_decode($request->getContent(), false);
        $comment = $this->em->getRepository(Comment::class)->find($id);
        if(!$comment) {
            return $this->json(['error' => 'Comment not found.'], Response::HTTP_NOT_FOUND);
        }

        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        if(!$user) {
            return $this->json(['error' => 'Vous devez être connecté pour effectuer cette action.'], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->em->getRepository(User::class)->find($user->getId());

        if ($comment->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'You can\'t update this comment.'], Response::HTTP_FORBIDDEN);
        }

        $comment->setContent($data->content);

        $this->em->flush();

        $comment = json_decode($this->serializer->serialize($comment, 'json', ['groups' => 'comment:post']));

        return $this->json($comment, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_comment', methods: ['DELETE']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function delete(int $id)
    {
        $comment = $this->em->getRepository(Comment::class)->find($id);
        if(!$comment) {
            return $this->json(['error' => 'Comment not found.'], Response::HTTP_NOT_FOUND);
        }

        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        if(!$user) {
            return $this->json(['error' => 'Vous devez être connecté pour effectuer cette action.'], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->em->getRepository(User::class)->find($user->getId());

        if ($comment->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'You can\'t delete this comment.'], Response::HTTP_FORBIDDEN);
        }

        $this->em->remove($comment);
        $this->em->flush();

        return $this->json(null, Response::HTTP_OK);
    }

    #[Route('/{id}/like', name: 'toggle_comment_like', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function toggleLike(int $id)
    {
        $comment = $this->em->getRepository(Comment::class)->find($id);
        if(!$comment) {
            return $this->json(['error' => 'Comment not found.'], Response::HTTP_NOT_FOUND);
        }

        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        $user = $this->em->getRepository(User::class)->find($user->getId());

        $existing = $this->em->getRepository(CommentLike::class)->findOneBy(['user' => $user, 'comment' => $comment]);
        $liked = !$existing;

        if ($existing) {
            $this->em->remove($existing);
        } else {
            $like = new CommentLike();
            $like->setUser($user);
            $like->setComment($comment);
            $this->em->persist($like);
        }
        $this->em->flush();

        $likesCount = $this->em->getRepository(CommentLike::class)->count(['comment' => $comment]);

        return $this->json(['liked' => $liked, 'likesCount' => $likesCount], Response::HTTP_OK);
    }
}
