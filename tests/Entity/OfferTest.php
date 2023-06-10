<?php 

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Novel;
use App\Entity\Comment;
use App\Entity\Offer;
use Symfony\Component\Validator\Validation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OfferTest extends KernelTestCase
{
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = Validation::createValidatorBuilder()
        ->enableAnnotationMapping()
        ->getValidator();
    }

    public function getEntity(): Offer
    {
        return (new Offer())
            ->setName('test')
            ->setCoins(1000)
            ->setPrice(10)
            ->setActive(true)
            ->setDateStart(new \DateTime('now'))
            ->setDateEnd(new \DateTime('now'));
    }

    public function assertHasErrors(Offer $offer, int $number = 0) // Print errors 
    {
        $errors = $this->validator->validate($offer);
        $messages = [];

        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidOffer()
    {
        // Default entity is valid
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testValidOfferName() {
        $this->assertHasErrors($this->getEntity()->setName('Starter'), 0);
    }

    public function testInvalidOfferName() {
        $this->assertHasErrors($this->getEntity()->setName(''), 2);
        $this->assertHasErrors($this->getEntity()->setName(str_repeat('a', 256)), 1);
    }

    public function testValidOfferCoins() {
        $this->assertHasErrors($this->getEntity()->setCoins(100), 0);
    }

    public function testInvalidOfferCoins() {
        $this->assertHasErrors($this->getEntity()->setCoins(0), 1);
        $this->assertHasErrors($this->getEntity()->setCoins(-10), 1);
    }

    public function testValidOfferPrice() {
        $this->assertHasErrors($this->getEntity()->setPrice(9.90), 0);
    }

    public function testInvalidOfferPrice() {
        $this->assertHasErrors($this->getEntity()->setPrice(0), 1);
        $this->assertHasErrors($this->getEntity()->setPrice(-10), 1);
    }

    public function testValidOfferActive() {
        $offer = (new Offer())
        ->setName('test')
        ->setCoins(1000)
        ->setPrice(10)
        ->setDateStart(new \DateTime('now'))
        ->setDateEnd(new \DateTime('now'));
        $this->assertHasErrors($offer, 1);
    }
}