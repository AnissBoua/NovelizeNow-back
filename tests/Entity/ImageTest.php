<?php

namespace App\Tests\Entity;

use DateTime;
use App\Entity\Image;
use Symfony\Component\Validator\Validation;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ImageTest extends KernelTestCase
{
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = Validation::createValidatorBuilder()
        ->enableAnnotationMapping()
        ->getValidator();
    }

    public function getEntity(): Image
    {
        $image = (new Image())
            ->setFilename("20230518101045-q6wE9r2tY7uI4-the-fault-in-our-stars.png")
            ->setFilepath("/uploads/novels/20230518101045-q6wE9r2tY7uI4-the-fault-in-our-stars.png");
        return $image;
    }

   public function assertHasErrors(Image $image, int $number = 0)
    {
        $errors = $this->validator->validate($image);
        $messages = [];

        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidImage()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidImageFilename()
    {
        $this->assertHasErrors($this->getEntity()->setFilename(""), 1);
        $this->assertHasErrors($this->getEntity()->setFilename(str_repeat('a', 256)), 1);
    }

    public function testInvalidImageFilepath()
    {
        $this->assertHasErrors($this->getEntity()->setFilepath(""), 1);
        $this->assertHasErrors($this->getEntity()->setFilepath(str_repeat('a', 256)), 1);
    }
}