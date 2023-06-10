<?php 

namespace App\Tests\EventSubscriber;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\EventSubscriber\JWTSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTSubscriberTest extends TestCase
{
    public function testJwtSubscriber()
    {
        $this->assertIsArray(JWTSubscriber::getSubscribedEvents());
        $this->assertArrayHasKey('lexik_jwt_authentication.on_jwt_created', JWTSubscriber::getSubscribedEvents());
    }

    public function testOnJwtCreated() {
        // Create a real User instance
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setName('John');
        $user->setLastname('Doe');
        $user->setUsername('johndoe');

        $event = new JWTCreatedEvent([], $user, []);
        $subscriber = new JWTSubscriber();

        // Call the onJwtCreated method
        $subscriber->onLexikJwtAuthenticationOnJwtCreated($event);

        // Assert the data has been modified
        $data = $event->getData();
        $this->assertNotNull($data);
        $this->assertIsArray($data);
        $this->assertEquals('test@example.com', $data['email']);
        $this->assertEquals('John', $data['name']);
        $this->assertEquals('Doe', $data['lastname']);
        $this->assertEquals('johndoe', $data['username']);
    }
}
