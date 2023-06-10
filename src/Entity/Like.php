<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LikeRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`')]
#[ORM\UniqueConstraint(
    name: 'like_unique',
    columns: ['user_id', 'novel_id']
  )]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["like:get"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["like:get"])]
    #[Assert\NotBlank(message: "The user is required")]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["like:get"])]
    #[Assert\NotBlank]
    private ?Novel $novel = null;

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
}
