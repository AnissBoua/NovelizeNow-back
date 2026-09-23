<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['comment:post', "novel:get", "novel:edit"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['comment:post', "novel:get", "novel:edit"])]
    #[Assert\NotBlank(message: "The content is required")]
    #[Assert\Length(
        min: 1,
        max: 500,
        minMessage: "The content must contain at least {{ limit }} characters",
        maxMessage: "The content must contain at most {{ limit }} characters"
    )]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['comment:post', "novel:get", "novel:edit"])]
    #[Assert\NotBlank(message: "The user is required")]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotBlank(message: "The novel is required")]
    private ?Novel $novel = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?self $comment = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    #[Groups(['comment:post'])]
    private ?Chapter $chapter = null;

    #[ORM\OneToMany(mappedBy: 'comment', targetEntity: self::class, cascade: ['remove'])]
    #[Groups(["novel:get", "novel:edit"])]
    private Collection $comments;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['comment:post', "novel:get"])]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\OneToMany(mappedBy: 'comment', targetEntity: CommentLike::class, cascade: ['remove'])]
    private Collection $commentLikes;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->commentLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getComment(): ?self
    {
        return $this->comment;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): self
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function setComment(?self $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(self $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setComment($this);
        }

        return $this;
    }

    public function removeComment(self $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getComment() === $this) {
                $comment->setComment(null);
            }
        }

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

    #[Groups(['comment:post', "novel:get"])]
    public function getLikesCount(): int
    {
        return count($this->commentLikes);
    }
}
