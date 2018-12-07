<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 */
class Orders
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Cart", mappedBy="order")
     */
    private $items;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isConfirmed;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->isConfirmed = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsConfirmed()
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed;
    }

    public function setDateTime()
    {
        $this->dateTime = new \DateTime();
    }

    public function getDateTime()
    {
        return $this->dateTime;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function addItem(Cart $item)
    {
        if($this->items->contains($item)) {
            return $this;
        }

        $this->items->add($item);

        return $this;
    }

    public function removeItem(Cart $item)
    {
        if(!$this->items->contains($item)) {
            return $this;
        }

        $this->items->removeElement($item);

        return $this;
    }


}
