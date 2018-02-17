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
 * @ORM\Entity(repositoryClass="ZSELEX_Entity_Repository_PluginRepository")
 * @ORM\Table(name="zselex_plugin")
 */
class ZSELEX_Entity_Plugin extends Zikula_EntityAccess {
	
	/**
	 * id field
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $plugin_id;
	
	/**
	 * @ORM\Column(length=250 , nullable=true)
	 */
	private $plugin_name;
	
	/**
	 * @ORM\Column(length=250 , nullable=true)
	 */
	private $identifier;
	
	/**
	 * @ORM\Column(length=250 , nullable=true)
	 */
	private $type;
	
	/**
	 * @ORM\Column(type="boolean" , nullable=true)
	 */
	private $qty_based;
	
	/**
	 * @ORM\Column(type="text" , nullable=true)
	 */
	private $description;
	
	/**
	 * @ORM\Column(type="text" , nullable=true)
	 */
	private $content;
	
	/**
	 * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
	 */
	private $price;
	
	/**
	 * @ORM\Column(type="boolean" , nullable=true)
	 */
	private $bundle;
	
	/**
	 * @ORM\Column(type="integer" , nullable=true)
	 */
	private $bundle_id;
	
	/**
	 * @ORM\Column(type="boolean" , nullable=true)
	 */
	private $top_bundle;
	
	/**
	 * @ORM\Column(type="boolean" , nullable=true)
	 */
	private $service_depended;
	
	/**
	 * @ORM\Column(type="text" , nullable=true)
	 */
	private $depended_services;
	
	/**
	 * @ORM\Column(type="boolean" , nullable=true)
	 */
	private $shop_depended;
	
	/**
	 * @ORM\Column(type="text" , nullable=true)
	 */
	private $depended_shoptypes;
	
	/**
	 * @ORM\Column(type="boolean" , nullable=true)
	 */
	private $is_editable;
	
	/**
	 * @ORM\Column(length=250 , nullable=true)
	 */
	private $func_name;
	
	/**
	 * @ORM\Column(type="boolean" , nullable=true)
	 */
	private $status;
	
	/**
	 * @ORM\Column(type="boolean" , nullable=true)
	 */
	private $demo;
	
	/**
	 * @ORM\Column(type="integer" , nullable=true)
	 */
	private $demoperiod;
	
	/**
	 * @ORM\Column(type="integer" , nullable=true)
	 */
	private $sort_order;
	
	/**
	 * @ORM\OneToMany(targetEntity="ZSELEX_Entity_ServiceShop" ,cascade={"all"}, mappedBy="plugin")
	 */
	public $plugin_service_shops;
	
	/**
	 * @ORM\OneToMany(targetEntity="ZSELEX_Entity_BundleItem" ,cascade={"all"}, mappedBy="plugin")
	 */
	public $plugin_bundle_items;
	
