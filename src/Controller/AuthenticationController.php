<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as SecurityMiddleware;
use App\Middleware\FileUploadMiddleware;

class AuthenticationController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $em, 
        SerializerInterface $serializer,
        private Security $security,
        FileUploadMiddleware $fileUploadMiddleware
    )
    {
        $this->serializer = $serializer;
        $this->fileUploadMiddleware = $fileUploadMiddleware;
        $this->em = $em;
    }

    #[Route('/registration', name: 'api_registration', methods:["POST"])]
    public function registration(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        
        $data = $request->request;
        $files = $request->files;

        $user = new User();
        
        $user->setName($data->get('name'));
        $user->setLastname($data->get('lastname'));
        $username = $data->get('username');
        if($username !== null) $user->setUsername($username);

        $user->setEmail($data->get('email'));
        $user->setCoins(0);
        $user->setPassword($passwordHasher->hashPassword($user, $data->get('password')));

        if ($files->get('avatar')) {
            $avatar = $files->get('avatar');
            $destination = '/uploads/avatars';
            $image = $this->fileUploadMiddleware->imageUpload($avatar,$destination);
            $user->setAvatar($image);
        }
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
            JsonResponse::HTTP_OK
        );
    }

    // Hidden Route /token/refresh To refresh token required refresh_token in body

    #[Route("/user/coins", name:"api_user_coins", methods: ["GET"]), SecurityMiddleware("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function getCoins(): Response
    {
        /** @var User */
        $user = $this->security->getUser();

        $user = $this->em->getRepository(User::class)->find($user->getId());
        if(!$user) {
            return $this->json(['error' => 'Vous devez être connecté pour créer un nouveau roman'], 401);
        }

        return new JsonResponse([
            "coins" => $user->getCoins()
        ],
        JsonResponse::HTTP_OK
        );
    }
}
