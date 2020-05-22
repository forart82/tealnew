<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Services\Statics\UniqueId;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CsvKeyValuesRepository")
 */
class CsvKeyValues
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
    private $asValue;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

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

    public function getasValue(): ?string
    {
        return $this->asValue;
    }

    public function setasValue(string $asValue): self
    {
        $this->asValue = $asValue;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

}
