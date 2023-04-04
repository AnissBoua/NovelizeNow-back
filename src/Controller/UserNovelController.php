<?php

namespace App\Controller;

use App\Repository\NovelRepository;
use App\Repository\UserNovelRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserNovelController extends AbstractController
{

    public function __construct(private SecurityAuth $security, UserRepository $userRepository, UserNovelRepository $userNovelRepository, SerializerInterface $serializerInterface)
    {
        $this->userRepository = $userRepository;
        $this->userNovelRepository = $userNovelRepository;
        $this->serializerInterface = $serializerInterface;
    }

    #[Route('/user/novels', name: 'get_user_novel', methods: ['GET']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function get()
    {
        /** @var \App\Entity\User $user */
        $user  = $this->security->getUser();
        $user = $this->userRepository->find($user->getId());

        if (!$user) {
            return $this->json(['message' => 'Unauthorized'], 401);
        }

        $userNovels = $this->userNovelRepository->getNovelsByUser($user->getId());

        $userNovels = $this->serializerInterface->serialize($userNovels, 'json', ['groups' => 'user-novel:get']);
        return new JsonResponse($userNovels, 200, [], true);
    }
}
