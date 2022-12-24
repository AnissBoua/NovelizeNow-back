<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AuthenticationController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $em, 
        SerializerInterface $serializer,
        private Security $security
    )
    {
        $this->serializer = $serializer;
        $this->em = $em;
    }

    #[Route('/registration', name: 'api_registration', methods:["POST"])]
    public function registration(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent());

        $user = new User();

        $user->setName($data->name);
        $user->setLastname($data->lastname);
        if(isset($data->username)) $user->setUsername($data->username);

        $user->setEmail($data->email);
        $user->setCoins(0);
        $user->setPassword($passwordHasher->hashPassword($user, $data->password));

        $this->em->persist($user);
        $this->em->flush($user);

        return new JsonResponse([
            "status" => "ok" 
            ],
            JsonResponse::HTTP_CREATED
        );
    }

    #[Route("/login", name:"api_login", methods:["POST"])]
    public function login()
    {
        // lexik do everything
    }

    #[Route("/me", name:"api_me", methods:["GET"])]
    public function me()
    {
        $user = $this->security->getUser();
        $userArray = $this->serializer->normalize($user, null);

        unset($userArray['userIdentifier']);

        // Hide all key that have a null value, keys need to be set in user entity createFromPayload
        $userFiltered = array_filter($userArray, function ($key)
        {
            if ($key === null) return;

            return $key;
        });

        return new JsonResponse(
            $userFiltered,
            JsonResponse::HTTP_CREATED
        );
    }
}
