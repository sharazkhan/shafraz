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
 * @ORM\Table(name="zselex_orderitems")
 */
class ZSELEX_Entity_OrderItem extends Zikula_EntityAccess {
	
	/**
	 * id field
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $item_id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Product")
	 * @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
	 */
	public $product;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Shop")
	 * @ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")
	 */
	public $shop;
	
	/**
	 * @ORM\Column(length=255, nullable=true)
	 */
	private $order_id;
	
	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $quantity;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $product_options;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $prd_answer;
	
	/**
	 * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
	 */
	private $price;
	
	/**
	 * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
	 */
	private $options_price;
	
	/**
	 * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
	 */
	private $total;
	
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
	public function __construct() {
	}
	public function getItem_id() {
		return $this->item_id;
	}
	public function getProduct() {
		return $this->product;
	}
	public function setProduct(ZSELEX_Entity_Product $product) {
		$this->product = $product;
	}
	public function getShop() {
		return $this->shop;
	}
	public function setShop(ZSELEX_Entity_Shop $shop) {
		$this->shop = $shop;
	}
	public function getOrder_id() {
		return $this->order_id;
	}
	public function setOrder_id($order_id) {
		$this->order_id = $order_id;
	}
	public function getQuantity() {
		return $this->quantity;
	}
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}
	public function getProduct_options() {
		return $this->product_options;
	}
	public function setProduct_options($product_options) {
		$this->product_options = $product_options;
	}
	public function getPrice() {
		return $this->price;
	}
	public function setPrice($price) {
		$this->price = $price;
	}
	public function getOptions_price() {
		return $this->options_price;
	}
	public function setOptions_price($options_price) {
		$this->options_price = $options_price;
	}
	public function getTotal() {
		return $this->total;
	}
	public function setTotal($total) {
		$this->total = $total;
	}
	public function getPrd_answer() {
		return $this->prd_answer;
	}
	public function setPrd_answer($prd_answer) {
		$this->prd_answer = $prd_answer;
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

