<?php 

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Novel;
use App\Entity\Comment;
use App\Entity\Category;
use Symfony\Component\Validator\Validation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentTest extends KernelTestCase
{
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = Validation::createValidatorBuilder()
        ->enableAnnotationMapping()
        ->getValidator();
    }

    public function getEntity(): Comment
    {
        return (new Comment())
            ->setNovel($this->createMock(Novel::class))
            ->setUser($this->createMock(User::class))
            ->setContent('test');
    }

    public function assertHasErrors(Comment $comment, int $number = 0) // Print errors 
    {
        $errors = $this->validator->validate($comment);
        $messages = [];

        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidComment()
    {
        // Default entity is valid
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testValidCommentContent() {
        $this->assertHasErrors($this->getEntity()->setContent('Hello World'), 0);
    }

    public function testInvalidCommentContent() {
        $this->assertHasErrors($this->getEntity()->setContent(''), 2);
        $this->assertHasErrors($this->getEntity()->setContent(str_repeat('a', 501)), 1);
    }

    public function testInvalidCommentNovel() {
        $this->assertHasErrors($this->getEntity()->setNovel(null), 1);
    }

    public function testInvalidCommentUser() {
        $this->assertHasErrors($this->getEntity()->setUser(null), 1);
    }
}