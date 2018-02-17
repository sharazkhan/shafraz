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
 * @ORM\Entity(repositoryClass="ZSELEX_Entity_Repository_OrderRepository")
 * @ORM\Table(name="zselex_order")
 */
class ZSELEX_Entity_Order extends Zikula_EntityAccess
{
    /**
     * id field
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * user id
     *
     * @ORM\Column(length=255, nullable=true)
     */
    private $order_id;

    /**
     * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Shop")
     * @ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")
     */
    public $shop;

    /**
     * user id
     *
     * @ORM\Column(length=255, nullable=true)
     */
    private $user_id;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private $first_name;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private $last_name;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private $zip;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
     */
    private $totalprice;

    /**
     * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
     */
    private $vat = '0.0000';

    /**
     * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
     */
    private $shipping = '0.0000';

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private $payment_type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $self_pickup = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $completed = 0;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Timestampable(on="create")
     * 
     * @var datetime $cr_date.
     */
    protected $cr_date;

    /**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="create")
     *
     * @var integer $cr_uid.
     */
    protected $cr_uid;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Timestampable(on="update")
     * 
     * @var datetime $lu_date.
     */
    protected $lu_date;

    /**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="update")
     *
     * @var integer $lu_uid.
     */
    protected $lu_uid;

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    public function getId()
    {
        return $this->id;
    }

    public function getOrder_id()
    {
        return $this->order_id;
    }

    public function setOrder_id($order_id)
    {
        $this->order_id = $order_id;
    }

    public function getShop()
    {
        return $this->shop;
    }

    public function setShop(ZSELEX_Entity_Shop $shop)
    {
        $this->shop = $shop;
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getFirst_name()
    {
        return $this->first_name;
    }

    public function setFirst_name($first_name)
    {
        $this->first_name = $first_name;
    }

    public function getLast_name()
    {
        return $this->last_name;
    }

    public function setLast_name($last_name)
    {
        $this->last_name = $last_name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet($street)
    {
        $this->street = $street;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getTotalprice()
    {
        return $this->totalprice;
    }

    public function setTotalprice($totalprice)
    {
        $this->totalprice = $totalprice;
    }

    public function getVat()
    {
        return $this->vat;
    }

    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    public function getShipping()
    {
        return $this->shipping;
    }

    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
    }

    public function getFreight()
    {
        return $this->freight;
    }

    public function setFreight($freight)
    {
        $this->freight = $freight;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getPayment_type()
    {
        return $this->payment_type;
    }

    public function setPayment_type($payment_type)
    {
        $this->payment_type = $payment_type;
    }

    public function getSelf_pickup()
    {
        return $this->self_pickup;
    }

    public function setSelf_pickup($selfPickup)
    {
        $this->self_pickup = $selfPickup;
    }

    public function getCompleted()
    {
        return $this->completed;
    }

    public function setCompleted($completed)
    {
        $this->completed = $completed;
    }

    public function getCr_date()
    {
        return $this->cr_date;
    }

    public function setCr_date($cr_date)
    {
        $this->cr_date = $cr_date;
    }

    public function getCr_uid()
    {
        return $this->cr_uid;
    }

    public function setCr_uid($cr_uid)
    {
        $this->cr_uid = $cr_uid;
    }

    public function getLu_date()
    {
        return $this->lu_date;
    }

    public function setLu_date($lu_date)
    {
        $this->lu_date = $lu_date;
    }

    public function getLu_uid()
    {
        return $this->lu_uid;
    }

    public function setLu_uid($lu_uid)
    {
        $this->lu_uid = $lu_uid;
    }
}