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
 * @ORM\Table(name="zselex_service_bundle_items")
 */
class ZSELEX_Entity_BundleItem extends Zikula_EntityAccess {
	
	/**
	 * id field
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Bundle", inversedBy="bundle_items")
	 * @ORM\JoinColumn(name="bundle_id", referencedColumnName="bundle_id")
	 */
	private $bundle = 0;
	
	/**
	 * @ORM\Column(length=255 , nullable=true)
	 */
	public $service_name;
	
	/**
	 * @ORM\Column(length=255 , nullable=true)
	 */
	public $servicetype;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Plugin", inversedBy="plugin_bundle_items")
	 * @ORM\JoinColumn(name="plugin_id", referencedColumnName="plugin_id")
	 */
	private $plugin = 0;
	
	/**
	 * field (original_price)
	 *
	 * @ORM\Column(type="decimal" , scale=2 , nullable=true)
	 */
	private $price;
	
	/**
	 * @ORM\Column(type="integer" , nullable=true)
	 */
	private $qty;
	
	/**
	 * @ORM\Column(type="boolean" , nullable=true)
	 */
	private $qty_based;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		// $this->shop_branches = new ArrayCollection();
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * get the product_name
	 * 
	 * @return string
	 */
	public function getBundle() {
		return $this->bundle;
	}
	
	/**
	 * set the product_name
	 * 
	 * @param string $product_name        	
	 */
	public function setBundle(ZSELEX_Entity_Bundle $bundle) {
		$this->bundle = $bundle;
	}
	
	/**
	 * get the shop ID
	 * 
	 * @return integer
	 */
	public function getService_name() {
		return $this->service_name;
	}
	
	/**
	 * set the shop ID
	 * 
	 * @param int $shop        	
	 */
	public function setService_name($service_name) {
		$this->service_name = $service_name;
	}
	
	/**
	 * get the category ID
	 * 
	 * @return integer
	 */
	public function getServicetype() {
		return $this->servicetype;
	}
	
	/**
	 * set the category ID
	 * 
	 * @param
	 *        	integer
	 */
	public function setServicetype($servicetype) {
		$this->servicetype = $servicetype;
	}
	
	/**
	 * get the urltitle
	 * 
	 * @return string urltitle
	 */
	public function getPlugin() {
		return $this->plugin;
	}
	
	/**
	 * set the urltitle
	 * 
	 * @param
	 *        	string urltitle
	 */
	public function setPlugin(ZSELEX_Entity_Plugin $plugin) {
		$this->plugin = $plugin;
	}
	
	/**
	 * get the prd_description
	 * 
	 * @return string prd_description
	 */
	public function getPrice() {
		return $this->price;
	}
	
	/**
	 * set the prd_description
	 * 
	 * @param
	 *        	string prd_description
	 */
	public function setPrice($price) {
		$this->price = $price;
	}
	
	/**
	 * get the keywords
	 * 
	 * @return string keywords
	 */
	public function getQty() {
		return $this->qty;
	}
	
	/**
	 * set the keywords
	 * 
	 * @param
	 *        	string keywords
	 */
	public function setQty($qty) {
		$this->qty = $qty;
	}
	
	/**
	 * get the original_price
	 * 
	 * @return string original_price
	 */
	public function getQty_based() {
		return $this->qty_based;
	}
	
	/**
	 * set the original_price
	 * 
	 * @param
	 *        	string original_price
	 */
	public function setQty_based($qty_based) {
		$this->qty_based = $qty_based;
	}
}

