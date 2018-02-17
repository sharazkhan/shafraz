<?php

/**
 * Zselex_country - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;

/**
 * Tags entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity(repositoryClass="ZSELEX_Entity_Repository_ProductRepository")
 * @ORM\Table(name="zselex_products" , indexes={@ORM\index(name="urltitle", columns={"urltitle"})})
 */
class ZSELEX_Entity_Product extends Zikula_EntityAccess
{
    /**
     * id field
     *
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $product_id;

    /**
     * module field (product_name)
     *
     * @ORM\Column(length=255 , nullable=true)
     */
    private $product_name;

    /**
     * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Shop", inversedBy="shop_products")
     * @ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")
     */
    public $shop = null;

    /**
     * field (urltitle)
     *
     * @ORM\Column(length=255 , nullable=true)
     */
    private $urltitle;

    /**
     * field (prd_description)
     *
     * @ORM\Column(type="text" , nullable=true)
     */
    private $prd_description;

    /**
     * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Manufacturer", inversedBy="product_manufacturer")
     * @ORM\JoinColumn(name="manufacturer_id", referencedColumnName="manufacturer_id")
     */
    public $manufacturer = null;

    /**
     * field (keywords)
     *
     * @ORM\Column(type="text" , nullable=true)
     */
    private $keywords;

    /**
     * field (original_price)
     *
     * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
     */
    private $original_price = 0.0000;

    /**
     * field (prd_price)
     *
     * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
     */
    private $prd_price = 0.0000;

    /**
     * field (discount)
     *
     * @ORM\Column(length=255 , options={"default" = 0} , nullable=true)
     */
    private $discount = 0;

    /**
     * field (prd_price)
     *
     * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
     */
    private $shipping_price;

    /**
     * field (prd_quantity)
     *
     * @ORM\Column(type="integer" , options={"default" = 0} , nullable=true)
     */
    private $prd_quantity = 0;

    /**
     * field (prd_image)
     *
     * @ORM\Column(length="255" , nullable=true)
     */
    private $prd_image;

    /**
     * field (prd_status)
     *
     * @ORM\Column(type="boolean" , nullable=true)
     */
    private $enable_question;

    /**
     * field (prd_status)
     *
     * @ORM\Column(type="boolean" , nullable=true)
     */
    private $validate_question;

    /**
     * field (prd_image)
     *
     * @ORM\Column(length="255" , nullable=true)
     */
    private $prd_question;

    /**
     * field (no_vat)
     *
     * @ORM\Column(type="boolean" , nullable=true)
     */
    private $no_vat = 0;

    /**
     * field (prd_status)
     *
     * @ORM\Column(type="boolean" , nullable=true)
     */
    private $prd_status;

    /**
     * field (prd_status)
     *
     * @ORM\Column(type="boolean" , nullable=true)
     */
    private $advertise;

    /**
     * field (max_discount)
     *
     * @ORM\Column(type="string" , length=10 , nullable=true)
     */
    private $max_discount;

    /**
     * field (no_delivery)
     *
     * @ORM\Column(type="boolean" , nullable=true)
     */
    private $no_delivery = 0;

    /**
     * @ORM\Column(type="datetime")
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
     * @ORM\Column(type="datetime")
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
     * @ORM\OneToMany(targetEntity="ZSELEX_Entity_ProductToOption" , cascade={"all"} , mappedBy="product")
     */
    public $product_options;

    /**
     * @ORM\OneToMany(targetEntity="ZSELEX_Entity_ProductToOptionValue" , cascade={"all"} , mappedBy="product")
     */
    public $product_options_values;

    /**
     * @ORM\OneToMany(targetEntity="ZSELEX_Entity_QuantityDiscount" , cascade={"all"} , mappedBy="product")
     */
    public $quantity_discounts;

