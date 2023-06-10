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
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NovelRepository::class)]
class Novel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["novel:get", "novel:edit", "chapter:read","page:read", "user-novel:get", "like:get", "home:get", "home:categories"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The title is required")]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: "The title must contain at least {{ limit }} characters",
        maxMessage: "The title must contain at most {{ limit }} characters"
    )]
    #[Groups(["novel:get", "novel:edit", "chapter:read", "user-novel:get", "like:get", "home:get", "home:categories"])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The slug is required")]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: "The slug must contain at least {{ limit }} characters",
        maxMessage: "The slug must contain at most {{ limit }} characters"
    )]
    #[Assert\Regex(
        pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        message: "The slug must contain only lowercase alphanumeric characters and hyphens (-)"
    )]
    #[Groups(["novel:get", "novel:edit", "user-novel:get", "chapter:read", "home:get", "home:categories"])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        max: 500,
        maxMessage: "The resume must contain at most {{ limit }} characters"
    )]
    #[Groups(["novel:get", "novel:edit", "user-novel:get", "home:get", "home:categories"])]
    private ?string $resume = null;

    #[ORM\Column(type: 'string', columnDefinition: "ENUM('published', 'unpublished')")]
    #[Assert\Choice(
        choices: ['published', 'unpublished'],
        message: "The status must be published or unpublished"
    )]
    #[Groups(["novel:get", "novel:edit", "user-novel:get"])]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["novel:get", "novel:edit", "user-novel:get"])]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_update = null;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'novel')]
    #[Groups(["novel:get", "novel:edit", "home:get"])]
    private Collection $categories;

    // delete if novel is deleted
    #[ORM\OneToMany(mappedBy: 'novel', targetEntity: NovelImage::class, cascade: ['remove'])]
    #[Groups(["novel:get", "novel:edit", "user-novel:get", "home:get", "home:categories"])]
    private Collection $novelImages;

    #[ORM\OneToMany(mappedBy: 'novel', targetEntity: Chapter::class, cascade: ['remove'])]
    #[Groups(["novel:get", "novel:edit"])]
    private Collection $chapters;

    #[ORM\OneToMany(mappedBy: 'novel', targetEntity: UserNovel::class, cascade: ['remove'])]
    private Collection $userNovels;

    #[ORM\OneToMany(mappedBy: 'novel', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The price is required")]
    #[Assert\Positive(message: "The price must be positive")]
    #[Assert\Type(type: 'numeric', message: "The price must be a number")]
    #[Groups(["novel:get", "novel:edit"])]
    private ?int $price = null;

    #[ORM\OneToMany(mappedBy: 'novel', targetEntity: Comment::class), OrderBy(['id' => 'DESC'])]
    // #[Groups(["novel:get", "novel:edit"])]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'novel', targetEntity: Like::class)]
    private Collection $likes;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->novelImages = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->userNovels = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    #[Groups(["novel:get"])]
    public function getPublishedChapters()
    {
        $chapters = array();
        foreach ($this->chapters as $chapter) {
            if ($chapter->getStatus() === 'published') {
                array_push($chapters, $chapter);
            }
        }
        return $chapters;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(["novel:get", "user-novel:get", "home:get", "home:categories"])]
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

    #[Groups(["user-novel:get", "novel:get", "user-novel:get", "home:get", "home:categories"])]
    public function getQuantiteChapitre()
    {
        return count($this->getChapters());
    }

    #[Groups(["novel:get", "user-novel:get", "home:get", "home:categories"])]
    public function getCover()
    {
        return $this->getImageByPosition('cover');
    }

    #[Groups(["novel:get", "user-novel:get", "home:get", "home:categories"])]
    public function getBanner()
    {
        return $this->getImageByPosition('banner');
    }

    public function getImageByPosition($position){
        $image = $this->novelImages->filter(function ($novelImage) use ($position) {
            return $novelImage->getImgPosition() === $position;
        });
        return $image->first() ? $image->first()->getImage() : null;
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

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setNovel($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getNovel() === $this) {
                $order->setNovel(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setNovel($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getNovel() === $this) {
                $comment->setNovel(null);
            }
        }

        return $this;
    }

    #[Groups(["user-novel:get", "home:get", "home:categories"])]
    public function getLikesCount(): int
    {
        return count($this->likes);
    }

    #[Groups(["user-novel:get", "home:get", "home:categories"])]
    public function getCommentsCount(): int
    {
        return count($this->comments);
    }

}
