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
     * @ORM\OneToMany(targetEntity="Cart", mappedBy="order", cascade={"persist", "remove"})
     */
    private $items;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="approvedOrders")
     */
    private $admin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAccepted;

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
        $this->isAccepted = false;
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

    public function getIsAccepted()
    {
        return $this->isAccepted;
    }

    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;
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

    public function getAdmin()
    {
        return $this->admin;
    }

    public function setAdmin(User $admin)
    {
        $this->admin = $admin;
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
