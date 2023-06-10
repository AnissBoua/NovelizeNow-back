<?php

namespace App\Tests\Entity;

use DateTime;
use App\Entity\User;
use App\Entity\Image;
use Symfony\Component\Validator\Validation;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = Validation::createValidatorBuilder()
        ->enableAnnotationMapping()
        ->getValidator();
    }

    public function getEntity(): User
    {
        $user = (new User())
            ->setEmail("test@test.test")
            ->setPassword("EZaDFQSDFQAasdf85674")
            ->setName('Henri')
            ->setLastname("Dupont")
            ->setCoins(0)
            ->setUsername("fskjf")
            ->setAvatar($this->createMock(Image::class));
        return $user;
    }

   public function assertHasErrors(User $user, int $number = 0)
    {
        $errors = $this->validator->validate($user);
        $messages = [];

        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidUser()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    

    public function testInvalidUserEmail(){
        $this->assertHasErrors($this->getEntity()->setEmail(""), 1);
        $this->assertHasErrors($this->getEntity()->setEmail("test@test"), 1);
    }

    public function testInvalidUserPassword()
    {
        $this->assertHasErrors($this->getEntity()->setPassword(""), 1);
    }

    public function testInvalidUserName()
    {
        $this->assertHasErrors($this->getEntity()->setName(""), 1);
        $this->assertHasErrors($this->getEntity()->setName(str_repeat('a', 256)), 1);
    }

    public function testInvalidUserLastname()
    {
        $this->assertHasErrors($this->getEntity()->setLastname(""), 1);
        $this->assertHasErrors($this->getEntity()->setLastname(str_repeat('a', 256)), 1);
    }

    public function testInvalidUserCoins()
    {
        $this->assertHasErrors($this->getEntity()->setCoins(-10), 1);
    }

    public function testInvalidUserUsername()
    {
        $this->assertHasErrors($this->getEntity()->setUsername(""), 1);
        $this->assertHasErrors($this->getEntity()->setUsername(str_repeat('a', 256)), 1);
    }

    public function testInvalidUserAvatar()
    {
        $this->assertHasErrors($this->getEntity()->setAvatar(null),1);
    }


}