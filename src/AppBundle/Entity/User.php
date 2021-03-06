<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Cart", mappedBy="user",cascade={"persist", "remove"})
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="Orders", mappedBy="user", cascade={"persist", "remove"})
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity="Orders", mappedBy="admin", cascade={"persist", "remove"})
     */
    private $approvedOrders;

    /**
     * @ORM\OneToMany(targetEntity="Report", mappedBy="user", cascade={"persist", "remove"})
     */
    private $logs;

    public function __construct()
    {
        parent::__construct();

        $this->roles = array('ROLE_USER');
        $this->products = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->approvedOrders = new ArrayCollection();
        $this->logs = new ArrayCollection();
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function addProduct(Cart $product)
    {
        if($this->products->contains($product)) {
            return $this;
        }

        $this->products->add($product);

        return $this;
    }

    public function removeProduct(Cart $product)
    {
        if(!$this->products->contains($product)) {
            return $this;
        }

        $this->products->removeElement($product);

        return $this;
    }

    public function addOrder(Orders $order)
    {
        if($this->orders->contains($order)) {
            return $this;
        }

        $this->orders->add($order);

        return $this;
    }

    public function removeOrder(Orders $order)
    {
        if(!$this->orders->contains($order)) {
            return $this;
        }

        $this->orders->removeElement($order);

        return $this;
    }
}