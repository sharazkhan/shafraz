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
 * @ORM\Entity(repositoryClass="ZSELEX_Entity_Repository_UrlRepository")
 * @ORM\Table(name="zselex_urls" , indexes={@ORM\index(name="search_url", columns={"type" , "url"})})
 */
class ZSELEX_Entity_Url extends Zikula_EntityAccess {
	
	/**
	 * id field
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $url_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	private $type_id;
	
	/**
	 * @ORM\Column(length=25)
	 */
	private $type;
	
	/**
	 * @ORM\Column(length=200)
	 */
	public $url;
	
	/**
	 * @ORM\Column(type="integer" , nullable=true)
	 */
	private $shop_id = 0;
	
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
	public function getUrl_id() {
		return $this->url_id;
	}
	
	/**
	 * get the branch_name
	 * 
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * set the branch_name
	 * 
	 * @param string $branch_name        	
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getType_id() {
		return $this->type_id;
	}
	
	/**
	 * set the Country Desc
	 * 
	 * @param string $module        	
	 */
	public function setType_id($type_id) {
		$this->type_id = $type_id;
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * set the Country Status
	 * 
	 * @param string $module        	
	 */
	public function setUrl($url) {
		$this->url = $url;
	}
	public function getShop_id() {
		return $this->shop_id;
	}
	public function setShop_id($shop_id) {
		$this->shop_id = $shop_id;
	}
}
