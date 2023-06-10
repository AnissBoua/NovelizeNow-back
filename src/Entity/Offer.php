<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OfferRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['offer:get'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['offer:get'])]
    #[Assert\NotBlank(message: "The name is required")]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: "The name must contain at least {{ limit }} characters",
        maxMessage: "The name must contain at most {{ limit }} characters"
    )]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['offer:get'])]
    #[Assert\NotBlank(message: "The coins is required")]
    #[Assert\Positive(message: "The coins must be positive")]
    private ?int $coins = null;

    #[ORM\Column]
    #[Groups(['offer:get'])]
    #[Assert\NotBlank(message: "The price is required")]
    #[Assert\Positive(message: "The price must be positive")]
    private ?float $price = null;

    #[ORM\Column]
    #[Groups(['offer:get'])]
    #[Assert\NotBlank(message: "The active status is required")]
    private ?bool $active = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['offer:get'])]
    private ?\DateTimeInterface $date_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['offer:get'])]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\OneToMany(mappedBy: 'offer', targetEntity: Transaction::class)]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCoins(): ?int
    {
        return $this->coins;
    }

    public function setCoins(int $coins): self
    {
        $this->coins = $coins;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(?\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(?\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setOffer($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getOffer() === $this) {
                $transaction->setOffer(null);
            }
        }

        return $this;
    }
}
