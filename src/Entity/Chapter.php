<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ChapterRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChapterRepository::class)]
#[ORM\Index(columns: ['status', 'publish_at'], name: 'chapter_status_publish_at')]
class Chapter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["chapter:read", "novel:get", "novel:edit", 'home:get'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["chapter:read", "novel:get", "novel:edit", 'home:get'])]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 255,
        minMessage: "The title must contain at least {{ limit }} characters",
        maxMessage: "The title must contain at most {{ limit }} characters"
    )]
    private ?string $title = null;

    #[ORM\Column(type:'string', columnDefinition: "ENUM('published', 'in_progress', 'scheduled')")]
    #[Groups(["chapter:read", "novel:get"])]
    #[Assert\Choice(choices: ['published', 'in_progress', 'scheduled'])]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publishAt = null;

    #[ORM\ManyToOne(inversedBy: 'chapters')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[Groups(["chapter:read", 'home:get'])]
    #[Assert\NotBlank]
    private ?Novel $novel = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["chapter:read"])]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["chapter:read"])]
    private ?string $html = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["chapter:read", "novel:get"])]
    private ?\DateTimeInterface $dateCreation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): self
    {
        $this->html = $html;

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

    #[Groups(["chapter:read", "novel:get"])]
    public function getPublishAt(): ?string
    {
        return $this->publishAt ? $this->publishAt->format('Y-m-d H:i:s') : null;
    }

    public function setPublishAt(?\DateTimeInterface $publishAt): self
    {
        $this->publishAt = $publishAt;

        return $this;
    }

    #[Groups(["chapter:read", "novel:get"])]
    public function getWordCount(): int
    {
        $text = trim($this->content ?? '');
        return $text === '' ? 0 : count(preg_split('/\s+/', $text));
    }
}
