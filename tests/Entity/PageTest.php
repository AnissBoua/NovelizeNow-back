<?php

namespace App\Tests\Entity;

use DateTime;
use App\Entity\Page;
use App\Entity\Chapter;
use Symfony\Component\Validator\Validation;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PageTest extends KernelTestCase
{
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = Validation::createValidatorBuilder()
        ->enableAnnotationMapping()
        ->getValidator();
    }

    public function getEntity(): Page
    {
        $page = (new Page())
            ->setContent("Test")
            ->setHtml("<p>Test</p>")
            ->setChapter($this->createMock(Chapter::class));
        return $page;
    }

   public function assertHasErrors(Page $page, int $number = 0)
    {
        $errors = $this->validator->validate($page);
        $messages = [];

        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidPage()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidPageContent()
    {
        $this->assertHasErrors($this->getEntity()->setContent(""), 1);
        $this->assertHasErrors($this->getEntity()->setContent(str_repeat('a', 3001)), 1);
    }

    public function testInvalidPageHtml()
    {
        $this->assertHasErrors($this->getEntity()->setHtml(""), 1);
    }

    public function testInvalidPageChapter()
    {
        $this->assertHasErrors($this->getEntity()->setChapter(null), 1);
    }

}