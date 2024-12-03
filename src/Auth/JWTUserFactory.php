<?php 

namespace App\Auth;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class JWTUserFactory {
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function createFromIdentifier($id): User {
        $user = $this->em->getRepository(User::class)->find($id);
        if (!$user) throw new UserNotFoundException("User with ID $id not found");
        return $user;
    }

    public function createFromPayload($id, array $payload): User {
        $user = $this->em->getRepository(User::class)->find($id);
        if (!$user) throw new UserNotFoundException("User with ID $id not found");

        return $user;
    }
}
