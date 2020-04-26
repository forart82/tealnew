<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(type="integer")
     */
    private $choice;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subject", inversedBy="subjectResult", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idSubject;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userResult", fetch="EAGER")
     */
    private $idUser;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $notation;

    public function __construct()
    {
        $this->idUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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


    public function getIdSubject(): ?Subject
    {
        return $this->idSubject;
    }

    public function setIdSubject($idSubject)
    {
        $this->idSubject = $idSubject;

        return $this;
    }

    public function getIdUser():User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

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
