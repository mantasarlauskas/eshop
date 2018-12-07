<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartRepository")
 */
class Cart
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="users")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="Orders", inversedBy="items")
     */
    private $order;

    /**
     * @ORM\Column(type="string")
     */
    private $productTitle;

    /**
     * @ORM\Column(type="float")
     */
    private $productPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $count;


    public function getId()
    {
        return $this->id;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function incrementCount()
    {
        $this->count = $this->count + 1;
    }

    public function decrementCount()
    {
        $this->count = $this->count - 1;
    }

    public function setCount()
    {
        $this->count = 1;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    public function getProductTitle()
    {
        return $this->productTitle;
    }

    public function setProductTitle($productTitle)
    {
        $this->productTitle = $productTitle;
    }

    public function getProductPrice()
    {
        return $this->productPrice;
    }

    public function setProductPrice($productPrice)
    {
        $this->productPrice = $productPrice;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }


}
