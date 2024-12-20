<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ChapterRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChapterRepository::class)]
class Chapter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["page:read", "chapter:read", "novel:get", "novel:edit", 'home:get'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["page:read", "chapter:read", "novel:get", "novel:edit", 'home:get'])]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 255,
        minMessage: "The title must contain at least {{ limit }} characters",
        maxMessage: "The title must contain at most {{ limit }} characters"
    )]
    private ?string $title = null;

    #[ORM\Column(type:'string', columnDefinition: "ENUM('published', 'in_progress')")]
    #[Groups(["chapter:read", "novel:get"])]
    #[Assert\Choice(choices: ['published', 'in_progress'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'chapters')]
    #[Groups(["chapter:read", "page:read", 'home:get'])]
    #[Assert\NotBlank]
    private ?Novel $novel = null;

    #[ORM\OneToMany(mappedBy: 'chapter', targetEntity: Page::class, cascade: ['remove'])]
    #[Groups(["chapter:read"])]
    private Collection $pages;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(["chapter:read", "page:read", "novel:get"])]
    private array $pageState = [];

    public function __construct()
    {
        $this->pages = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Page>
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function addPage(Page $page): self
    {
        if (!$this->pages->contains($page)) {
            $this->pages->add($page);
            $page->setChapter($this);
        }

        return $this;
    }

    public function removePage(Page $page): self
    {
        if ($this->pages->removeElement($page)) {
            // set the owning side to null (unless already changed)
            if ($page->getChapter() === $this) {
                $page->setChapter(null);
            }
        }

        return $this;
    }

    public function getPageState(): array
    {
        return $this->pageState;
    }

    /**
     * @param int[] $integerArray
     */
    public function setPageState(?array $pageState): self
    {
        $this->pageState = $pageState ? array_values($pageState) : [];
        return $this;
    }
}
