<?php 

namespace App\Tests\Entity;

use App\Entity\Category;
use Symfony\Component\Validator\Validation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = Validation::createValidatorBuilder()
        ->enableAnnotationMapping()
        ->getValidator();
    }

    public function getEntity(): Category
    {
        return (new Category())
            ->setName('test');
    }

    public function assertHasErrors(Category $category, int $number = 0) // Print errors 
    {
        $errors = $this->validator->validate($category);
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
    }

    public function testValidCategoryName() {
        $this->assertHasErrors($this->getEntity()->setName('test'), 0);
    }

    public function testInvalidCategoryName() {
        $this->assertHasErrors($this->getEntity()->setName(''), 2);
        $this->assertHasErrors($this->getEntity()->setName(str_repeat('a', 256)), 1);
    }
}