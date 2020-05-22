<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Services\Statics\UniqueId;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SvgRepository")
 */
class Svg
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
    private $eid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $svg;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $svgColor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Navigations", mappedBy="svg")
     */
    private $navigations;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    public function __construct()
    {
        $this->eid=UniqueId::createId();
        $this->navigations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEid(): ?string
    {
        return $this->eid;
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
            $navigation->setSvg($this);
        }

        return $this;
    }

    public function removeNavigation(Navigations $navigation): self
    {
        if ($this->navigations->contains($navigation)) {
            $this->navigations->removeElement($navigation);
            // set the owning side to null (unless already changed)
            if ($navigation->getSvg() === $this) {
                $navigation->setSvg(null);
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
