<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["page:read", "chapter:read"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 3000,
        minMessage: "The title must contain at least {{ limit }} characters",
        maxMessage: "The title must contain at most {{ limit }} characters"
    )]
    #[Groups(["page:read", "chapter:read"])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'pages')]
    #[Groups(["page:read"])]
    #[Assert\NotBlank(message: "The user is required")]
    private ?Chapter $chapter = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["page:read"])]
    #[Assert\NotBlank]
    private ?string $html = null;

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

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): self
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'html' => $this->html
        ];
    }
}
