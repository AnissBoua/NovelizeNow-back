<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["novel:get", "novel:edit"])]
    private ?string $filename = null;

    #[ORM\OneToMany(mappedBy: 'image', targetEntity: NovelImage::class)]
    private Collection $novelImages;

    public function __construct()
    {
        $this->novelImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

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
            $novelImage->setImage($this);
        }

        return $this;
    }

    public function removeNovelImage(NovelImage $novelImage): self
    {
        if ($this->novelImages->removeElement($novelImage)) {
            // set the owning side to null (unless already changed)
            if ($novelImage->getImage() === $this) {
                $novelImage->setImage(null);
            }
        }

        return $this;
    }
}
