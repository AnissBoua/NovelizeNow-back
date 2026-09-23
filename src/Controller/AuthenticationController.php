<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\LibraryEntry;
use App\Entity\ReadingProgress;
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
    private $serializer;
    private $em;
    private $fileUploadService;

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
        /** @var User $authUser */
        $authUser = $this->security->getUser();
        $user = $this->em->getRepository(User::class)->find($authUser->getId());

        $data = $this->serializer->normalize($user, null, ['groups' => ['user:me']]);
        $data['chaptersReadCount'] = $this->countChaptersRead($user);
        $data['followedNovelsCount'] = $this->em->getRepository(LibraryEntry::class)->count(['user' => $user]);

        return new JsonResponse(
            $data,
            JsonResponse::HTTP_OK
        );
    }

    private function countChaptersRead(User $user): int
    {
        $count = 0;
        $progressList = $this->em->getRepository(ReadingProgress::class)->findBy(['user' => $user]);
        foreach ($progressList as $progress) {
            $chapter = $progress->getChapter();
            $publishedChapters = $progress->getNovel()->getPublishedChapters();
            foreach ($publishedChapters as $index => $ch) {
                if ($ch->getId() === $chapter->getId()) {
                    $count += $index + 1;
                    break;
                }
            }
        }
        return $count;
    }

    #[Route("/me", name:"api_update_me", methods:["POST"]), SecurityMiddleware("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function updateMe(Request $request)
    {
        /** @var User $authUser */
        $authUser = $this->security->getUser();
        $user = $this->em->getRepository(User::class)->find($authUser->getId());

        $data = $request->request;
        $files = $request->files;

        if ($data->get('name')) {
            $user->setName($data->get('name'));
        }
        if ($data->get('lastname')) {
            $user->setLastname($data->get('lastname'));
        }
        if ($data->get('username')) {
            $existing = $this->em->getRepository(User::class)->findOneBy(['username' => $data->get('username')]);
            if ($existing && $existing->getId() !== $user->getId()) {
                return new JsonResponse(['message' => "Cet identifiant est déjà pris."], JsonResponse::HTTP_CONFLICT);
            }
            $user->setUsername($data->get('username'));
        }
        if ($data->has('bio')) {
            $user->setBio($data->get('bio'));
        }

        if ($files->get('avatar')) {
            $avatar = $files->get('avatar');
            $destination = '/uploads/avatars';
            $image = $this->fileUploadService->imageUpload($avatar, $destination);
            $user->setAvatar($image);
        }

        $this->em->persist($user);
        $this->em->flush();

        $user = $this->serializer->normalize($user, null, ['groups' => ['user:me']]);

        return new JsonResponse($user, JsonResponse::HTTP_OK);
    }

    #[Route("/me/credentials", name:"api_update_credentials", methods:["POST"]), SecurityMiddleware("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function updateCredentials(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        /** @var User $authUser */
        $authUser = $this->security->getUser();
        $user = $this->em->getRepository(User::class)->find($authUser->getId());

        $data = json_decode($request->getContent(), true) ?? [];
        $currentPassword = $data['current_password'] ?? '';

        if (!$currentPassword || !$passwordHasher->isPasswordValid($user, $currentPassword)) {
            return new JsonResponse(['message' => "Mot de passe actuel incorrect."], JsonResponse::HTTP_FORBIDDEN);
        }

        if (!empty($data['email']) && $data['email'] !== $user->getEmail()) {
            $existing = $this->em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            if ($existing && $existing->getId() !== $user->getId()) {
                return new JsonResponse(['message' => "Cette adresse e-mail est déjà utilisée."], JsonResponse::HTTP_CONFLICT);
            }
            $user->setEmail($data['email']);
        }

        if (!empty($data['new_password'])) {
            if (strlen($data['new_password']) < 8) {
                return new JsonResponse(['message' => "Le nouveau mot de passe doit contenir au moins 8 caractères."], JsonResponse::HTTP_BAD_REQUEST);
            }
            $user->setPassword($passwordHasher->hashPassword($user, $data['new_password']));
        }

        $this->em->persist($user);
        $this->em->flush();

        $user = $this->serializer->normalize($user, null, ['groups' => ['user:me']]);

        return new JsonResponse($user, JsonResponse::HTTP_OK);
    }

    #[Route("/me", name:"api_delete_me", methods:["DELETE"]), SecurityMiddleware("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function deleteMe()
    {
        /** @var User $authUser */
        $authUser = $this->security->getUser();
        $user = $this->em->getRepository(User::class)->find($authUser->getId());

        $authoredNovels = [];
        foreach ($user->getUserNovels() as $userNovel) {
            if ($userNovel->getRelation() === 'author') {
                $authoredNovels[] = $userNovel->getNovel();
            } else {
                $this->em->remove($userNovel);
            }
        }

        foreach ($authoredNovels as $novel) {
            foreach ($novel->getComments() as $comment) {
                $this->em->remove($comment);
            }
            foreach ($this->em->getRepository(Like::class)->findBy(['novel' => $novel]) as $like) {
                $this->em->remove($like);
            }
            foreach ($novel->getOrders() as $order) {
                $this->em->remove($order);
            }
        }

        foreach ($user->getComments() as $comment) {
            $this->em->remove($comment);
        }
        foreach ($user->getTransactions() as $transaction) {
            $this->em->remove($transaction);
        }
        foreach ($user->getOrders() as $order) {
            $this->em->remove($order);
        }

        foreach ($authoredNovels as $novel) {
            $this->em->remove($novel);
        }

        $this->em->remove($user);
        $this->em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
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
