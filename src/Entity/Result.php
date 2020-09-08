<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Services\Statics\UniqueId;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResultRepository")
 */
class Result
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
     * @ORM\Column(type="integer")
     */
    private $choice;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subject", inversedBy="subjectResult")
     */
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist"}, inversedBy="userResult")
     */
    private $user;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $notation;

    public function __construct()
    {
        $this->eid = UniqueId::createId();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEid(): ?string
    {
        return $this->eid;
    }

    public function getChoice(): ?int
    {
        return $this->choice;
    }

    public function setChoice(int $choice): self
    {
        $this->choice = $choice;

        return $this;
    }


    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNotation(): ?int
    {
        return $this->notation;
    }

    public function setNotation(?int $notation): self
    {
        $this->notation = $notation;

        return $this;
    }
}
