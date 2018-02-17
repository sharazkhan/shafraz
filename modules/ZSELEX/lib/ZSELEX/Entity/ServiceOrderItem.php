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
 * @ORM\Table(name="zselex_service_orderitems")
 */
class ZSELEX_Entity_ServiceOrderItem extends Zikula_EntityAccess {
	
	/**
	 * id field
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $order_item_id;
	
	/**
	 * @ORM\Column(length=255, nullable=true)
	 */
	private $order_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	private $plugin_id;
	
	/**
	 * @ORM\Column(length="255")
	 */
	private $type;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	private $shop_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	private $user_id = 0;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	private $owner_id = 0;
	
	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $quantity = 0;
	
	/**
	 * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
	 */
	private $price = 0.0000;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	private $status;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	private $service_status;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	private $timer_days = 0;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	private $qty_based = 0;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $is_bundle;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	private $bundle_id;
	
	/**
	 * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
	 */
	private $subtotal;
	
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
	public function __construct() {
	}
	public function getOrder_item_id() {
		return $this->order_item_id;
	}
	public function getOrder_id() {
		return $this->order_id;
	}
	public function setOrder_id($order_id) {
		$this->order_id = $order_id;
	}
	public function getPlugin_id() {
		return $this->plugin_id;
	}
	public function setPlugin_id($plugin_id) {
		$this->plugin_id = $plugin_id;
	}
	public function getType() {
		return $this->type;
	}
	public function setType($type) {
		$this->type = $type;
	}
	public function getShop_id() {
		return $this->shop_id;
	}
	public function setShop_id($shop_id) {
		$this->shop_id = $shop_id;
	}
	public function getUser_id() {
		return $this->user_id;
	}
	public function setUser_id($user_id) {
		$this->user_id = $user_id;
	}
	public function getOwner_id() {
		return $this->owner_id;
	}
	public function setOwner_id($owner_id) {
		$this->owner_id = $owner_id;
	}
	public function getQuantity() {
		return $this->quantity;
	}
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}
	public function getPrice() {
		return $this->price;
	}
	public function setPrice($price) {
		$this->price = $price;
	}
	public function getStatus() {
		return $this->status;
	}
	public function setStatus($status) {
		$this->status = $status;
	}
	public function getService_status() {
		return $this->service_status;
	}
	public function setService_status($service_status) {
		$this->service_status = $service_status;
	}
	public function getTimer_days() {
		return $this->timer_days;
	}
	public function setTimer_days($timer_days) {
		$this->timer_days = $timer_days;
	}
	public function getQty_based() {
		return $this->qty_based;
	}
	public function setQty_based($qty_based) {
		$this->qty_based = $qty_based;
	}
	public function getIs_bundle() {
		return $this->is_bundle;
	}
	public function setIs_bundle($is_bundle) {
		$this->is_bundle = $is_bundle;
	}
	public function getBundle_id() {
		return $this->bundle_id;
	}
	public function setBundle_id($bundle_id) {
		$this->bundle_id = $bundle_id;
	}
	public function getSubtotal() {
		return $this->subtotal;
	}
	public function setSubtotal($subtotal) {
		$this->subtotal = $subtotal;
	}
	public function getCr_date() {
		return $this->cr_date;
	}
	public function setCr_date($cr_date) {
		$this->cr_date = $cr_date;
	}
	public function getCr_uid() {
		return $this->cr_uid;
	}
	public function setCr_uid($cr_uid) {
		$this->cr_uid = $cr_uid;
	}
	public function getLu_date() {
		return $this->lu_date;
	}
	public function setLu_date($lu_date) {
		$this->lu_date = $lu_date;
	}
	public function getLu_uid() {
		return $this->lu_uid;
	}
	public function setLu_uid($lu_uid) {
		$this->lu_uid = $lu_uid;
	}
}

