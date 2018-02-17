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
 * @ORM\Entity(repositoryClass="ZTEXT_Entity_Repository_PageRepository")
 * @ORM\Table(name="ztext_pages")
 */
class ZTEXT_Entity_Page extends Zikula_EntityAccess {

    /**
     * id field
     *
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $text_id;

    /**
     * shop_id
     * 
     * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Shop", inversedBy="shop_pages")
     * @ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")
     */
    public $shop;

    /**
     * headertext(Title)
     *
     * @ORM\Column(length=250 , nullable=true)
     */
    private $headertext;

    /**
     * headertext(Title)
     *
     * @ORM\Column(length=250 , nullable=true)
     */
    private $urltitle;

    /**
     * bodytext (Content)
     *
     * @ORM\Column(type="text" , nullable=true)
     */
    private $bodytext;

    /**
     * Active
     * 
     * @ORM\Column(type="boolean" , nullable=true) 
     */
    private $active;

    /**
     * Display on front end or not
     * 
     * @ORM\Column(type="boolean" , nullable=true) 
     */
    private $displayonfront=0;

    /**
     * Image
     *
     * @ORM\Column(length=250 , nullable=true)
     */
    private $image='';
    
     /**
     * extension
     *
     * @ORM\Column(length=250 , nullable=true)
     */
    private $extension='';

    /**
     * Image
     *
     * @ORM\Column(length=250 , nullable=true)
     */
    private $doc='';

    /**
     * Link
     *
     * @ORM\Column(length=250 , nullable=true)
     */
    private $link;

    /**
     * Sort Order
     * 
     * @ORM\Column(type="integer" , nullable=true) 
     */
    private $sort_order;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @var datetime $cr_date.
     */
    protected $cr_date;

    /**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="create")
     * @var integer $cr_uid.
     */
    protected $cr_uid;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @var datetime $lu_date.
     */
    protected $lu_date;

    /**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="update")
     * @var integer $lu_uid.
     */
    protected $lu_uid;

    /**
     * Constructor 
     */
    public function __construct() {

        //$this->shop_branches = new ArrayCollection();
    }

    /**
     * get the record ID
     * @return integer
     */
    public function getText_id() {
        return $this->text_id;
    }

    /**
     * get the branch_name
     * @return string
     */
    public function getShop() {
        return $this->shop;
    }

    /**
     * set the shop_id
     * @param string $branch_name
     */
    public function setShop(ZSELEX_Entity_Shop $shop) {
        $this->shop = $shop;
    }

    /**
     * get the record ID
     * @return integer
     */
    public function getHeadertext() {
        return $this->headertext;
    }

    /**
     * set the Country Desc
     * @param string $module 
     */
    public function setHeadertext($headertext) {
        $this->headertext = $headertext;
    }

    public function getUrltitle() {
        return $this->urltitle;
    }

    public function setUrltitle($urltitle) {
        $this->urltitle = $urltitle;
    }

    /**
     * get the Image
     * @return integer
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * set the Country Status
     * @param string $module 
     */
    public function setImage($image) {
        $this->image = $image;
    }
    
     /**
     * get the extension
     * @return string
     */
    public function getExtension() {
        return $this->extension;
    }
    
     /**
     * set the extension
     * @param string $extension
     */
    public function setExtension($extension) {
        $this->extension = $extension;
    }
    
    /**
     * get the doc
     * @return integer
     */
    public function getDoc() {
        return $this->doc;
    }
    
     /**
     * set the Country Status
     * @param string $module 
     */
    public function setDoc($doc) {
        $this->doc = $doc;
    }

    /**
     * get displayonfront
     * @return boolean
     */
    public function getDisplayonfront() {
        return $this->displayonfront;
    }

    /**
     * set displayonfront
     * @param string $displayonfront 
     */
    public function setDisplayonfront($displayonfront) {
        $this->displayonfront = $displayonfront;
    }

    /**
     * get bodytext
     * @return boolean
     */
    public function getBodytext() {
        return $this->bodytext;
    }

    /**
     * set displayonfront
     * @param string $displayonfront 
     */
    public function setBodytext($bodytext) {
        $this->bodytext = $bodytext;
    }

    /**
     * get active
     * @return boolean
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * set active
     * @param string $active 
     */
    public function setActive($bodytext) {
        $this->active = $bodytext;
    }

    /**
     * get active
     * @return boolean
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * set active
     * @param string $active 
     */
    public function setLink($link) {
        $this->link = $link;
    }

    /**
     * get active
     * @return boolean
     */
    public function getSort_order() {
        return $this->sort_order;
    }

    /**
     * set active
     * @param string $active 
     */
    public function setSort_order($sort_order) {
        $this->sort_order = $sort_order;
    }

    /**
     * Get created user id.
     *
     * @return integer[]
     */
    public function getCr_date() {
        return $this->cr_date;
    }

    /**
     * Set created user id.
     *
     * @param integer[] $createdUserId.
     *
     * @return void
     */
    public function setCr_date($cr_date) {
        $this->cr_date = $cr_date;
    }

    /**
     * Get updated user id.
     *
     * @return integer[]
     */
    public function getCr_uid() {
        return $this->cr_uid;
    }

    /**
     * Set updated user id.
     *
     * @param integer[] $updatedUserId.
     *
     * @return void
     */
    public function setCr_uid($cr_uid) {
        $this->cr_uid = $cr_uid;
    }

    /**
     * Get created date.
     *
     * @return datetime[]
     */
    public function getLu_date() {
        return $this->lu_date;
    }

    /**
     * Set created date.
     *
     * @param datetime[] $createdDate.
     *
     * @return void
     */
    public function setLu_date($lu_date) {
        $this->lu_date = $lu_date;
    }

    /**
     * Get updated date.
     *
     * @return datetime[]
     */
    public function getLu_uid() {
        return $this->lu_uid;
    }

    /**
     * Get updated date.
     *
     * @return datetime[]
     */
    public function setLu_uid($lu_uid) {
        $this->lu_uid = $lu_uid;
    }

    public function getShop_branches() {
        return $this->shop_branches;
    }

    public function setShop_branches($shop_branches) {
        $this->shop_branches = $shop_branches;
    }

}
