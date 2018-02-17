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
 * @ORM\Table(name="zselex_service_order")
 */
class ZSELEX_Entity_ServiceOrder extends Zikula_EntityAccess {
	
	/**
	 * id field
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * user id
	 *
	 * @ORM\Column(length=255, nullable=true)
	 */
	private $order_id;
	
	/**
	 * user id
	 *
	 * @ORM\Column(type="integer")
	 */
	private $user_id;
	
	/**
	 * @ORM\Column(length=255, nullable=true)
	 */
	private $status;
	
	/**
	 * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
	 */
	private $grand_total;
	
	/**
	 * @ORM\Column(length=255, nullable=true)
	 */
	private $payment_method = '';
	
	/**
	 * @ORM\Column(length=255, nullable=true)
	 */
	private $transaction_id = '';
	
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
	public function getId() {
		return $this->id;
	}
	public function getOrder_id() {
		return $this->order_id;
	}
	public function setOrder_id($order_id) {
		$this->order_id = $order_id;
	}
	public function getUser_id() {
		return $this->user_id;
	}
	public function setUser_id($user_id) {
		$this->user_id = $user_id;
	}
	public function getGrand_total() {
		return $this->grand_total;
	}
	public function setGrand_total($grand_total) {
		$this->grand_total = $grand_total;
	}
	public function getStatus() {
		return $this->status;
	}
	public function setStatus($status) {
		$this->status = $status;
	}
	public function getPayment_method() {
		return $this->payment_method;
	}
	public function setPayment_method($payment_method) {
		$this->payment_method = $payment_method;
	}
	public function getTransaction_id() {
		return $this->transaction_id;
	}
	public function setTransaction_id($transaction_id) {
		$this->transaction_id = $transaction_id;
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
