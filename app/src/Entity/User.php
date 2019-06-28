<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="name_idx",
 *              columns={"name"},
 *          )
 *     }
 * )
 *
 * @UniqueEntity(fields={"name"})
 */
class User implements UserInterface
{
    /**
     * Role user.
     *
     * @var string
     */
    const ROLE_USER = 'ROLE_USER';

    /**
     * Role admin.
     *
     * @var string
     */
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     nullable=false,
     *     options={"unsigned"=true},
     * )
     */
    private $id;

    /**
     * Name.
     *
     * @ORM\Column(type="string", length=45)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
     */
    private $name;

    /**
     * Password.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     *
     * @SecurityAssert\UserPassword
     */
    private $password;

    /**
     * Roles.
     *
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserData", mappedBy="user", orphanRemoval=true)
     */
    private $userData;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DiaryEntry", mappedBy="user", orphanRemoval=true)
     */
    private $diaryEntries;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="user", orphanRemoval=true)
     */
    private $products;

    public function __construct()
    {
        $this->userData = new ArrayCollection();
        $this->diaryEntries = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = static::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see UserInterface
     *
     * @return string User name
     */
    public function getUsername(): string
    {
        return (string) $this->name;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using bcrypt or argon
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|UserData[]
     */
    public function getUserData(): Collection
    {
        return $this->userData;
    }

    public function addUserData(UserData $userData): self
    {
        if (!$this->userData->contains($userData)) {
            $this->userData[] = $userData;
            $userData->setUser($this);
        }

        return $this;
    }

    public function removeUserData(UserData $userData): self
    {
        if ($this->userData->contains($userData)) {
            $this->userData->removeElement($userData);
            // set the owning side to null (unless already changed)
            if ($userData->getUser() === $this) {
                $userData->setUser(null);
            }
        }

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
            $diaryEntry->setUser($this);
        }

        return $this;
    }

    public function removeDiaryEntry(DiaryEntry $diaryEntry): self
    {
        if ($this->diaryEntries->contains($diaryEntry)) {
            $this->diaryEntries->removeElement($diaryEntry);
            // set the owning side to null (unless already changed)
            if ($diaryEntry->getUser() === $this) {
                $diaryEntry->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }
}
