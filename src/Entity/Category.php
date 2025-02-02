<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["category:get", "category:post", "novel:get", "novel:edit", "home:get", "home:categories"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["category:get", "category:post", "novel:get", "novel:edit", "home:get", "home:categories"])]
    #[Assert\NotBlank(message: "The name is required")]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: "The name must contain at least {{ limit }} characters",
        maxMessage: "The name must contain at most {{ limit }} characters"
    )]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Novel::class, inversedBy: 'categories')]
    #[Groups(["home:categories"])]
    private Collection $novel;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childCategories')]
    #[Groups(["category:get", "category:post"])]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $childCategories;

    public function __construct()
    {
        $this->novel = new ArrayCollection();
        $this->childCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Novel>
     */
    public function getNovel(): Collection
    {
        return $this->novel;
    }

    public function setNovel(array $novel): self {
        $this->novel = new ArrayCollection($novel);

        return $this;
    }

    public function addNovel(Novel $novel): self
    {
        if (!$this->novel->contains($novel)) {
            $this->novel->add($novel);
        }

        return $this;
    }

    public function removeNovel(Novel $novel): self
    {
        $this->novel->removeElement($novel);

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildCategories(): Collection
    {
        return $this->childCategories;
    }

    public function addChildCategory(self $childCategory): self
    {
        if (!$this->childCategories->contains($childCategory)) {
            $this->childCategories->add($childCategory);
            $childCategory->setParent($this);
        }

        return $this;
    }

    public function removeChildCategory(self $childCategory): self
    {
        if ($this->childCategories->removeElement($childCategory)) {
            // set the owning side to null (unless already changed)
            if ($childCategory->getParent() === $this) {
                $childCategory->setParent(null);
            }
        }

        return $this;
    }

}
