<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Entity\Novel;
use App\Entity\Comment;
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
            throw new Exception('Novel not found.');
        }
        
        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        if(!$user) {
            throw new Exception('Vous devez être connecté pour effectuer cette action.');
        }

        $user = $this->em->getRepository(User::class)->find($user->getId());

        if (isset($data->parent)) {
            $parent = $this->em->getRepository(Comment::class)->find($data->parent);
            if ($parent->getComment()) {
                throw new Exception('You can\'t reply to a reply.');
            }
        }

        $comment = new Comment;
        $comment->setNovel($novel);
        $comment->setUser($user);
        $comment->setContent($data->content);
        if (isset($parent)) {
            $comment->setComment($parent);
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
            throw new Exception('Comment not found.');
        }

        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        if(!$user) {
            throw new Exception('Vous devez être connecté pour effectuer cette action.');
        }

        $user = $this->em->getRepository(User::class)->find($user->getId());

        if ($comment->getUser()->getId() !== $user->getId()) {
            throw new Exception('You can\'t update this comment.');
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
            throw new Exception('Comment not found.');
        }

        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        if(!$user) {
            throw new Exception('Vous devez être connecté pour effectuer cette action.');
        }

        $user = $this->em->getRepository(User::class)->find($user->getId());

        if ($comment->getUser()->getId() !== $user->getId()) {
            throw new Exception('You can\'t delete this comment.');
        }

        $this->em->remove($comment);
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
