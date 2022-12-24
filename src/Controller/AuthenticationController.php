<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/registration', name: 'api_registration', methods:["POST"])]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
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
        // dd($user);

        return new JsonResponse([
            "status" => "ok" 
            ],
            JsonResponse::HTTP_CREATED
        ) ;
    }
}
