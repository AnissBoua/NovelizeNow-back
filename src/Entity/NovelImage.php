<?php

namespace App\Entity;

use App\Repository\NovelImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NovelImageRepository::class)]
class NovelImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column( type: 'string', columnDefinition:"ENUM('cover', 'banner')")]
    #[Groups(["novel:get", "novel:edit"])]
    private ?string $imgPosition = null;

    #[ORM\ManyToOne(inversedBy: 'novelImages')]
    #[Groups(["novel:get", "novel:edit"])]
    private ?Image $image = null;

    #[ORM\ManyToOne(inversedBy: 'novelImages')]
    private ?Novel $novel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImgPosition(): ?string
    {
        return $this->imgPosition;
    }

    public function setImgPosition(?string $imgPosition): self
    {
        $this->imgPosition = $imgPosition;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

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
