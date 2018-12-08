<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="Cart", mappedBy="product", cascade={"persist", "remove"})
     */
    private $users;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $count;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setCount($count)
    {
        $this->count = $count;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function decrementCount()
    {
        $this->count = $this->count - 1;
    }

    public function incrementCount()
    {
        $this->count = $this->count + 1;
    }

    public function addUser(Cart $user)
    {
        if($this->users->contains($user)) {
            return $this;
        }

        $this->users->add($user);

        return $this;
    }

    public function removeUser(Cart $user)
    {
        if(!$this->users->contains($user)) {
            return $this;
        }

        $this->users->removeElement($user);

        return $this;
    }

}
