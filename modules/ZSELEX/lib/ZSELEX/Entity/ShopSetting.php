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
 * @ORM\Table(name="zselex_shop_a_settings")
 */
class ZSELEX_Entity_ShopSetting extends Zikula_EntityAccess {
	
	/**
	 * id field
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	public $id;
	
	/**
	 * @ORM\Column(name="shop_id" ,type="integer" , nullable=false)
	 */
	public $shop;
	
	/**
	 * @ORM\Column(length="100" , nullable=true)
	 */
	public $default_img_frm;
	
	/**
	 * @ORM\Column(type="boolean" , options={"default" = 0} , nullable=true)
	 */
	public $main;
	
	/**
	 * @ORM\Column(length="250" , nullable=true)
	 */
	public $theme;
	
	/**
	 * @ORM\Column(type="text" , nullable=true)
	 */
	public $opening_hours;
	
	/**
	 * Country description
	 *
	 * @ORM\Column(type="boolean" , options={"default" = 0} , nullable=true)
	 */
	public $no_payment;
	
	/**
	 * Country description
	 *
	 * @ORM\Column(type="text" , nullable=true)
	 */
	public $link_to_homepage;
	
	/**
	 * Country description
	 *
	 * @ORM\Column(type="text" , nullable=true)
	 */
	public $terms_conditions;
	
	/**
	 * Constructor
	 */
	public function __construct() {
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
	 * set the Country ID
	 * 
	 * @param string $module        	
	 */
	public function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * Get cityId.
	 *
	 * @return integer
	 */
	public function getShop() {
		return $this->shop;
	}
	public function setShop(ZSELEX_Entity_Shop $shop_id) {
		$this->shop = $shop_id;
		
		/*
		 * if ($this->shop !== null) {
		 * $this->shop->removeShop($this);
		 * }
		 *
		 * if ($shop !== null) {
		 * $shop->addShop($this);
		 * }
		 *
		 * $this->shop = $shop;
		 * return $this;
		 */
	}
	public function backUP() {
	/**
	 * @ORM\OneToOne(targetEntity="ZSELEX_Entity_Shop", cascade={"all"}, inversedBy="shop_settings")
	 * @ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")
	 */
	}
	public function __toString() {
		return $this->name;
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getDefault_img_frm() {
		return $this->default_img_frm;
	}
	
	/**
	 * set the Country Name
	 * 
	 * @param string $module        	
	 */
	public function setDefault_img_frm($default_img_frm) {
		$this->default_img_frm = $default_img_frm;
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getMain() {
		return $this->main;
	}
	
	/**
	 * set the Country Desc
	 * 
	 * @param string $module        	
	 */
	public function setMain($main) {
		$this->main = $main;
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getTheme() {
		return $this->theme;
	}
	
	/**
	 * set the Country Status
	 * 
	 * @param string $module        	
	 */
	public function setTheme($theme) {
		$this->theme = $theme;
	}
	
	/**
	 * Get created user id.
	 *
	 * @return integer[]
	 */
	public function getOpening_hours() {
		return $this->opening_hours;
	}
	
	/**
	 * Set created user id.
	 *
	 * @param integer[] $createdUserId.        	
	 *
	 * @return void
	 */
	public function setOpening_hours($opening_hours) {
		$this->opening_hours = $opening_hours;
	}
	
	/**
	 * Get updated user id.
	 *
	 * @return integer[]
	 */
	public function getNo_payment() {
		return $this->no_payment;
	}
	
	/**
	 * Set updated user id.
	 *
	 * @param integer[] $updatedUserId.        	
	 *
	 * @return void
	 */
	public function setNo_payment($no_payment) {
		$this->no_payment = $no_payment;
	}
	
	/**
	 * Get created date.
	 *
	 * @return datetime[]
	 */
	public function getLink_to_homepage() {
		return $this->link_to_homepage;
	}
	
	/**
	 * Set created date.
	 *
	 * @param datetime[] $createdDate.        	
	 *
	 * @return void
	 */
	public function setLink_to_homepage($link_to_homepage) {
		$this->link_to_homepage = $link_to_homepage;
	}
	
	/**
	 * Get updated date.
	 *
	 * @return datetime[]
	 */
	public function getTerms_conditions() {
		return $this->terms_conditions;
	}
	public function setTerms_conditions($terms_conditions) {
		$this->terms_conditions = $terms_conditions;
	}
}

