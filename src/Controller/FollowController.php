<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Follow;
use App\Repository\FollowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/follow')]
class FollowController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $em,
        FollowRepository $followRepository,
        private SecurityAuth $security
    ) {
        $this->em = $em;
        $this->followRepository = $followRepository;
    }

    #[Route('/{authorId}', name: 'toggle_follow', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function toggle(int $authorId)
    {
        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        $user = $this->em->getRepository(User::class)->find($user->getId());

        if ($authorId === $user->getId()) {
            return $this->json(['error' => 'You cannot follow yourself.'], Response::HTTP_BAD_REQUEST);
        }

        $author = $this->em->getRepository(User::class)->find($authorId);
        if (!$author) {
            return $this->json(['error' => 'Author not found.'], Response::HTTP_NOT_FOUND);
        }

        $follow = $this->followRepository->findOneBy(['follower' => $user, 'author' => $author]);

        if ($follow) {
            $this->em->remove($follow);
            $this->em->flush();
            return $this->json(['following' => false, 'followersCount' => $this->followRepository->count(['author' => $author])], Response::HTTP_OK);
        }

        $follow = new Follow();
        $follow->setFollower($user);
        $follow->setAuthor($author);
        $follow->setDateCreation(new \DateTime());
        $this->em->persist($follow);
        $this->em->flush();

        return $this->json(['following' => true, 'followersCount' => $this->followRepository->count(['author' => $author])], Response::HTTP_CREATED);
    }
}
