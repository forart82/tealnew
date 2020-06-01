<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Services\Statics\UniqueId;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NavigationsRepository")
 */
class Navigations
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
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $subPosition;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $authorisation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Svg", inversedBy="navigations")
     */
    private $svg;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $translation;

    public function __construct()
    {
        $this->eid = UniqueId::createId();
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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getSubPosition(): ?int
    {
        return $this->subPosition;
    }

    public function setSubPosition(?int $subPosition): self
    {
        $this->subPosition = $subPosition;

        return $this;
    }

    public function getAuthorisation(): ?string
    {
        return $this->authorisation;
    }

    public function setAuthorisation(string $authorisation): self
    {
        $this->authorisation = $authorisation;

        return $this;
    }

    public function getSvg(): ?Svg
    {
        return $this->svg;
    }

    public function setSvg(?Svg $svg): self
    {
        $this->svg = $svg;

        return $this;
    }

    public function getTranslation(): ?string
    {
        return $this->translation;
    }

    public function setTranslation(string $translation): self
    {
        $this->translation = $translation;

        return $this;
    }
}
