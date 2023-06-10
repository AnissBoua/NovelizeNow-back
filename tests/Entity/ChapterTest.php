<?php

namespace App\Tests\Entity;

use DateTime;
use App\Entity\Novel;
use App\Entity\Chapter;
use Symfony\Component\Validator\Validation;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ChapterTest extends KernelTestCase
{
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = Validation::createValidatorBuilder()
        ->enableAnnotationMapping()
        ->getValidator();
    }

    public function getEntity(): Chapter
    {
        $chapter = (new Chapter())
            ->setTitle("Test")
            ->setStatus("in_progress")
            ->setNovel($this->createMock(Novel::class));
        return $chapter;
    }

   public function assertHasErrors(Chapter $chapter, int $number = 0)
    {
        $errors = $this->validator->validate($chapter);
        $messages = [];

        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidChapter()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidChapterTitle()
    {
        $this->assertHasErrors($this->getEntity()->setTitle(""), 1);
        $this->assertHasErrors($this->getEntity()->setTitle(str_repeat('a', 256)), 1);
    }
    
    public function testInvalidChapterStatus()
    {
        $this->assertHasErrors($this->getEntity()->setStatus("in progress"), 1);
        $this->assertHasErrors($this->getEntity()->setStatus("Published"), 1);
    }

    public function testInvalidChapterNovel()
    {
        $this->assertHasErrors($this->getEntity()->setNovel(null), 1);
    }
}