<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubjectRepository")
 */
class Subject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
   */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $question;

    /**
     * @ORM\Column(type="text" )
     * @Assert\Length(min=2, minMessage="Veuillez vérifier votre saisie")
     */
    private $answerOne;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=2, minMessage="Veuillez vérifier votre saisie")
     */
    private $answerTwo;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=2, minMessage="Veuillez vérifier votre saisie")
     */
    private $answerThree;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=2, minMessage="Veuillez vérifier votre saisie")
     */
    private $answerFour;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=2, minMessage="Veuillez vérifier votre saisie")
     */
    private $answerFive;

    /**
     * @ORM\Column(type="integer",options={"position" : 1}))
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Result", mappedBy="subject")
     */
    private $subjectResult;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=2, minMessage="Veuillez vérifier votre saisie")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Svg", inversedBy="subjects")
     * @ORM\JoinColumn(name="svg_id", referencedColumnName="id",  onDelete="SET NULL")
     */
    private $svg;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $language;

    public function __construct()
    {
        $this->subjectResult = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswerOne(): ?string
    {
        return $this->answerOne;
    }

    public function setAnswerOne(string $answerOne): self
    {
        $this->answerOne = $answerOne;

        return $this;
    }

    public function getAnswerTwo(): ?string
    {
        return $this->answerTwo;
    }

    public function setAnswerTwo(string $answerTwo): self
    {
        $this->answerTwo = $answerTwo;

        return $this;
    }

    public function getAnswerThree(): ?string
    {
        return $this->answerThree;
    }

    public function setAnswerThree(string $answerThree): self
    {
        $this->answerThree = $answerThree;

        return $this;
    }

    public function getAnswerFour(): ?string
    {
        return $this->answerFour;
    }

    public function setAnswerFour(string $answerFour): self
    {
        $this->answerFour = $answerFour;

        return $this;
    }

    public function getAnswerFive(): ?string
    {
        return $this->answerFive;
    }

    public function setAnswerFive(string $answerFive): self
    {
        $this->answerFive = $answerFive;

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

    /**
     * @return Collection|Result[]
     */
    public function getSubjectResult(): Collection
    {
        return $this->subjectResult;
    }

    public function addSubjectResult(Result $subjectResult): self
    {
        if (!$this->subjectResult->contains($subjectResult)) {
            $this->subjectResult[] = $subjectResult;
            $subjectResult->setSubject($this);
        }

        return $this;
    }

    public function removeSubjectResult(Result $subjectResult): self
    {
        if ($this->subjectResult->contains($subjectResult)) {
            $this->subjectResult->removeElement($subjectResult);
            // set the owning side to null (unless already changed)
            if ($subjectResult->getSubject() === $this) {
                $subjectResult->setSubject(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSvg(): ?Svg
    {
        return $this->svg;
    }

    public function setSvg(?Svg $svg): self
    {
        $this->Svg = $svg;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }
}
