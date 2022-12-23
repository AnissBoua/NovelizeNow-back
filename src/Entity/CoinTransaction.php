<?php

namespace App\Entity;

use App\Repository\CoinTransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoinTransactionRepository::class)]
class CoinTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $coins = null;

    #[ORM\Column]
    private array $dataInfo = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateTransaction = null;

    #[ORM\ManyToOne(inversedBy: 'coinTransactions')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDataInfo(): array
    {
        return $this->dataInfo;
    }

    public function setDataInfo(array $dataInfo): self
    {
        $this->dataInfo = $dataInfo;

        return $this;
    }

    public function getDateTransaction(): ?\DateTimeInterface
    {
        return $this->dateTransaction;
    }

    public function setDateTransaction(\DateTimeInterface $dateTransaction): self
    {
        $this->dateTransaction = $dateTransaction;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
