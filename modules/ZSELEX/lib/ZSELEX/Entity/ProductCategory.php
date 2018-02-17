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
 * @ORM\Table(name="zselex_product_categories")
 */
class ZSELEX_Entity_ProductCategory extends Zikula_EntityAccess {
	
	/**
	 * id field
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $prd_cat_id;
	
	/**
	 * module field (category_name)
	 *
	 * @ORM\Column(length=250)
	 */
	private $prd_cat_name;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Shop", cascade={"all"}, inversedBy="shop_prod_categories" , fetch="EAGER")
	 * @ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")
	 */
	private $shop;
	
	/**
	 * Event All Day or not
	 *
	 * @ORM\Column(type="integer")
	 */
	private $user_id = 0;
	
	/**
	 * Event All Day or not
	 *
	 * @ORM\Column(type="boolean")
	 */
	private $status;
	
	/**
	 * @ORM\ManyToMany(targetEntity="ZSELEX_Entity_Product" , cascade={"all"}, mappedBy="product_to_category" , fetch="EAGER")
	 */
	protected $productcategory_products;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		
		// $this->product_categories = new ArrayCollection();
		$this->productcategory_products = new ArrayCollection ();
	}
	public function addProducts(ZSELEX_Entity_Product $products) {
		$this->productcategory_products [] = $products;
	}
	public function removeCategory(ZSELEX_Entity_ProductCategory $category) {
		$this->productcategory_products->removeElement ( $category );
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getPrd_cat_id() {
		return $this->prd_cat_id;
	}
	
	/**
	 * get the category_name
	 * 
	 * @return string
	 */
	public function getPrd_cat_name() {
		return $this->prd_cat_name;
	}
	
	/**
	 * set the Country Name
	 * 
	 * @param string $category_name        	
	 */
	public function setPrd_cat_name($prd_cat_name) {
		$this->prd_cat_name = $prd_cat_name;
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getShop() {
		return $this->shop;
	}
	
	/**
	 * set the Country Desc
	 * 
	 * @param string $module        	
	 */
	public function setShop(ZSELEX_Entity_Shop $shop) {
		$this->shop = $shop;
	}
	
	/**
	 * get the User ID
	 * 
	 * @return integer
	 */
	public function getUser_id() {
		return $this->user_id;
	}
	
	/**
	 * set the User ID
	 * 
	 * @param int $user_id        	
	 */
	public function setUser_id($user_id) {
		$this->user_id = $user_id;
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
	 * set the Status
	 * 
	 * @param string $module        	
	 */
	public function setStatus($status) {
		$this->status = $status;
	}
	public function getProduct_categories() {
		return $this->product_categories;
	}
	public function setProduct_categories($product_categories) {
		$this->product_categories = $product_categories;
	}
	public function getProductcategory_products() {
		return $this->productcategory_products;
	}
	public function setProductcategory_products($productcategory_products) {
		$this->productcategory_products = $productcategory_products;
	}
}