	/**
	 * @ORM\OneToMany(targetEntity="ZSELEX_Entity_ServiceDemo" ,cascade={"all"}, mappedBy="plugin")
	 */
	public $plugin_demos;
	
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
	 * Constructor
	 */
	public function __construct() {
		$this->plugin_shops = new ArrayCollection ();
		// $this->type_service_shops = new ArrayCollection();
		$this->plugin_bundle_items = new ArrayCollection ();
		$this->plugin_demos = new ArrayCollection ();
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getPlugin_id() {
		return $this->plugin_id;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getPlugin_name() {
		return $this->plugin_name;
	}
	
	/**
	 *
	 * @param
	 *        	string
	 */
	public function setPlugin_name($plugin_name) {
		$this->plugin_name = $plugin_name;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getIdentifier() {
		return $this->identifier;
	}
	
	/**
	 *
	 * @param
	 *        	string
	 */
	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 *
	 * @param
	 *        	string
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	 *
	 * @return int
	 */
	public function getQty_based() {
		return $this->qty_based;
	}
	
	/**
	 *
	 * @param
	 *        	int
	 */
	public function setQty_based($qty_based) {
		$this->qty_based = $qty_based;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 *
	 * @param
	 *        	string
	 */
	public function setDescription($description) {
		$this->description = $description;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}
	
	/**
	 *
	 * @param
	 *        	string
	 */
	public function setContent($content) {
		$this->content = $content;
	}
	
	/**
	 *
	 * @return decimal
	 */
	public function getPrice() {
		return $this->price;
	}
	
	/**
	 *
	 * @param
	 *        	decimal
	 */
	public function setPrice($price) {
		$this->price = $price;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function getBundle() {
		return $this->bundle;
	}
	
	/**
	 *
	 * @param
	 *        	boolean
	 */
	public function setBundle($bundle) {
		$this->bundle = $bundle;
	}
	
	/**
	 *
	 * @return int
	 */
	public function getBundle_id() {
		return $this->bundle_id;
	}
	
	/**
	 *
	 * @param
	 *        	int
	 */
	public function setBundle_id($bundle_id) {
		$this->bundle_id = $bundle_id;
	}
	
	/**
	 *
	 * @return int
	 */
	public function getTop_bundle() {
		return $this->top_bundle;
	}
	
	/**
	 *
	 * @param
	 *        	int
	 */
	public function setTop_bundle($top_bundle) {
		$this->top_bundle = $top_bundle;
	}
	
	/**
	 *
	 * @return int
	 */
	public function getService_depended() {
		return $this->service_depended;
	}
	
	/**
	 *
	 * @param
	 *        	int
	 */
	public function setService_depended($service_depended) {
		$this->service_depended = $service_depended;
	}
	
	/**
	 *
	 * @return json
	 */
	public function getDepended_services() {
		return $this->depended_services;
	}
	
	/**
	 *
	 * @param
	 *        	json
	 */
	public function setDepended_services($depended_services) {
		$this->depended_services = $depended_services;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function getShop_depended() {
		return $this->shop_depended;
	}
	
	/**
	 *
	 * @param
	 *        	boolean
	 */
	public function setShop_depended($shop_depended) {
		$this->shop_depended = $shop_depended;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getDepended_shoptypes() {
		return $this->depended_shoptypes;
	}
	
	/**
	 *
	 * @param
	 *        	string
	 */
	public function setDepended_shoptypes($depended_shoptypes) {
		$this->depended_shoptypes = $depended_shoptypes;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function getIs_editable() {
		return $this->is_editable;
	}
	
	/**
	 *
	 * @param
	 *        	boolean
	 */
	public function setIs_editable($is_editable) {
		$this->is_editable = $is_editable;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getFunc_name() {
		return $this->func_name;
	}
	
	/**
	 *
	 * @param
	 *        	string
	 */
	public function setFunc_name($func_name) {
		$this->func_name = $func_name;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 *
	 * @param
	 *        	boolean
	 */
	public function setStatus($status) {
		$this->status = $status;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function getDemo() {
		return $this->demo;
	}
	
	/**
	 *
	 * @param
	 *        	boolean
	 */
	public function setDemo($demo) {
		$this->demo = $demo;
	}
	
	/**
	 *
	 * @return int
	 */
	public function getDemoperiod() {
		return $this->demoperiod;
	}
	
	/**
	 *
	 * @param
	 *        	int
	 */
	public function setDemoperiod($demoperiod) {
		$this->demoperiod = $demoperiod;
	}
	
	/**
	 *
	 * @return int
	 */
	public function getSort_order() {
		return $this->sort_order;
	}
	
	/**
	 *
	 * @param
	 *        	int
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
	public function getPlugin_shops() {
		return $this->plugin_shops;
	}
	public function setPlugin_shops($plugin_shops) {
		$this->plugin_shops = $plugin_shops;
	}
	
	/*
	 * public function getType_service_shops() {
	 * return $this->type_service_shops;
	 * }
	 *
	 * public function setType_service_shops($type_service_shops) {
	 * $this->type_service_shops = $type_service_shops;
	 * }
	 */
	public function getPlugin_bundle_items() {
		return $this->plugin_bundle_items;
	}
	public function setPlugin_bundle_items($plugin_bundle_items) {
		$this->plugin_bundle_items = $plugin_bundle_items;
	}
}

