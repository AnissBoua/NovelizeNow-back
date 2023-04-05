<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\NovelRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: NovelRepository::class)]
class Novel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["novel:get", "novel:edit", "chapter:read","page:read", "user-novel:get"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["novel:get", "novel:edit", "chapter:read", "user-novel:get"])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(["novel:get", "novel:edit", "user-novel:get"])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["novel:get", "novel:edit", "user-novel:get"])]
    private ?string $resume = null;

    #[ORM\Column(type: 'string', columnDefinition: "ENUM('published', 'unpublished')")]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["novel:get", "novel:edit", "user-novel:get"])]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_update = null;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'novel')]
    #[Groups(["novel:get", "novel:edit"])]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'novel', targetEntity: NovelImage::class)]
    #[Groups(["novel:get", "novel:edit", "user-novel:get"])]
    private Collection $novelImages;

    #[ORM\OneToMany(mappedBy: 'novel', targetEntity: Chapter::class)]
    #[Groups(["novel:edit"])]
    private Collection $chapters;

    #[ORM\OneToMany(mappedBy: 'novel', targetEntity: UserNovel::class, cascade: ['remove'])]
    private Collection $userNovels;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->novelImages = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->userNovels = new ArrayCollection();
    }

    #[Groups(["novel:get"])]
    public function getPublishedChapters()
    {
        $chapters = array();
        foreach ($this->chapters as $chapter) {
            if ($chapter->getStatus() === 'Published') {
                array_push($chapters, $chapter);
            }
        }
        return $chapters;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(["novel:get", "user-novel:get"])]
    public function getAuthor()
    {
        $relatedUser = $this->userNovels;
        foreach($relatedUser as $userNovel){
            if($userNovel->getRelation() === "author"){
                $user = $userNovel->getUser();
            }
        }
        return $user;
    }

    #[Groups(["novel:get", "user-novel:get"])]
    public function getQuantiteChapitre()
    {
        return count($this->getChapters());
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

    public function getDateCreation(): ?string
    {
        return $this->date_creation->format('Y-m-d H:i:s');
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(?\DateTimeInterface $date_update): self
    {
        $this->date_update = $date_update;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addNovel($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeNovel($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, NovelImage>
     */
    public function getNovelImages(): Collection
    {
        return $this->novelImages;
    }

    public function addNovelImage(NovelImage $novelImage): self
    {
        if (!$this->novelImages->contains($novelImage)) {
            $this->novelImages->add($novelImage);
            $novelImage->setNovel($this);
        }

        return $this;
    }

    public function removeNovelImage(NovelImage $novelImage): self
    {
        if ($this->novelImages->removeElement($novelImage)) {
            // set the owning side to null (unless already changed)
            if ($novelImage->getNovel() === $this) {
                $novelImage->setNovel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chapter>
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters->add($chapter);
            $chapter->setNovel($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapters->removeElement($chapter)) {
            // set the owning side to null (unless already changed)
            if ($chapter->getNovel() === $this) {
                $chapter->setNovel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserNovel>
     */
    public function getUserNovels(): Collection
    {
        return $this->userNovels;
    }

    public function addUserNovel(UserNovel $userNovel): self
    {
        if (!$this->userNovels->contains($userNovel)) {
            $this->userNovels->add($userNovel);
            $userNovel->setNovel($this);
        }

        return $this;
    }

    public function removeUserNovel(UserNovel $userNovel): self
    {
        if ($this->userNovels->removeElement($userNovel)) {
            // set the owning side to null (unless already changed)
            if ($userNovel->getNovel() === $this) {
                $userNovel->setNovel(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

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
}
