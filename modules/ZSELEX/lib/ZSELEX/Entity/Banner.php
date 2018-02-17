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
 * @ORM\Table(name="zselex_shop_banner")
 */
class ZSELEX_Entity_Banner extends Zikula_EntityAccess {
	
	/**
	 * id field
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $shop_banner_id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Shop", inversedBy="shop_banners")
	 * @ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")
	 */
	public $shop;
	
	/**
	 * module field (branch_name)
	 *
	 * @ORM\Column(length=250 , nullable=true)
	 */
	private $banner_image;
	
	/**
	 * Event All Day or not
	 *
	 * @ORM\Column(type="boolean" , nullable=true)
	 */
	private $status;
	
	/**
	 * module field (height)
	 *
	 * @ORM\Column(length=250 , nullable=true)
	 */
	private $height;
	
	/**
	 * module field (width)
	 *
	 * @ORM\Column(length=250 , nullable=true)
	 */
	private $width;
	
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
	public function getShop_banner_id() {
		return $this->shop_banner_id;
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
	public function getBanner_image() {
		return $this->banner_image;
	}
	
	/**
	 * set the Country Desc
	 * 
	 * @param string $module        	
	 */
	public function setBanner_image($banner_image) {
		$this->banner_image = $banner_image;
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
	 * get the height
	 *
	 * @return string
	 */
	public function getHeight() {
		return $this->height;
	}
	
	/**
	 * set the height
	 *
	 * @param
	 *        	string
	 */
	public function setHeight($height) {
		$this->height = $height;
	}
	
	/**
	 * get the width
	 *
	 * @return string
	 */
	public function getWidth() {
		return $this->width;
	}
	
	/**
	 * set the width
	 *
	 * @param
	 *        	string
	 */
	public function setWidth($width) {
		$this->width = $width;
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