    /**
     * @ORM\ManyToMany(targetEntity="ZSELEX_Entity_ProductCategory" , inversedBy="productcategory_products" , cascade={"all"})
     * @ORM\JoinTable(name="zselex_product_to_category",
     * joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="product_id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="prd_cat_id", referencedColumnName="prd_cat_id")}
     * )
     */
    protected $product_to_category;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product_to_category = new ArrayCollection ();
        $this->product_options     = new ArrayCollection ();
        $this->quantity_discounts  = new ArrayCollection ();
    }

    public function addCategory(ZSELEX_Entity_ProductCategory $category = null)
    {
        $this->product_to_category->add($category);
    }

    public function removeCategory(ZSELEX_Entity_ProductCategory $category)
    {
        $this->product_to_category->removeElement($category);
    }

    /**
     * get the record ID
     *
     * @return integer
     */
    public function getProduct_id()
    {
        return $this->product_id;
    }

    /**
     * get the product_name
     *
     * @return string
     */
    public function getProduct_name()
    {
        return $this->product_name;
    }

    /**
     * set the product_name
     *
     * @param string $product_name
     */
    public function setProduct_name($product_name)
    {
        $this->product_name = $product_name;
    }

    /**
     * get the shop ID
     *
     * @return integer
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * set the shop ID
     *
     * @param int $shop
     */
    public function setShop(ZSELEX_Entity_Shop $shop)
    {
        $this->shop = $shop;
    }

    /**
     * get the urltitle
     *
     * @return string urltitle
     */
    public function getUrltitle()
    {
        return $this->urltitle;
    }

    /**
     * set the urltitle
     *
     * @param
     *        	string urltitle
     */
    public function setUrltitle($urltitle)
    {
        $this->urltitle = $urltitle;
    }

    /**
     * get the prd_description
     *
     * @return string prd_description
     */
    public function getPrd_description()
    {
        return $this->prd_description;
    }

    /**
     * set the prd_description
     *
     * @param
     *        	string prd_description
     */
    public function setPrd_description($prd_description)
    {
        $this->prd_description = $prd_description;
    }

    /**
     * get the keywords
     *
     * @return string keywords
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * set the keywords
     *
     * @param
     *        	string keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * get the original_price
     *
     * @return string original_price
     */
    public function getOriginal_price()
    {
        return $this->original_price;
    }

    /**
     * set the original_price
     *
     * @param
     *        	string original_price
     */
    public function setOriginal_price($original_price)
    {
        $this->original_price = $original_price;
    }

    /**
     * get the prd_price
     *
     * @return string prd_price
     */
    public function getPrd_price()
    {
        return $this->prd_price;
    }

    /**
     * set the prd_price
     *
     * @param
     *        	string prd_price
     */
    public function setPrd_price($prd_price)
    {
        $this->prd_price = $prd_price;
    }

    /**
     * get the discount
     *
     * @return string discount
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * set the discount
     *
     * @param
     *        	string discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * get the prd_quantity
     *
     * @return string prd_quantity
     */
    public function getPrd_quantity()
    {
        return $this->prd_quantity;
    }

    /**
     * set the prd_quantity
     *
     * @param
     *        	string prd_quantity
     */
    public function setPrd_quantity($prd_quantity)
    {
        $this->prd_quantity = $prd_quantity;
    }

    /**
     * get the prd_image
     *
     * @return string prd_image
     */
    public function getPrd_image()
    {
        return $this->prd_image;
    }

    /**
     * set the prd_image
     *
     * @param
     *        	string prd_image
     */
    public function setPrd_image($prd_image)
    {
        $this->prd_image = $prd_image;
    }

    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    public function setManufacturer(ZSELEX_Entity_Manufacturer $manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }

    public function getPrd_status()
    {
        return $this->prd_status;
    }

    public function setPrd_status($prd_status)
    {
        $this->prd_status = $prd_status;
    }

    public function getEnable_ques()
    {
        return $this->enable_ques;
    }

    public function setPrd_question($prd_question)
    {
        $this->prd_question = $prd_question;
    }

    public function getProduct_to_category()
    {
        return $this->product_to_category;
    }

    public function setProduct_to_category($product_to_category)
    {
        $this->product_to_category = $product_to_category;
    }

    public function setPrd_discounts($prd_discounts)
    {
        $this->prd_discounts = $prd_discounts;
    }

    public function getPrd_discounts()
    {
        return $this->prd_discounts;
    }

    public function setNo_vat($no_vat)
    {
        $this->no_vat = $no_vat;
    }

    public function getNo_vat()
    {
        return $this->no_vat;
    }

    public function setMax_discount($max_discount)
    {
        $this->max_discount = $max_discount;
    }

    public function getMax_discount()
    {
        return $this->max_discount;
    }

    public function setNo_delivery($no_delivery)
    {
        $this->no_delivery = $no_delivery;
    }

    public function getNo_delivery()
    {
        return $this->no_delivery;
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