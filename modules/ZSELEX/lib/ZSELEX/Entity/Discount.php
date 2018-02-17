<?php

/**
 * zselex_discounts - a content-tagging module for the Zikukla Application Framework
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
 * @ORM\Entity(repositoryClass="ZSELEX_Entity_Repository_ProductRepository")
 * @ORM\Table(name="zselex_discounts")
 */
class ZSELEX_Entity_Discount extends Zikula_EntityAccess
{
    /**
     * id field
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $discount_id;

    /**
     * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Shop")
     * @ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")
     */
    public $shop = null;

    /**
     *
     * @ORM\Column(length=250)
     */
    public $discount_code;

    /**
     *
     * @ORM\Column(length=250)
     */
    public $discount;

    /**
     * Event All Day or not
     *
     * @ORM\Column(type="boolean")
     */
    private $status;

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
     * @Gedmo\Timestampable(on="create")
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

    public function getDiscount_id()
    {
        return $this->discount_id;
    }

    public function getShop()
    {
        return $this->shop;
    }

    public function setShop(ZSELEX_Entity_Shop $shop)
    {
        $this->shop = $shop;
    }

    public function getDiscount_code()
    {
        return $this->discount_code;
    }

    public function setDiscount_code($discount_code)
    {
        $this->discount_code = $discount_code;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get created user id.
     *
     * @return integer[]
     */
    public function getCr_date()
    {
        return $this->cr_date;
    }

    /**
     * Set created user id.
     *
     * @param integer[] $createdUserId.
     *
     * @return void
     */
    public function setCr_date($cr_date)
    {
        $this->cr_date = $cr_date;
    }

    /**
     * Get updated user id.
     *
     * @return integer[]
     */
    public function getCr_uid()
    {
        return $this->cr_uid;
    }

    /**
     * Set updated user id.
     *
     * @param integer[] $updatedUserId.
     *
     * @return void
     */
    public function setCr_uid($cr_uid)
    {
        $this->cr_uid = $cr_uid;
    }

    /**
     * Get created date.
     *
     * @return datetime[]
     */
    public function getLu_date()
    {
        return $this->lu_date;
    }

    /**
     * Set created date.
     *
     * @param datetime[] $createdDate.
     *
     * @return void
     */
    public function setLu_date($lu_date)
    {
        $this->lu_date = $lu_date;
    }

    /**
     * Get updated date.
     *
     * @return datetime[]
     */
    public function getLu_uid()
    {
        return $this->lu_uid;
    }

    /**
     * Get updated date.
     *
     * @return datetime[]
     */
    public function setLu_uid($lu_uid)
    {
        $this->lu_uid = $lu_uid;
    }
}