<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserDataRepository")
 * @ORM\Table(name="user_datas")
 */
class UserData
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
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     *
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 32000,
     * )
     */
    private $calorieGoal;

    /**
     * @ORM\Column(type="smallint")
     *
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 400,
     * )
     */
    private $weight;

    /**
     * @ORM\Column(type="smallint")
     *
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 400,
     * )
     */
    private $height;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userData")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCalorieGoal(): ?int
    {
        return $this->calorieGoal;
    }

    public function setCalorieGoal(int $calorieGoal): self
    {
        $this->calorieGoal = $calorieGoal;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
}
