<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentLikeRepository;

#[ORM\Entity(repositoryClass: CommentLikeRepository::class)]
#[ORM\UniqueConstraint(name: 'comment_like_unique', columns: ['user_id', 'comment_id'])]
class CommentLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'commentLikes')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Comment $comment = null;

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

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;
        return $this;
    }
}
