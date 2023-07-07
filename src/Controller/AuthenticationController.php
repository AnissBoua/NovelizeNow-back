<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\FileUploadService;
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

class AuthenticationController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $em, 
        SerializerInterface $serializer,
        private Security $security,
        FileUploadService $fileUploadService
    )
    {
        $this->serializer = $serializer;
        $this->fileUploadService = $fileUploadService;
        $this->em = $em;
    }

    #[Route('/registration', name: 'api_registration', methods:["POST"])]
    public function registration(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        
        $data = $request->request;
        $files = $request->files;

        if (!$data->get('name') || !$data->get('lastname') || !$data->get('email') || !$data->get('password')) {
            return new JsonResponse([
                "status" => "error",
                "message" => "Missing required fields"
            ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
        
        if($this->em->getRepository(User::class)->findOneBy(['email' => $data->get('email')])) {
            return new JsonResponse([
                "status" => "error",
                "message" => "Email already used"
            ],
                JsonResponse::HTTP_CONFLICT
            );
        }

        $user = new User();
        
        $user->setName($data->get('name'));
        $user->setLastname($data->get('lastname'));
        $username = $data->get('username');
        if($username !== null) $user->setUsername($username);

        $user->setEmail($data->get('email'));
        $user->setCoins(0);
        $regex = "^(?=.*[a-z])(?=.*[A-Z])(?=.{8,})^";
        if(preg_match($regex, $data->get('password'))) {
            $user->setPassword($passwordHasher->hashPassword($user, $data->get('password')));
        } else {
            return new JsonResponse([
                "status" => "error",
                "message" => "Password must contain at least 8 characters, 1 uppercase and 1 lowercase"
            ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if ($files->get('avatar')) {
            $avatar = $files->get('avatar');
            $destination = '/uploads/avatars';
            $image = $this->fileUploadService->imageUpload($avatar,$destination);
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

    #[Route("/me", name:"api_me", methods:["GET"]), SecurityMiddleware("is_granted('IS_AUTHENTICATED_FULLY')")]
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

    #[Route("/user/avatar", name:"api_user_avatar", methods: ["GET"]), SecurityMiddleware("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function getAvatar(): Response
    {
        /** @var User */
        $user = $this->security->getUser();

        $user = $this->em->getRepository(User::class)->find($user->getId());
        if(!$user) {
            return $this->json(['error' => 'Vous devez être connecté pour créer un nouveau roman'], 401);
        }

        if (!$user->getAvatar()) {
            return new JsonResponse([
                "avatar" => null
            ],
            JsonResponse::HTTP_OK
            );
        }

        return new JsonResponse([
            "avatar" => $user->getAvatar()->getFilepath()
        ],
        JsonResponse::HTTP_OK
        );
    }
}
