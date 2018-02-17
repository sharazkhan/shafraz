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
 * @ORM\Entity(repositoryClass="ZSELEX_Entity_Repository_CategoryRepository")
 * @ORM\Table(name="zselex_category")
 */
class ZSELEX_Entity_Category extends Zikula_EntityAccess {
	
	/**
	 * id field
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $category_id;
	
	/**
	 * module field (category_name)
	 *
	 * @ORM\Column(length=50)
	 */
	private $category_name;
	
	/**
	 * Country description
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $description;
	
	/**
	 * Event All Day or not
	 *
	 * @ORM\Column(type="boolean")
	 */
	private $status;
	
	/**
	 * @ORM\ManyToMany(targetEntity="ZSELEX_Entity_Shop" , cascade={"all"}, mappedBy="shop_to_category")
	 */
	protected $categories_shop;
	
	/**
	 * @ORM\Column(type="datetime")
	 * @Gedmo\Timestampable(on="create")
	 * 
	 * @var datetime $cr_date.
	 */
	public $cr_date;
	
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
	public $lu_date;
	
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
	public function __construct() {
		$this->shop_categories = new ArrayCollection ();
		$this->categories_shop = new ArrayCollection ();
	}
	public function addShops(ZSELEX_Entity_Shop $shops) {
		$this->categories_shop [] = $shops;
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getCategory_id() {
		return $this->category_id;
	}
	
	/**
	 * get the category_name
	 * 
	 * @return string
	 */
	public function getCategory_name() {
		return $this->category_name;
	}
	
	/**
	 * set the Country Name
	 * 
	 * @param string $category_name        	
	 */
	public function setCategory_name($category_name) {
		$this->category_name = $category_name;
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * set the Country Desc
	 * 
	 * @param string $module        	
	 */
	public function setDescription($description) {
		$this->description = $description;
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * set the Country Status
	 * 
	 * @param string $module        	
	 */
	public function setStatus($status) {
		$this->status = $status;
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
	public function getShop_categories() {
		return $this->shop_categories;
	}
	public function setShop_categories($shop_categories) {
		$this->shop_categories = $shop_categories;
	}
	public function getCategories_shop() {
		return $this->categories_shop;
	}
	public function setCategories_shop($categories_shop) {
		$this->categories_shop = $categories_shop;
	}
	public function getOldArray() {
		$array = parent::toArray ();
		unset ( $array ['shop_categories'] );
		return $array;
	}
}

