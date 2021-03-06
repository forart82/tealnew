<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Services\Statics\UniqueId;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TranslationRepository")
 */
class Translation
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Language", inversedBy="translations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $language;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Keytext", inversedBy="translation")
     */
    private $keytext;

    public function __construct()
    {
        $this->eid=UniqueId::createId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEid(): ?string
    {
        return $this->eid;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getKeytext(): ?Keytext
    {
        return $this->keytext;
    }

    public function setKeytext(?Keytext $keytext): self
    {
        $this->keytext = $keytext;

        return $this;
    }
}
