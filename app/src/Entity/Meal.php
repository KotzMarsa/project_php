<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MealRepository")
 * @ORM\Table(name="meals")
 */
class Meal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DiaryEntry", mappedBy="meal")
     */
    private $diaryEntries;

    public function __construct()
    {
        $this->diaryEntries = new ArrayCollection();
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

    /**
     * @return Collection|DiaryEntry[]
     */
    public function getDiaryEntries(): Collection
    {
        return $this->diaryEntries;
    }

    public function addDiaryEntry(DiaryEntry $diaryEntry): self
    {
        if (!$this->diaryEntries->contains($diaryEntry)) {
            $this->diaryEntries[] = $diaryEntry;
            $diaryEntry->setMeal($this);
        }

        return $this;
    }

    public function removeDiaryEntry(DiaryEntry $diaryEntry): self
    {
        if ($this->diaryEntries->contains($diaryEntry)) {
            $this->diaryEntries->removeElement($diaryEntry);
            // set the owning side to null (unless already changed)
            if ($diaryEntry->getMeal() === $this) {
                $diaryEntry->setMeal(null);
            }
        }

        return $this;
    }
}
