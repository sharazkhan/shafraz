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
 * @ORM\Entity(repositoryClass="ZSELEX_Entity_Repository_ShopRepository")
 * @ORM\Table(name="zselex_shop_announcement")
 */
class ZSELEX_Entity_Announcement extends Zikula_EntityAccess {
	
	/**
	 * id field
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $ann_id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Shop", inversedBy="shop_announcements")
	 * @ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")
	 */
	public $shop;
	
	/**
	 * module field (branch_name)
	 *
	 * @ORM\Column(type="text" , nullable=true)
	 */
	private $text;
	
	/**
	 * @ORM\Column(type="date" , nullable=true)
	 */
	private $start_date;
	
	/**
	 * @ORM\Column(type="date" , nullable=true)
	 */
	private $end_date;
	
	/**
	 * Event All Day or not
	 *
	 * @ORM\Column(type="boolean")
	 */
	private $status;
	
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
		
		// $this->shop_branches = new ArrayCollection();
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getAnn_id() {
		return $this->ann_id;
	}
	
	/**
	 * get the branch_name
	 * 
	 * @return string
	 */
	public function getShop() {
		return $this->shop;
	}
	
	/**
	 * set the branch_name
	 * 
	 * @param string $branch_name        	
	 */
	public function setShop(ZSELEX_Entity_Shop $shop) {
		$this->shop = $shop;
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getText() {
		return $this->text;
	}
	
	/**
	 * set the Country Desc
	 * 
	 * @param string $module        	
	 */
	public function setText($text) {
		$this->text = $text;
	}
	public function getStart_date() {
		return $this->start_date;
	}
	public function setStart_date($start_date) {
		$this->start_date = $start_date;
	}
	public function getEnd_date() {
		return $this->end_date;
	}
	public function setEnd_date($end_date) {
		$this->end_date = $end_date;
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
	public function getShop_branches() {
		return $this->shop_branches;
	}
	public function setShop_branches($shop_branches) {
		$this->shop_branches = $shop_branches;
	}
}

