<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class JWTSubscriber implements EventSubscriberInterface
{
    public function onLexikJwtAuthenticationOnJwtCreated(JWTCreatedEvent $event): void
    {
        $data = $event->getData();
        // dd($event->getUser());
        $user = $event->getUser();
        if ($user instanceof User) {
            $data["roles"] = $user->getRoles();
            $data["email"] = $user->getEmail();
            $data["name"] = $user->getName();
            $data["lastname"] = $user->getLastname();
            $data['username'] = $user->getUsername();
        }

        $event->setData($data);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_jwt_created' => 'onLexikJwtAuthenticationOnJwtCreated',
        ];
    }
}
