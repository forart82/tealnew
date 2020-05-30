<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Services\Statics\UniqueId;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KeytextRepository")
 */
class Keytext
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
    private $keytext;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Translation", mappedBy="keytext")
     */
    private $translation;

    public function __toString()
    {
        return (string) $this->getKeytext();
    }

    public function __construct()
    {
        $this->eid=UniqueId::createId();
        $this->translation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEid(): ?string
    {
        return $this->eid;
    }

    public function getKeytext(): ?string
    {
        return $this->keytext;
    }

    public function setKeytext(string $keytext): self
    {
        $this->keytext = $keytext;

        return $this;
    }

    /**
     * @return Collection|Translation[]
     */
    public function getTranslation(): Collection
    {
        return $this->translation;
    }

    public function addTranslation(Translation $translation): self
    {
        if (!$this->translation->contains($translation)) {
            $this->translation[] = $translation;
            $translation->setKeytext($this);
        }

        return $this;
    }

    public function removeTranslation(Translation $translation): self
    {
        if ($this->translation->contains($translation)) {
            $this->translation->removeElement($translation);
            // set the owning side to null (unless already changed)
            if ($translation->getKeytext() === $this) {
                $translation->setKeytext(null);
            }
        }

        return $this;
    }
}
