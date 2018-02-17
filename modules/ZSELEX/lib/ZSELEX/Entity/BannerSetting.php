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
 * @ORM\Table(name="zselex_shop_banner_settings")
 */
class ZSELEX_Entity_BannerSetting extends Zikula_EntityAccess {
	
	/**
	 * id field
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $ban_set_id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Shop")
	 * @ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")
	 */
	public $shop;
	
	/**
	 * module field (image_mode)
	 * 0 - no change , 1 - stretch , 2 - crop
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $image_mode;
	
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
	public function getBan_set_id() {
		return $this->ban_set_id;
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
	 * set the image_mode
	 * 
	 * @param string $image_mode        	
	 */
	public function setImage_mode($image_mode) {
		$this->image_mode = $image_mode;
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getImage_mode() {
		return $this->image_mode;
	}
}
