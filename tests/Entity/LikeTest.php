<?php

namespace App\Tests\Entity;

use DateTime;
use App\Entity\Like;
use App\Entity\User;
use App\Entity\Novel;
use Symfony\Component\Validator\Validation;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LikeTest extends KernelTestCase
{
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = Validation::createValidatorBuilder()
        ->enableAnnotationMapping()
        ->getValidator();
    }

    public function getEntity(): Like
    {
        $like = (new Like())
            ->setNovel($this->createMock(Novel::class))
            ->setUser($this->createMock(User::class));
        return $like;
    }

   public function assertHasErrors(Like $like, int $number = 0)
    {
        $errors = $this->validator->validate($like);
        $messages = [];

        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidLike()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }
    
    public function testInvalidLikeUser()
    {
        $this->assertHasErrors($this->getEntity()->setUser(null), 1);
    }

    public function testInvalidLikeNovel()
    {
        $this->assertHasErrors($this->getEntity()->setNovel(null), 1);
    }
}