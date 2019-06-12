<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="products")
 */
class Product
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     *
     * @constant int NUMBER_OF_ITEMS
     */
    const NUMBER_OF_ITEMS = 10;

    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Product name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $productName;

    /**
     * Calories.
     *
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $calories;

    /**
     * Carbohydrate.
     *
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $carbohydrate;

    /**
     * Protein.
     *
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $protein;

    /**
     * Fat.
     *
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $fat;

    /**
     * Is accepted.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isAccepted;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DiaryEntry", mappedBy="product")
     */
    private $diaryEntries;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function __construct()
    {
        $this->diaryEntries = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Product name.
     *
     * @return string|null
     */
    public function getProductName(): ?string
    {
        return $this->productName;
    }

    /**
     * Setter for Product name.
     *
     * @param string $productName
     *
     * @return Product
     */
    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Getter for Calories.
     *
     * @return int|null
     */
    public function getCalories(): ?int
    {
        return $this->calories;
    }

    /**
     * Setter for Calories.
     *
     * @param int $calories
     *
     * @return Product
     */
    public function setCalories(int $calories): self
    {
        $this->calories = $calories;

        return $this;
    }

    /**
     * Getter for Carbohydrate.
     *
     * @return int|null
     */
    public function getCarbohydrate(): ?int
    {
        return $this->carbohydrate;
    }

    /**
     * Setter for Carbohydrate.
     *
     * @param int $carbohydrate
     *
     * @return Product
     */
    public function setCarbohydrate(int $carbohydrate): self
    {
        $this->carbohydrate = $carbohydrate;

        return $this;
    }

    /**
     * Getter for Protein.
     *
     * @return int|null
     */
    public function getProtein(): ?int
    {
        return $this->protein;
    }

    /**
     * Setter for Protein.
     *
     * @param int $protein
     *
     * @return Product
     */
    public function setProtein(int $protein): self
    {
        $this->protein = $protein;

        return $this;
    }

    /**
     * Getter for Fat.
     *
     * @return int|null
     */
    public function getFat(): ?int
    {
        return $this->fat;
    }

    /**
     * Setter for Fat.
     *
     * @param int $fat
     *
     * @return Product
     */
    public function setFat(int $fat): self
    {
        $this->fat = $fat;

        return $this;
    }

    /**
     * Getter for isAccepted.
     *
     * @return bool|null
     */
    public function getIsAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    /**
     * Setter for isAccepted.
     *
     * @param bool $isAccepted
     *
     * @return Product
     */
    public function setIsAccepted(bool $isAccepted): self
    {
        $this->isAccepted = $isAccepted;

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
            $diaryEntry->setProduct($this);
        }

        return $this;
    }

    public function removeDiaryEntry(DiaryEntry $diaryEntry): self
    {
        if ($this->diaryEntries->contains($diaryEntry)) {
            $this->diaryEntries->removeElement($diaryEntry);
            // set the owning side to null (unless already changed)
            if ($diaryEntry->getProduct() === $this) {
                $diaryEntry->setProduct(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
