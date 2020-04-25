<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImagesRepository")
 */
class Images
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $imageblob;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $svg;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $svgColor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Navigations", mappedBy="image", orphanRemoval=true)
     */
    private $navigations;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    public function __construct()
    {
        $this->navigations = new ArrayCollection();
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

    public function getImageblob()
    {
        return $this->imageblob;
    }

    public function setImageblob($imageblob): self
    {
        $this->imageblob = $imageblob;

        return $this;
    }

    public function getSvg(): ?string
    {
        return $this->svg;
    }

    public function setSvg(?string $svg): self
    {
        $this->svg = $svg;

        return $this;
    }

    public function getSvgColor(): ?string
    {
        return $this->svgColor;
    }

    public function setSvgColor(?string $svgColor): self
    {
        $this->svgColor = $svgColor;

        return $this;
    }

    /**
     * @return Collection|Navigations[]
     */
    public function getNavigations(): Collection
    {
        return $this->navigations;
    }

    public function addNavigation(Navigations $navigation): self
    {
        if (!$this->navigations->contains($navigation)) {
            $this->navigations[] = $navigation;
            $navigation->setImage($this);
        }

        return $this;
    }

    public function removeNavigation(Navigations $navigation): self
    {
        if ($this->navigations->contains($navigation)) {
            $this->navigations->removeElement($navigation);
            // set the owning side to null (unless already changed)
            if ($navigation->getImage() === $this) {
                $navigation->setImage(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }
}
