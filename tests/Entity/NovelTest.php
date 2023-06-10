<?php 

namespace App\Tests\Entity;

use App\Entity\Novel;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

class NovelTest extends KernelTestCase
{
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = Validation::createValidatorBuilder()
        ->enableAnnotationMapping()
        ->getValidator();
    }

    public function getEntity(): Novel
    {
        return (new Novel())
            ->setTitle('Test')
            ->setSlug('test')
            ->setResume('Test')
            ->setPrice(1)
            ->setStatus('published')
            ->setDateCreation(new \DateTime())
            ->setDateUpdate(new \DateTime());
    }

    public function assertHasErrors(Novel $novel, int $number = 0)
    {
        $errors = $this->validator->validate($novel);
        $messages = [];

        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        // Default entity is valid
        $this->assertHasErrors($this->getEntity(), 0);

        // Test with a title
        $this->assertHasErrors($this->getEntity()->setTitle(''), 2);
        $this->assertHasErrors($this->getEntity()->setTitle(str_repeat('a', 256)), 1);

        // Test with a slug
        $this->assertHasErrors($this->getEntity()->setSlug(''), 2);
        $this->assertHasErrors($this->getEntity()->setSlug(str_repeat('a', 256)), 1);
        $this->assertHasErrors($this->getEntity()->setSlug('test test'), 1);
        $this->assertHasErrors($this->getEntity()->setSlug('test-Test'), 1);
        $this->assertHasErrors($this->getEntity()->setSlug('test-test-$'), 1);

        // Test with a resume
        $this->assertHasErrors($this->getEntity()->setResume(''), 0);
        $this->assertHasErrors($this->getEntity()->setResume(str_repeat('a', 501)), 1);

        // Test with a status
        $this->assertHasErrors($this->getEntity()->setStatus(''), 1);
        $this->assertHasErrors($this->getEntity()->setStatus('test'), 1);
        $this->assertHasErrors($this->getEntity()->setStatus('published'), 0);

        // Test with a date creation
        $this->assertHasErrors($this->getEntity()->setDateCreation(new \DateTime('2020-01-01')), 0);
        $this->assertHasErrors($this->getEntity()->setDateCreation(new \DateTime('2020-01-01 00:00:00')), 0);

        // Test with a date update
        $this->assertHasErrors($this->getEntity()->setDateUpdate(new \DateTime('2020-01-01')), 0);
        $this->assertHasErrors($this->getEntity()->setDateUpdate(new \DateTime('2020-01-01 00:00:00')), 0);

        // Test with a price
        $this->assertHasErrors($this->getEntity()->setPrice(0), 1);
        $this->assertHasErrors($this->getEntity()->setPrice(1), 0);
        $this->assertHasErrors($this->getEntity()->setPrice(100), 0);
        $this->assertHasErrors($this->getEntity()->setPrice(-100), 1);

    }
}