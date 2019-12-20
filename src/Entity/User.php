<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $age;

    /**
     * @ORM\Column(type="boolean")
     */
    private $male;

    /**
     * @ORM\Column(type="boolean")
     */
    private $target;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="users")
     */
    private $favoriteProducts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="favoriteUsers")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="user")
     */
    private $favoriteUsers;

    public function __construct()
    {
        $this->favoriteProducts = new ArrayCollection();
        $this->favoriteUsers = new ArrayCollection();
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

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getMale(): ?bool
    {
        return $this->male;
    }

    public function setMale(bool $male): self
    {
        $this->male = $male;

        return $this;
    }

    public function getTarget(): ?bool
    {
        return $this->target;
    }

    public function setTarget(bool $target): self
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getFavoriteProducts(): Collection
    {
        return $this->favoriteProducts;
    }

    public function addFavoriteProduct(Product $favoriteProduct): self
    {
        if (!$this->favoriteProducts->contains($favoriteProduct)) {
            $this->favoriteProducts[] = $favoriteProduct;
            $favoriteProduct->setUsers($this);
        }

        return $this;
    }

    public function removeFavoriteProduct(Product $favoriteProduct): self
    {
        if ($this->favoriteProducts->contains($favoriteProduct)) {
            $this->favoriteProducts->removeElement($favoriteProduct);
            // set the owning side to null (unless already changed)
            if ($favoriteProduct->getUsers() === $this) {
                $favoriteProduct->setUsers(null);
            }
        }

        return $this;
    }

    public function getUser(): ?self
    {
        return $this->user;
    }

    public function setUser(?self $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFavoriteUsers(): Collection
    {
        return $this->favoriteUsers;
    }

    public function addFavoriteUser(self $favoriteUser): self
    {
        if (!$this->favoriteUsers->contains($favoriteUser)) {
            $this->favoriteUsers[] = $favoriteUser;
            $favoriteUser->setUser($this);
        }

        return $this;
    }

    public function removeFavoriteUser(self $favoriteUser): self
    {
        if ($this->favoriteUsers->contains($favoriteUser)) {
            $this->favoriteUsers->removeElement($favoriteUser);
            // set the owning side to null (unless already changed)
            if ($favoriteUser->getUser() === $this) {
                $favoriteUser->setUser(null);
            }
        }

        return $this;
    }

}
