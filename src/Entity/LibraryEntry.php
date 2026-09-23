<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LibraryEntryRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LibraryEntryRepository::class)]
#[ORM\UniqueConstraint(name: 'library_entry_unique', columns: ['user_id', 'novel_id'])]
class LibraryEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["user-novel:get"])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(["user-novel:get"])]
    private ?Novel $novel = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["user-novel:get"])]
    private ?\DateTimeInterface $dateCreation = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNovel(): ?Novel
    {
        return $this->novel;
    }

    public function setNovel(?Novel $novel): self
    {
        $this->novel = $novel;
        return $this;
    }

    public function getDateCreation(): ?string
    {
        return $this->dateCreation ? $this->dateCreation->format('Y-m-d H:i:s') : null;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }
}
