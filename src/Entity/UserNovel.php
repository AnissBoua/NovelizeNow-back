<?php

namespace App\Entity;

use App\Repository\UserNovelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserNovelRepository::class)]
class UserNovel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["novel:edit"])]
    private ?int $id = null;

    #[ORM\Column( type: 'string', columnDefinition:"ENUM('author')")]
    #[Groups(["novel:edit"])]
    private string $relation;

    #[ORM\ManyToOne(inversedBy: 'userNovels')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userNovels')]
    private ?Novel $novel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelation(): ?string
    {
        return $this->relation;
    }

    public function setRelation(string $relation): self
    {
        $this->relation = $relation;

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

    public function getNovel(): ?Novel
    {
        return $this->novel;
    }

    public function setNovel(?Novel $novel): self
    {
        $this->novel = $novel;

        return $this;
    }
}
