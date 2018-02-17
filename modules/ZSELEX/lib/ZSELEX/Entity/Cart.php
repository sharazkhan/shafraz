<?php

/**
 * zselex_cart - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo; // Add behaviours
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;

/**
 * Tags entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity(repositoryClass="ZSELEX_Entity_Repository_CartRepository")
 * @ORM\Table(name="zselex_cart")
 */
class ZSELEX_Entity_Cart extends Zikula_EntityAccess
{
    /**
     * id field
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $cart_id;

    /**
     * user id
     *
     * @ORM\Column(length=250 , nullable=true)
     */
    private $user_id = 0;

    /**
     * @ORM\Column(type="smallint" , nullable=true)
     */
    private $is_guest = 0;

    /**
     * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
     */
    public $product = null;

    /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $quantity = 0;

    /**
     * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Shop")
     * @ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")
     */
    public $shop = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cart_content = '';

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $prd_answer = '';

    /**
     * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
     */
    private $original_price = 0.0000;

    /**
     * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
     */
    private $price = 0.0000;

    /**
     * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
     */
    private $options_price = 0.0000;

    /**
     * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
     */
    private $final_price = 0.0000;

    /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $stock = 0;

    /**
     * @ORM\Column(type="smallint" , nullable=true)
     */
    private $outofstock = 0;

    /**
     * @ORM\Column(type="boolean" , nullable=true)
     */
    private $discount_applied = 0;

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    public function getCart_id()
    {
        return $this->cart_id;
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct(ZSELEX_Entity_Product $product)
    {
        $this->product = $product;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getShop()
    {
        return $this->shop;
    }

    public function setShop(ZSELEX_Entity_Shop $shop)
    {
        $this->shop = $shop;
    }

    public function getCart_content()
    {
        return $this->cart_content;
    }

    public function setCart_content($cart_content)
    {
        $this->cart_content = $cart_content;
    }

    public function getOriginal_price()
    {
        return $this->original_price;
    }

    public function setOriginal_price($original_price)
    {
        $this->original_price = $original_price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getOptions_price()
    {
        return $this->options_price;
    }

    public function setOptions_price($options_price)
    {
        $this->options_price = $options_price;
    }

    public function getFinal_price()
    {
        return $this->final_price;
    }

    public function setFinal_price($final_price)
    {
        $this->final_price = $final_price;
    }

    public function getOutofstock()
    {
        return $this->outofstock;
    }

    public function setOutofstock($outofstock)
    {
        $this->outofstock = $outofstock;
    }

    public function getPrd_answer()
    {
        return $this->prd_answer;
    }

    public function setPrd_answer($prd_answer)
    {
        $this->prd_answer = $prd_answer;
    }

    public function getIs_guest()
    {
        return $this->is_guest;
    }

    public function setIs_guest($iGuest)
    {
        $this->is_guest = $iGuest;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    public function getDiscount_applied()
    {
        return $this->discount_applied;
    }

    public function setDiscount_applied($discount_applied)
    {
        $this->discount_applied = $stock;
    }
}