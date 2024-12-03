<?php

namespace App\Auth;

use App\Entity\User;
use App\Auth\JWTUserFactory;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JWTUserProvider implements UserProviderInterface {
    private JWTUserFactory $factory;

    public function __construct(JWTUserFactory $factory) {
        $this->factory = $factory;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface {
        return $this->factory->createFromIdentifier($identifier);
    }

    public function refreshUser(UserInterface $user): UserInterface {
        // LexikJWT does not use refreshUser; we can safely throw an exception
        throw new \LogicException('JWT tokens do not support refreshing users.');
    }

    public function supportsClass(string $class): bool {
        return $class === User::class;
    }

    public function loadUserByIdentifierAndPayload($identifier, array $payload): User {
        return $this->factory->createFromPayload($identifier, $payload);
    }
}