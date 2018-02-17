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
use Gedmo\Mapping\Annotation as Gedmo; // Add behaviours
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;

/**
 * Tags entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity(repositoryClass="ZSELEX_Entity_Repository_BundleRepo")
 * @ORM\Table(name="zselex_service_bundles")
 */
class ZSELEX_Entity_Bundle extends Zikula_EntityAccess
{
    /**
     * id field
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $bundle_id;

    /**
     * module field (product_name)
     *
     * @ORM\Column(length=255 , nullable=true)
     */
    public $bundle_name;

    /**
     * field (urltitle)
     *
     * @ORM\Column(length=255 , nullable=true)
     */
    private $type;

    /**
     * field (original_price)
     *
     * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
     */
    private $bundle_price;

    /**
     * field (original_price)
     *
     * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
     */
    private $calculated_price;

    /**
     * field (prd_description)
     *
     * @ORM\Column(type="text" , nullable=true)
     */
    private $bundle_description;

    /**
     * field (keywords)
     *
     * @ORM\Column(type="text" , nullable=true)
     */
    private $content = '';

    /**
     * @ORM\Column(length=255 , nullable=true)
     */
    private $bundle_type = '';

    /**
     * field (prd_status)
     *
     * @ORM\Column(type="smallint" , nullable=true)
     */
    private $demo;

    /**
     * field (prd_description)
     *
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $demoperiod;

    /**
     * field (is_free)
     *
     * @ORM\Column(type="boolean" , nullable=true)
     */
    private $is_free = 0;

    /**
     * field (prd_description)
     *
     * @ORM\Column(type="integer")
     */
    private $sort_order = 0;

    /**
     * field (prd_description)
     *
     * @ORM\Column(type="boolean" , nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime" , nullable=true)
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
     * @ORM\Column(type="datetime" , nullable=true)
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
     * @ORM\OneToMany(targetEntity="ZSELEX_Entity_BundleItem" ,cascade={"all"}, mappedBy="bundle")
     */
    public $bundle_items;

    /**
     * @ORM\OneToMany(targetEntity="ZSELEX_Entity_ServiceBundle" ,cascade={"all"}, mappedBy="bundle")
     */
    public $bundle_services;

    /**
     * @ORM\OneToMany(targetEntity="ZSELEX_Entity_ServiceDemo" ,cascade={"all"}, mappedBy="bundle")
     */
    public $bundle_demos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bundle_items    = new ArrayCollection ();
        $this->bundle_demos    = new ArrayCollection ();
        $this->bundle_services = new ArrayCollection ();
    }

    /**
     * get the record ID
     *
     * @return integer
     */
    public function getBundle_id()
    {
        return $this->bundle_id;
    }

    /**
     * get the product_name
     *
     * @return string
     */
    public function getBundle_name()
    {
        return $this->bundle_name;
    }

    /**
     * set the product_name
     *
     * @param string $product_name
     */
    public function setBundle_name($bundle_name)
    {
        $this->bundle_name = $bundle_name;
    }

    /**
     * get the shop ID
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * set the shop ID
     *
     * @param int $shop
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * get the category ID
     *
     * @return integer
     */
    public function getBundle_price()
    {
        return $this->bundle_price;
    }

    /**
     * set the category ID
     *
     * @param
     *        	integer
     */
    public function setBundle_price($bundle_price)
    {
        $this->bundle_price = $bundle_price;
    }

    /**
     * get the urltitle
     *
     * @return string urltitle
     */
    public function getCalculated_price()
    {
        return $this->calculated_price;
    }

    /**
     * set the urltitle
     *
     * @param
     *        	string urltitle
     */
    public function setCalculated_price($calculated_price)
    {
        $this->calculated_price = $calculated_price;
    }

    /**
     * get the prd_description
     *
     * @return string prd_description
     */
    public function getBundle_description()
    {
        return $this->bundle_description;
    }

    /**
     * set the prd_description
     *
     * @param
     *        	string prd_description
     */
    public function setBundle_description($bundle_description)
    {
        $this->bundle_description = $bundle_description;
    }

    /**
     * get the keywords
     *
     * @return string keywords
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * set the keywords
     *
     * @param
     *        	string keywords
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * get the original_price
     *
     * @return string original_price
     */
    public function getBundle_type()
    {
        return $this->bundle_type;
    }

    /**
     * set the original_price
     *
     * @param
     *        	string original_price
     */
    public function setBundle_type($bundle_type)
    {
        $this->bundle_type = $bundle_type;
    }

    /**
     * get the prd_price
     *
     * @return string prd_price
     */
    public function getDemo()
    {
        return $this->demo;
    }

    /**
     * set the prd_price
     *
     * @param
     *        	string prd_price
     */
    public function setDemo($demo)
    {
        $this->demo = $demo;
    }

    /**
     * get the discount
     *
     * @return string discount
     */
    public function getDemoperiod()
    {
        return $this->demoperiod;
    }

    /**
     * set the discount
     *
     * @param
     *        	string discount
     */
    public function setDemoperiod($demoperiod)
    {
        $this->demoperiod = $demoperiod;
    }

    /**
     * get the prd_quantity
     *
     * @return string prd_quantity
     */
    public function getSort_order()
    {
        return $this->sort_order;
    }

    /**
     * set the prd_quantity
     *
     * @param
     *        	string prd_quantity
     */
    public function setSort_order($sort_order)
    {
        $this->sort_order = $sort_order;
    }

    /**
     * get the prd_image
     *
     * @return string prd_image
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * set the prd_image
     *
     * @param
     *        	string prd_image
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getBundle_items()
    {
        return $this->bundle_items;
    }

    public function setBundle_items($bundle_items)
    {
        $this->bundle_items = $bundle_items;
    }

    public function getBundle_demos()
    {
        return $this->bundle_demos;
    }

    public function setBundle_demos($bundle_demos)
    {
        $this->bundle_demos = $bundle_demos;
    }

    public function getIs_free()
    {
        return $this->is_free;
    }

    public function setIs_free($isFree)
    {
        $this->is_free = $isFree;
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