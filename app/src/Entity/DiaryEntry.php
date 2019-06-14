<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DiaryEntryRepository")
 * @ORM\Table(name="diary_entries")
 */
class DiaryEntry
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options.
     *
     * @constant int NUMBER_OF_ITEMS
     */
    const NUMBER_OF_ITEMS = 50;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \Date
     *
     * @ORM\Column(type="datetime")
     *
     */
    private $date;

    /**
     * @ORM\Column(type="smallint")
     */
    private $serving;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="diaryEntries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Meal", inversedBy="diaryEntries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $meal;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     *
     * @return DiaryEntry
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getServing(): ?int
    {
        return $this->serving;
    }

    /**
     * @param int $serving
     *
     * @return DiaryEntry
     */
    public function setServing(int $serving): self
    {
        $this->serving = $serving;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRelation(): ?string
    {
        return $this->relation;
    }

    /**
     * @param string $relation
     *
     * @return DiaryEntry
     */
    public function setRelation(string $relation): self
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     *
     * @return DiaryEntry
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Meal|null
     */
    public function getMeal(): ?Meal
    {
        return $this->meal;
    }

    /**
     * @param Meal|null $meal
     *
     * @return DiaryEntry
     */
    public function setMeal(?Meal $meal): self
    {
        $this->meal = $meal;

        return $this;
    }
}
